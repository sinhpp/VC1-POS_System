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

    public function scan() {
        header('Content-Type: application/json');
        if (isset($_POST['scan'])) {
            $barcode = filter_input(INPUT_POST, 'barcode', FILTER_SANITIZE_STRING);
            $rawBarcode = $barcode; // Keep raw for logging
            $barcode = trim($barcode, " \t\n\r\0\x0B");
            $barcode = preg_replace('/[^0-9a-zA-Z]/', '', $barcode);
            error_log("[" . date('Y-m-d H:i:s') . "] Raw barcode received: '$rawBarcode'");
            error_log("[" . date('Y-m-d H:i:s') . "] Cleaned barcode: '$barcode'");

            $product = $this->productModel->getProductByBarcode($barcode);
            error_log("[" . date('Y-m-d H:i:s') . "] Product lookup result: " . json_encode($product));

            if ($product) {
                if ($product['stock'] > 0) {
                    $result = $this->productModel->updateStock($barcode, 1);
                    error_log("[" . date('Y-m-d H:i:s') . "] Stock decrease for '$barcode': " . ($result ? "success" : "failed"));
                    if (!$result) {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to update stock']);
                        exit();
                    }
                    // Check if the product is already in the order
                    $found = false;
                    foreach ($_SESSION['order'] as &$item) {
                        if ($item['barcode'] === $barcode) {
                            $item['quantity'] += 1;
                            $item['stock'] -= 1; // Update stock in the session
                            $found = true;
                            error_log("[" . date('Y-m-d H:i:s') . "] Incremented quantity for '$barcode' to " . $item['quantity']);
                            break;
                        }
                    }
                    if (!$found) {
                        $product['quantity'] = 1;
                        $product['stock'] -= 1;
                        $_SESSION['order'][] = $product;
                        error_log("[" . date('Y-m-d H:i:s') . "] Added new product '$barcode' with quantity 1");
                    }
                    $updatedStock = $this->getUpdatedStock();
                    echo json_encode([
                        'status' => 'success',
                        'order' => $_SESSION['order'],
                        'updatedStock' => $updatedStock
                    ]);
                } else {
                    error_log("[" . date('Y-m-d H:i:s') . "] Out of stock: '$barcode'");
                    echo json_encode(['status' => 'error', 'message' => 'Product out of stock!']);
                }
            } else {
                error_log("[" . date('Y-m-d H:i:s') . "] Product not found for barcode: '$barcode'");
                echo json_encode(['status' => 'error', 'message' => "Product not found for barcode: '$barcode'"]);
            }
        } else {
            error_log("[" . date('Y-m-d H:i:s') . "] Invalid scan request");
            echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        }
        exit();
    }

    public function delete() {
        header('Content-Type: application/json');
        if (isset($_POST['delete'])) {
            $index = filter_input(INPUT_POST, 'index', FILTER_VALIDATE_INT);
            $barcode = filter_input(INPUT_POST, 'barcode', FILTER_SANITIZE_STRING);
            $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
            error_log("[" . date('Y-m-d H:i:s') . "] Delete request: index=$index, barcode='$barcode', quantity=$quantity");

            if ($index !== false && isset($_SESSION['order'][$index]) && $barcode && $quantity > 0) {
                $result = $this->productModel->restoreStock($barcode, $quantity);
                error_log("[" . date('Y-m-d H:i:s') . "] Stock restore for '$barcode': " . ($result ? "success" : "failed"));
                if (!$result) {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to restore stock']);
                    exit();
                }
                unset($_SESSION['order'][$index]);
                $_SESSION['order'] = array_values($_SESSION['order']);
                $updatedStock = $this->getUpdatedStock();
                echo json_encode([
                    'status' => 'success',
                    'order' => $_SESSION['order'],
                    'updatedStock' => $updatedStock
                ]);
            } else {
                error_log("[" . date('Y-m-d H:i:s') . "] Invalid delete data");
                echo json_encode(['status' => 'error', 'message' => 'Invalid item or data']);
            }
        } else {
            error_log("[" . date('Y-m-d H:i:s') . "] Invalid delete request");
            echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        }
        exit();
    }

    private function getUpdatedStock() {
        $updatedStock = [];
        $barcodes = array_unique(array_column($_SESSION['order'], 'barcode'));
        foreach ($barcodes as $barcode) {
            $product = $this->productModel->getProductByBarcode($barcode);
            if ($product) {
                $updatedStock[$barcode] = $product['stock'];
            }
        }
        return $updatedStock;
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
                        if ($item['quantity'] + 1 <= $product['stock']) {
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
 
    public function checkout()
    {
        if (empty($_SESSION['order'])) {
            header("Location: /order");
            exit();
        }

        $subtotal = 0;
        foreach ($_SESSION['order'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $stmt = $this->db->prepare("SELECT tax_rate FROM tax_config WHERE tax_name = 'VAT'");
        $stmt->execute();
        $taxRate = $stmt->fetchColumn() / 100 ?: 0.15; // Default to 15% if not found
        $taxAmount = $subtotal * $taxRate;

        $discountRate = 0.06;
        $discountAmount = $subtotal * $discountRate;
        $finalTotal = $subtotal + $taxAmount - $discountAmount;

        $data = [
            'order' => $_SESSION['order'],
            'subtotal' => $subtotal,
            'taxAmount' => $taxAmount,
            'discountAmount' => $discountAmount,
            'finalTotal' => $finalTotal,
            'deliveryDate' => date('Y-m-d', strtotime('+7 days'))
        ];

        require_once __DIR__ . '/../views/order/checkout.php';
    }

    public function processCheckout()
    {
        if (!isset($_POST['checkout']) || empty($_SESSION['order']) || !isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Invalid checkout request";
            header("Location: /views/order/checkout.php");
            exit();
        }
        header("Location: /order");
        exit();
    }

    public function processAndPrint(): void
{
    // If not a POST request or order is already processed, redirect to /order
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || (isset($_SESSION['order_processed']) && $_SESSION['order_processed'] === true)) {
        unset($_SESSION['order']);
        unset($_SESSION['product']);
        unset($_SESSION['order_processed']);
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

        // Save PDF to a temporary file
        $tempDir = __DIR__ . '/../../temp';
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        $tempFile = $tempDir . '/receipt_' . $orderId . '.pdf';
        $pdf->Output('F', $tempFile);

        // Mark order as processed and clear session data
        $_SESSION['order_processed'] = true;
        unset($_SESSION['order']);
        unset($_SESSION['product']);

        // Send PDF to browser and trigger print with redirect
        ob_clean();
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="receipt_' . $orderId . '.pdf"');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        readfile($tempFile);
        unlink($tempFile);

        // Output HTML with JavaScript to print and redirect
        echo '<html>
            <body>
                <script>
                    window.onload = function() {
                        window.print(); // Open print dialog
                        setTimeout(function() {
                            window.location.href = "/order"; // Redirect to order scan page
                        }, 500); // Delay to allow print dialog
                    };
                </script>
            </body>
        </html>';
        exit();
    } catch (Exception $e) {
        $this->db->rollBack();
        $_SESSION['error'] = "Error processing order: " . $e->getMessage();
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
