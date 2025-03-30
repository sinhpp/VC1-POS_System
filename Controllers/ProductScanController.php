<?php
session_start();
require_once 'Database/Database.php';
require_once 'Models/ProductModel.php';
require_once 'Models/OrderModel.php';
require_once __DIR__ . '../../views/vendor/fpdf186/fpdf.php';


class ProductScanController
{
    private $productModel;
    private $db;

    public function __construct()
    {
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
            $product = $this->productModel->getProductByBarcode($barcode);

            if ($product) {
                if ($product['stock'] > 0) {
                    // Reduce stock in the database
                    $this->productModel->updateStock($barcode, 1);

                    // Update the session
                    $_SESSION['product'] = $product;
                } else {
                    $_SESSION['error'] = "Product is out of stock!";
                }
            } else {
                $_SESSION['error'] = "Product not found!";
            }
        }
        header("Location: /order");
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
                $_SESSION['error'] = "Product is out of stock!";
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

    public function printReceipt(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['checkout']) || empty($_SESSION['order'])) {
            $_SESSION['error'] = "Invalid checkout request";
            header("Location: /order");
            exit();
        }

        try {
            $this->db->beginTransaction();

            $subtotal = array_sum(array_map(
                fn($item) => $item['price'] * $item['quantity'],
                $_SESSION['order']
            ));
            $discountRate = 0.06;
            $discount = $subtotal * $discountRate;
            $total_amount = $subtotal - $discount;

            $pdf = new FPDF('P', 'mm', 'A4');
            $pdf->AddPage();
            $pdf->SetAutoPageBreak(true, 10);

            // Header
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, 'SREYTOCH SHOP', 0, 1, 'C');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 6, '271, Street Number 06/B, Sen Sok, Teuktha, KH', 0, 1, 'C');
            $pdf->Cell(0, 6, 'Tel: (+855) 16 872 177', 0, 1, 'C');
            $pdf->Cell(0, 6, 'Email: sreytoch@gmail.com', 0, 1, 'C');

            // Receipt Info
            $pdf->Ln(5);
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->SetTextColor(255, 0, 0);
            $pdf->Cell(0, 10, 'RECEIPT', 0, 1, 'C');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 6, 'Date: ' . date('F d, Y H:i:s'), 0, 1, 'C');

            // Customer Info
            $pdf->Ln(5);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(0, 8, 'Customer Information', 0, 1);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(50, 6, 'Name:', 0, 0);
            $pdf->Cell(0, 6, filter_input(INPUT_POST, 'customerName', FILTER_SANITIZE_STRING), 0, 1);
            $pdf->Cell(50, 6, 'Contact:', 0, 0);
            $pdf->Cell(0, 6, filter_input(INPUT_POST, 'contactDetails', FILTER_SANITIZE_STRING), 0, 1);
            $pdf->Cell(50, 6, 'Shipping Address:', 0, 0);
            $pdf->MultiCell(0, 6, filter_input(INPUT_POST, 'shippingAddress', FILTER_SANITIZE_STRING));

            // Order Details
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
            foreach ($_SESSION['order'] as $item) {
                $pdf->Cell(80, 7, $item['name'], 1, 0, 'L');
                $pdf->Cell(30, 7, '$' . number_format($item['price'], 2), 1, 0, 'R');
                $pdf->Cell(20, 7, $item['quantity'], 1, 0, 'C');
                $pdf->Cell(40, 7, '$' . number_format($item['price'] * $item['quantity'], 2), 1, 1, 'R');
            }

            // Totals
            $pdf->Ln(5);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(130, 7, 'Subtotal:', 0, 0, 'R');
            $pdf->Cell(40, 7, '$' . number_format($subtotal, 2), 0, 1, 'R');
            $pdf->Cell(130, 7, 'Discount (6%):', 0, 0, 'R');
            $pdf->Cell(40, 7, '-$' . number_format($discount, 2), 0, 1, 'R');
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetFillColor(255, 0, 0);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell(130, 10, 'Total Amount:', 'T', 0, 'R', true);
            $pdf->Cell(40, 10, '$' . number_format($total_amount, 2), 'T', 1, 'R', true);
            $pdf->SetTextColor(0, 0, 0);

            // Footer
            $pdf->Ln(5);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 7, 'Payment Method: ' . ucfirst(filter_input(INPUT_POST, 'paymentMethod', FILTER_SANITIZE_STRING)), 0, 1);
            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'I', 10);
            $pdf->Cell(0, 6, 'Thank you for shopping with us!', 0, 1, 'C');

            $this->db->commit();
            $tempFile = __DIR__ . '/../../temp/receipt_' . time() . '.pdf';
            $pdf->Output('F', $tempFile);

            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="receipt.pdf"');
            readfile($tempFile);
            
            echo '<script>window.print();setTimeout(() => window.location.href = "/order", 1000);</script>';
            
            unlink($tempFile);
            unset($_SESSION['order'], $_SESSION['product']);
            exit();
        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = "Checkout error: " . $e->getMessage();
            header("Location: /order");
            exit();
        }
    }
        // ... (existing GET request handling remains unchanged)
    }
    // Helper method to draw a circle (not natively supported by FPDF)
    function Circle($pdf, $x, $y, $r, $style = 'D')
    {
        $pdf->Ellipse($x, $y, $r, $r, 0, 0, 360, $style);
    }

