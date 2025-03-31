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
    }

    // ... (other methods remain the same until 'add')

    public function add() {
        if (isset($_POST['add'])) {
            $barcode = filter_input(INPUT_POST, 'barcode', FILTER_SANITIZE_STRING);
            $product = $this->productModel->getProductByBarcode($barcode);

            if ($product && $product['stock'] > 0) {
                $found = false;

                foreach ($_SESSION['order'] as &$item) {
                    if ($item['barcode'] === $barcode) {
                        if ($item['quantity'] + 1 <= $product['stock']) {
                            $item['quantity'] += 1; // Fixed: increment instead of decrement
                            $this->productModel->updateStock($barcode, 1);
                        } else {
                            $_SESSION['error'] = "Cannot add more items; stock limit reached!";
                        }
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $product['quantity'] = 1;
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

    // ... (other methods remain the same)

    public function printReceipt(): void {
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

            // ... (PDF generation code remains the same)

            $this->db->commit();
            $tempFile = __DIR__ . '/../temp/receipt_' . time() . '.pdf'; // Fixed path
            $pdf->Output('F', $tempFile);

            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="receipt.pdf"');
            readfile($tempFile);
            
            echo '<script>window.print();setTimeout(() => window.location.href = "/order", 1000);</script>';
            
            unlink($tempFile);
            unset($_SESSION['order'], $_SESSION['product']);
            exit();
        } catch (Exception $e) {
            // $this->db->rollBack();
            $_SESSION['error'] = "Checkout error: " . $e->getMessage();
            header("Location: /order");
            exit();
        }
    }

    private function Circle($x, $y, $r, $style = 'D') {
        $pdf = new FPDF(); // Ensure $pdf is accessible or passed as a parameter
        $pdf->Ellipse($x, $y, $r, $r, 0, 0, 360, $style);
    }
    private function Ellipse($x, $y, $rx, $ry, $angle, $astart, $aend, $style = 'D') {
        $pdf = new FPDF();
        $pdf->Ellipse($x, $y, $rx, $ry, $angle, $astart, $aend, $style);
    }
} // Added missing closing brace
?>