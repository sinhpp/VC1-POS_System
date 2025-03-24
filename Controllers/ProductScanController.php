<?php
session_start();
require_once 'Database/Database.php';
require_once 'Models/ProductModel.php';
require_once 'Models/OrderModel.php';
require_once __DIR__. '../../views/vendor/fpdf186/fpdf.php';

class ProductScanController {
    private $productModel;
    private $orderModel;
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
        if (!isset($_SESSION['order'])) {
            $_SESSION['order'] = [];
        }
        $this->productModel = new ProductModel();
        // $this->orderModel = new OrderModel();
    }

    public function index() {
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
    

    public function add() {
        if (isset($_POST['add'])) {
            $barcode = filter_input(INPUT_POST, 'barcode', FILTER_SANITIZE_STRING);
            $product = $this->productModel->getProductByBarcode($barcode);
    
            if ($product && $product['stock'] > 0) {
                $found = false;
    
                // Check if the product already exists in the order
                foreach ($_SESSION['order'] as &$item) {
                    if ($item['barcode'] === $barcode) {
                        if ($item['quantity'] + 1 <= $product['stock']) {
                            $item['quantity'] += 1;
    
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
    

    public function delete() {
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

    public function checkout() {
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

    public function processCheckout() {
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

    public function printReceipt() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
            if (empty($_SESSION['order']) || !isset($_SESSION['user_id'])) {
                $_SESSION['error'] = "Invalid checkout request";
                header("Location: /views/order/checkout.php");
                exit();
            }
    
            try {
                $this->db->beginTransaction();
                // ... (existing customer, order, payment, sale logic remains unchanged)
    
                // Generate PDF with styled design
                ob_start();
                $pdf = new FPDF();
                $pdf->AddPage();
    
                // Header: Red Circle and Business Name
                $pdf->SetFillColor(255, 0, 0); // Red
                // $pdf->Circle(20, 20, 10, 'F'); // Red circle at (20, 20) with radius 10
                $pdf->SetFont('Arial', 'B', 16);
                $pdf->SetTextColor(0, 0, 0); // Black
                $pdf->SetXY(35, 15);
                $pdf->Cell(50, 10, 'Business Name', 0, 0);
    
                // Header: "INVOICE" and Date
                $pdf->SetFont('Arial', 'B', 16);
                $pdf->SetTextColor(255, 0, 0); // Red
                $pdf->SetXY(120, 15);
                $pdf->Cell(40, 10, 'INVOICE', 0, 0, 'R');
                $pdf->SetFont('Arial', '', 12);
                $pdf->SetTextColor(0, 0, 0); // Black
                $pdf->SetXY(120, 25);
                $pdf->Cell(40, 10, date('F d, Y'), 0, 1, 'R');
    
                // Business and Customer Info
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->SetXY(10, 40);
                $pdf->Cell(90, 8, 'Office Address', 0, 0);
                $pdf->Cell(90, 8, 'To:', 0, 1);
                $pdf->SetFont('Arial', '', 10);
                $pdf->SetXY(10, 48);
                $pdf->Cell(90, 6, 'Main street, Number 06/B,', 0, 0);
                $pdf->Cell(90, 6, $_POST['customerName'], 0, 1);
                $pdf->SetXY(10, 54);
                $pdf->Cell(90, 6, 'South Mountain, YK', 0, 0);
                $pdf->Cell(90, 6, $_POST['shippingAddress'], 0, 1);
                $pdf->SetXY(10, 60);
                $pdf->Cell(90, 6, '(+62) 123 456 7890', 0, 0);
                $pdf->Cell(90, 6, $_POST['billingAddress'], 0, 1);
                $pdf->SetXY(10, 66);
                $pdf->Cell(90, 6, '', 0, 0);
                $pdf->Cell(90, 6, 'Number 06/B', 0, 1);
                $pdf->Ln(10);
    
                // Items Table Header
                $pdf->SetFillColor(255, 0, 0); // Red
                $pdf->SetTextColor(255, 255, 255); // White
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(90, 10, 'Items Description', 1, 0, 'C', 1);
                $pdf->Cell(30, 10, 'Unit Price', 1, 0, 'C', 1);
                $pdf->Cell(30, 10, 'Qty', 1, 0, 'C', 1);
                $pdf->Cell(30, 10, 'Total', 1, 1, 'C', 1);
    
                // Items Table Rows
                $pdf->SetFont('Arial', '', 10);
                $pdf->SetTextColor(0, 0, 0); // Black
                $pdf->SetFillColor(230, 230, 230); // Light gray
                foreach ($_SESSION['order'] as $item) {
                    $pdf->Cell(90, 10, $item['name'], 1, 0, 'L', 1);
                    $pdf->Cell(30, 10, '$' . number_format($item['price'], 2), 1, 0, 'R', 1);
                    $pdf->Cell(30, 10, $item['quantity'], 1, 0, 'C', 1);
                    $pdf->Cell(30, 10, '$' . number_format($item['price'] * $item['quantity'], 2), 1, 1, 'R', 1);
                }
    
                // Totals Section
                $pdf->Ln(5);
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(150, 10, 'Subtotal:', 0, 0, 'R');
                $pdf->Cell(30, 10, '$' . number_format($subtotal, 2), 0, 1, 'R');
                $pdf->Cell(150, 10, 'Tax VAT 15%:', 0, 0, 'R');
                $tax = $subtotal * 0.15;
                $pdf->Cell(30, 10, '$' . number_format($tax, 2), 0, 1, 'R');
                $pdf->Cell(150, 10, 'Discount 6%:', 0, 0, 'R');
                $pdf->Cell(30, 10, '-$' . number_format($discount, 2), 0, 1, 'R');
                $pdf->SetFillColor(255, 0, 0); // Red
                $pdf->SetTextColor(255, 255, 255); // White
                $pdf->Cell(150, 10, 'Total Due:', 0, 0, 'R', 1);
                $pdf->Cell(30, 10, '$' . number_format($total_amount + $tax, 2), 1, 1, 'R', 1);
    
                // Note Section
                $pdf->SetTextColor(0, 0, 0); // Black
                $pdf->SetFont('Arial', '', 10);
                $pdf->Ln(5);
                $pdf->Cell(0, 10, 'Note:', 0, 1);
                $pdf->MultiCell(0, 5, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
    
                // Thank You Message
                $pdf->Ln(5);
                $pdf->SetFont('Arial', 'I', 12);
                $pdf->Cell(0, 10, 'Thank you for your Business', 0, 1, 'C');
    
                // Footer: Three Columns
                $pdf->SetY(-40);
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(60, 8, 'Questions?', 0, 0);
                $pdf->Cell(60, 8, 'Payment Info:', 0, 0);
                $pdf->Cell(60, 8, 'Terms & Conditions/Note:', 0, 1);
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(60, 5, 'Email us: company@gmail.com', 0, 0);
                $pdf->Cell(60, 5, 'Account Name: 1234-567-890', 0, 0);
                $pdf->Cell(60, 5, 'Lorem ipsum dolor sit amet, consectetur', 0, 1);
                $pdf->Cell(60, 5, 'Call us: +123 456 7890', 0, 0);
                $pdf->Cell(60, 5, 'Bank Detail: Bank Phnom', 0, 0);
                $pdf->Cell(60, 5, 'adipiscing elit, sed do eiusmod', 0, 1);
    
                $this->db->commit();
    
                $tempFile = __DIR__ . '/../../temp/order_receipt_' . $order_id . '.pdf';
                ob_end_clean();
                $pdf->Output('F', $tempFile);
    
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="order_receipt_' . $order_id . '.pdf"');
                readfile($tempFile);
                echo '<script>window.print(); window.location.href="/order";</script>';
    
                unset($_SESSION['order']);
                unset($_SESSION['product']);
                exit();
            } catch (Exception $e) {
                $this->db->rollBack();
                $_SESSION['error'] = "Error processing checkout: " . $e->getMessage();
                header("Location: /views/order/checkout.php");
                exit();
            }
        }
        // ... (existing GET request handling remains unchanged)
    }
    
    // Helper method to draw a circle (not natively supported by FPDF)
    function Circle($pdf, $x, $y, $r, $style = 'D') {
        $pdf->Ellipse($x, $y, $r, $r, 0, 0, 360, $style);
    }
}
?>