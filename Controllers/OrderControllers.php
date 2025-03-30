<?php
// OrderController.php
require_once __DIR__ . 'views/../vendor/fpdf/fpdf/src/Fpdf/Fpdf.php';
require_once __DIR__ . '/../Models/ProductModel.php'; // Fixed path
require_once __DIR__ . '/../Models/Order.php'; // Fixed path
require_once __DIR__ . '/../Controllers/EmailController.php'; // Fixed path

class OrderController {
    private $productModel;

    public function __construct() {
        session_start();
        $this->productModel = new ProductModel();
    }

    public function checkout() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['order'])) {
            $order = $_SESSION['order'];

            try {
                $this->productModel->beginTransaction();

                foreach ($order as $item) {
                    $barcode = $item['barcode'];
                    $quantitySold = $item['quantity'];
                    $this->productModel->updateStock($barcode, $quantitySold);
                }

                if (isset($_POST['checkout'])) {
                    $this->generateOrderPDF($order);
                }

                $this->productModel->commit();
                unset($_SESSION['order']);
                header("Location: /order");
                exit();
            } catch (Exception $e) {
                $this->productModel->rollback();
                die("Error processing order: " . $e->getMessage());
            }
        }
    }

    public function placeOrder($userID, $cartItems) {
        $orderId = Order::createOrder($userID, $cartItems);

        if ($orderId) {
            $orderDetails = Order::getOrderDetails($orderId);
            EmailController::sendOrderConfirmation($orderDetails['email'], json_encode($orderDetails));
        }
    }

    private function generateOrderPDF($order) {
        ob_start();
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Order Summary', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(100, 10, 'Customer Name: ' . htmlspecialchars($_POST['customerName'] ?? 'N/A'));
        $pdf->Ln();
        $pdf->Cell(100, 10, 'Shipping Address: ' . htmlspecialchars($_POST['shippingAddress'] ?? 'N/A'));
        $pdf->Ln();
        $pdf->Cell(100, 10, 'Billing Address: ' . htmlspecialchars($_POST['billingAddress'] ?? 'N/A'));
        $pdf->Ln();
        $pdf->Cell(100, 10, 'Contact Details: ' . htmlspecialchars($_POST['contactDetails'] ?? 'N/A'));
        $pdf->Ln();
        $pdf->Cell(100, 10, 'Payment Method: ' . htmlspecialchars($_POST['paymentMethod'] ?? 'N/A'));
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(90, 10, 'Item', 1);
        $pdf->Cell(30, 10, 'Quantity', 1);
        $pdf->Cell(30, 10, 'Price', 1);
        $pdf->Ln();

        $totalPrice = 0;
        $pdf->SetFont('Arial', '', 12);
        foreach ($order as $item) {
            $pdf->Cell(90, 10, $item['name'], 1);
            $pdf->Cell(30, 10, $item['quantity'], 1);
            $pdf->Cell(30, 10, '$' . number_format($item['price'] * $item['quantity'], 2), 1);
            $pdf->Ln();
            $totalPrice += $item['price'] * $item['quantity'];
        }

        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(100, 10, 'Total Price: $' . number_format($totalPrice, 2));

        ob_end_clean();
        $pdf->Output('D', 'order_summary.pdf');
        exit();
    }
}

$controller = new OrderController();
$controller->checkout();
?>