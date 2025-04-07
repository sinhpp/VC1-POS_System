<?php
// ProductScanController.php
session_start();
require_once 'Database/Database.php';
require_once 'Models/ProductModel.php';
require_once 'Models/OrderModel.php';
require_once __DIR__ . '../../views/vendor/fpdf186/fpdf.php';

class ProductScanController {
    private $productModel;
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
        if (!isset($_SESSION['order'])) {
            $_SESSION['order'] = [];
        }
        $this->productModel = new ProductModel();
        // $this->orderModel = new OrderModel();
    }

    public function index()
    {
        $data = [
            'order' => $_SESSION['order'],
            'products' => [],
            'error' => null
        ];

        if (isset($_SESSION['product'])) {
            $data['products'] = [$_SESSION['product']];
        }

        if (isset($_SESSION['error'])) {
            $data['error'] = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        require_once __DIR__ . '/../views/order/product-scanning.php';
    }

    public function scan()
    {
        if (isset($_POST['scan'])) {
            $barcode = filter_input(INPUT_POST, 'barcode', FILTER_SANITIZE_STRING);

            if (empty($barcode)) {
                $_SESSION['error'] = "Please provide a valid barcode!";
                header("Location: /order");
                exit();
            }

            // Fetch product by barcode
            $product = $this->productModel->getProductByBarcode($barcode);

            if ($product) {
                if ($product['stock'] > 0) {
                    // Reduce stock in the database
                    $this->productModel->updateStock($barcode, 1);
    
                    // Automatically add to order
                    $found = false;
    
                    // Check if the product already exists in the order
                    foreach ($_SESSION['order'] as &$item) {
                        if ($item['barcode'] === $barcode) {
                            if ($item['quantity'] + 1 <= $product['stock']) {
                                $item['quantity'] += 1; // Increment quantity
                                $found = true;
                            } else {
                                $_SESSION['error'] = "Cannot add more items; stock limit reached!";
                            }
                            break;
                        }
                    }
    
                    // If the product is new in the order list
                    if (!$found) {
                        $product['quantity'] = 1;
                        $_SESSION['order'][] = $product; // Add new product to order
                    }
    
                    // Optionally, store the product in the session for displaying details
                    $_SESSION['product'] = $product;
                } else {
                    $_SESSION['error'] = "Product is out of stock!";
                }
            } else {
                $_SESSION['error'] = "Product not found!";
            }
        }
        header("Location: /order"); // Redirect to refresh the order list
        exit();
    }


    public function add()
    {
        if (isset($_POST['add'])) {
            $barcode = filter_input(INPUT_POST, 'barcode', FILTER_SANITIZE_STRING);
            $product = $this->productModel->getProductByBarcode($barcode);

            if ($product && $product['stock'] > 0) {
                $found = false;

                // Check if the product already exists in the order
                foreach ($_SESSION['order'] as &$item) {
                    if ($item['barcode'] === $barcode) {
                        if ($item['quantity'] +1 <= $product['stock']) {
                            $item['quantity'] -= 1;

                            // Reduce stock in the database
                            $this->productModel->updateStock($barcode, 1);
                        } else {
                            $_SESSION['error'] = "Cannot add more items; stock limit reached!";
                        }
                        $found = true;
                        break;
                    }
                }

                // If the product is new in the order list
                if (!$found) {
                    $product['quantity'] = 1;

                    // Reduce stock in the database
                    $this->productModel->updateStock($barcode, 1);

                    $_SESSION['order'][] = $product;
                }
            } else {
                $_SESSION['error'] = "Product is out of stock or not found!";
            }
        }
        header("Location: /order");
        exit();
    }


    public function delete()
    {
        if (isset($_POST['delete'])) {
            $index = filter_input(INPUT_POST, 'index', FILTER_VALIDATE_INT);
            if ($index !== false && isset($_SESSION['order'][$index])) {
                unset($_SESSION['order'][$index]);
                $_SESSION['order'] = array_values($_SESSION['order']);
            }
        }
        header("Location: /order");
        exit();
    }

    public function checkout()
    {
        if (empty($_SESSION['order'])) {
            header("Location: /order");
            exit();
        }

        $totalPrice = 0;
        foreach ($_SESSION['order'] as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        $data = [
            'order' => $_SESSION['order'],
            'totalPrice' => $totalPrice,
            'deliveryDate' => date('Y-m-d', strtotime('+7 days'))
        ];

        require_once __DIR__ . '/../views/order/checkout.php';
    }

    public function processCheckout()
    {
        // This method can remain as a fallback or be removed if not used
        if (!isset($_POST['checkout']) || empty($_SESSION['order']) || !isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Invalid checkout request";
            header("Location: /views/order/checkout.php");
            exit();
        }
        // Existing logic can stay here if you still need this endpoint
        header("Location: /order");
        exit();
    }

    public function processAndPrint(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /order");
            exit();
        }

        // Prevent duplicate processing on refresh
        if (isset($_SESSION['order_processed']) && $_SESSION['order_processed'] === true) {
            unset($_SESSION['order_processed']);
            unset($_SESSION['order']);
            unset($_SESSION['product']);
            header("Location: /order");
            exit();
        }

        $order = isset($_POST['order']) ? json_decode($_POST['order'], true) : [];
        $subtotal = isset($_POST['subtotal']) ? floatval($_POST['subtotal']) : 0;
        $total = isset($_POST['total']) ? floatval($_POST['total']) : 0;
        $paymentMethod = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : 'cash';

        if (empty($order) || !isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "No order data or user not logged in";
            header("Location: /order");
            exit();
        }

    try {
        $this->db->beginTransaction();

        // Insert order into the database
        $stmt = $this->db->prepare("INSERT INTO orders (user_id, total_amount, payment_status) VALUES (:user_id, :total, 'paid')");
        $stmt->execute([':user_id' => $_SESSION['user_id'], ':total' => $total]);
        $orderId = $this->db->lastInsertId();

        // Insert order items
        foreach ($order as $item) {
            $price = (float)$item['price'];
            $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price, total_price) VALUES (:order_id, :product_id, :quantity, :unit_price, :total_price)");
            $stmt->execute([
                ':order_id' => $orderId,
                ':product_id' => $item['id'],
                ':quantity' => $item['quantity'],
                ':unit_price' => $price,
                ':total_price' => $price * $item['quantity']
            ]);
        }

        // Insert payment
        $stmt = $this->db->prepare("INSERT INTO payments (order_id, payment_method, amount) VALUES (:order_id, :method, :amount)");
        $stmt->execute([':order_id' => $orderId, ':method' => $paymentMethod, ':amount' => $total]);

        $this->db->commit();

            // Generate PDF Receipt
            $pdf = new FPDF('P', 'mm', 'A4');
            $pdf->AddPage();
            $pdf->SetAutoPageBreak(true, 10);

            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, 'SREYTOCH SHOP', 0, 1, 'C');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 6, '271, Street Number 06/B, Sen Sok, Teuktha, KH', 0, 1, 'C');
            $pdf->Cell(0, 6, 'Tel: (+855) 16 872 177', 0, 1, 'C');
            $pdf->Cell(0, 6, 'Email: sreytoch@gmail.com', 0, 1, 'C');

            $pdf->Ln(5);
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->SetTextColor(255, 0, 0);
            $pdf->Cell(0, 10, 'RECEIPT', 0, 1, 'C');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 6, 'Date: ' . date('F d, Y H:i:s'), 0, 1, 'C');
            $pdf->Cell(0, 6, 'Order #: ' . $orderId, 0, 1, 'C');

            $pdf->Ln(5);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(0, 8, 'Order Details', 0, 1);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(80, 8, 'Description', 1, 0, 'L', true);
            $pdf->Cell(30, 8, 'Unit Price', 1, 0, 'R', true);
            $pdf->Cell(20, 8, 'Qty', 1, 0, 'C', true);
            $pdf->Cell(40, 8, 'Subtotal', 1, 1, 'R', true);

            $pdf->SetFont('Arial', '', 10);
            foreach ($order as $item) {
                $price = (float)$item['price'];
                $pdf->Cell(80, 7, $item['name'], 1, 0, 'L');
                $pdf->Cell(30, 7, '$' . number_format($price, 2), 1, 0, 'R');
                $pdf->Cell(20, 7, $item['quantity'], 1, 0, 'C');
                $pdf->Cell(40, 7, '$' . number_format($price * $item['quantity'], 2), 1, 1, 'R');
            }

            $pdf->Ln(5);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(130, 7, 'Subtotal:', 0, 0, 'R');
            $pdf->Cell(40, 7, '$' . number_format($subtotal, 2), 0, 1, 'R');
            $pdf->Cell(130, 7, 'Shipping:', 0, 0, 'R');
            $pdf->Cell(40, 7, 'Free', 0, 1, 'R');
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetFillColor(255, 0, 0);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell(130, 10, 'Total Amount:', 'T', 0, 'R', true);
            $pdf->Cell(40, 10, '$' . number_format($total, 2), 'T', 1, 'R', true);
            $pdf->SetTextColor(0, 0, 0);

            $pdf->Ln(5);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 7, 'Payment Method: ' . ucfirst($paymentMethod), 0, 1);

            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'I', 10);
            $pdf->Cell(0, 6, 'Thank you for shopping with us!', 0, 1, 'C');

            $tempDir = __DIR__ . '/../../temp';
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            $tempFile = $tempDir . '/receipt_' . $orderId . '.pdf';
            $pdf->Output('F', $tempFile);

            $_SESSION['order_processed'] = true;

            unset($_SESSION['order']);
            unset($_SESSION['product']);

            ob_clean();
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="receipt_' . $orderId . '.pdf"');
            header('Cache-Control: no-store, no-cache, must-revalidate');
            readfile($tempFile);
            unlink($tempFile);

            // echo '<script>
            //     window.print();
            //     setTimeout(() => {
            //         window.location.href = "/order";
            //     }, 1000);
            // </script>';
            exit();
        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = "Error processing order: " . $e->getMessage();
            error_log("Order processing error: " . $e->getMessage());
            header("Location: /order");
            exit();
        }
    }
}
    // Helper method to draw a circle (not natively supported by FPDF)
    function Circle($pdf, $x, $y, $r, $style = 'D')
    {
        $pdf->Ellipse($x, $y, $r, $r, 0, 0, 360, $style);
    }
