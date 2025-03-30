<?php
require_once __DIR__ . '../../views/vendor/fpdf186/fpdf.php';
require_once __DIR__ . '../../Models/ProductModel.php';

class OrderController {
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    // Handle checkout and inventory update
    public function checkout() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['order'])) {
            $order = $_SESSION['order'];

            try {
                // Start a transaction for inventory updates
                // $this->productModel->beginTransaction();

                // Update stock for each item in the order
                foreach ($order as $item) {
                    $barcode = $item['barcode'];
                    $quantitySold = $item['quantity'];
                    $this->productModel->updateStock($barcode, $quantitySold);
                }

                // Generate PDF if checkout form is submitted
                if (isset($_POST['checkout'])) {
                    $this->generateOrderPDF($order);
                }

                // Commit transaction
                // $this->productModel->commit();

                // Clear session order
                unset($_SESSION['order']);

                // Redirect to success page
                header("Location: /order");
                exit();
            } catch (Exception $e) {
                // // Rollback transaction on error
                // $this->productModel->rollback();
                die("Error processing order: " . $e->getMessage());
            }
        }

        // Load the checkout view if not a POST request
        // require_once __DIR__ . '/../views/order/checkout.php';
    }

    // Generate PDF for the order
    private function generateOrderPDF($order) {
        ob_start();
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Order Summary', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(100, 10, 'Customer Name: ' . ($_POST['customerName'] ?? 'N/A'));
        $pdf->Ln();
        $pdf->Cell(100, 10, 'Shipping Address: ' . ($_POST['shippingAddress'] ?? 'N/A'));
        $pdf->Ln();
        $pdf->Cell(100, 10, 'Billing Address: ' . ($_POST['billingAddress'] ?? 'N/A'));
        $pdf->Ln();
        $pdf->Cell(100, 10, 'Contact Details: ' . ($_POST['contactDetails'] ?? 'N/A'));
        $pdf->Ln();
        $pdf->Cell(100, 10, 'Payment Method: ' . ($_POST['paymentMethod'] ?? 'N/A'));
        $pdf->Ln(10);

        // Order Details Table
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

// Instantiate and run the controller
$controller = new OrderController();
$controller->checkout();
?>