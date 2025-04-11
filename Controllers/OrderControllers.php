<?php
require_once __DIR__ . '../../Controllers/BaseController.php';
require_once __DIR__ . '../../Models/ProductModel.php';
require_once __DIR__ . '../../vendor/autoload.php'; // Assuming you're using Composer for PHPMailer

class OrderController {
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    // Handle checkout and inventory update
    public function checkout() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['order'])) {
            $order = $_SESSION['order'];
            $customerEmail = $_POST['email'] ?? ''; // Get customer email from form

            try {
                // Update stock for each item in the order
                foreach ($order as $item) {
                    $barcode = $item['barcode'];
                    $quantitySold = $item['quantity'];
                    $this->productModel->updateStock($barcode, $quantitySold);
                }

                // Generate PDF
                $pdfContent = $this->generateOrderPDF($order, false); // Get PDF as string

                // Send email with PDF attachment if email is provided
                if (!empty($cashierEmail) && filter_var($cashierEmail, FILTER_VALIDATE_EMAIL)) {
                    $this->sendOrderEmail($cashierEmail, $pdfContent, $order);
                }

                // Output PDF to browser
                $this->outputPDF($pdfContent);

                // Clear session order
                unset($_SESSION['order']);

                // Redirect to success page
                header("Location: /order/success");
                exit();
            } catch (Exception $e) {
                die("Error processing order: " . $e->getMessage());
            }
        }

        // Load the checkout view if not a POST request
        // require_once __DIR__ . '/../views/order/checkout.php';
    }

    // Generate PDF for the order and return as string if $output is false
    private function generateOrderPDF($order, $output = true) {
        ob_start();
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Order Summary', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(100, 10, 'Customer Name: ' . ($_POST['customerName'] ?? 'N/A'));
        $pdf->Ln();
        $pdf->Cell(100, 10, 'Email: ' . ($_POST['email'] ?? 'N/A'));
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

        $pdfContent = $pdf->Output('S'); // Get PDF as string
        
        if ($output) {
            ob_end_clean();
            $pdf->Output('D', 'order_summary.pdf');
            exit();
        }
        
        return $pdfContent;
    }

    // Output PDF to browser
    private function outputPDF($pdfContent) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="order_summary.pdf"');
        header('Content-Length: ' . strlen($pdfContent));
        echo $pdfContent;
    }

    // Send order confirmation email with PDF attachment
    private function sendOrderEmail($toEmail, $pdfContent, $order) {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; // Your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'zenngii168@gmail.com'; // SMTP username
            $mail->Password = 'hdzj larg wckf ziyq'; // SMTP password
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('your_email@example.com', 'ASIA Shop');
            $mail->addAddress($toEmail);
            $mail->addReplyTo('info@example.com', 'Information');

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your Order Confirmation';
            
            // Calculate total
            $totalPrice = 0;
            foreach ($order as $item) {
                $totalPrice += $item['price'] * $item['quantity'];
            }
            
            // Email body
            $mail->Body = '
                <h2>Thank you for your order!</h2>
                <p>Your order has been received and is being processed.</p>
                <h3>Order Summary</h3>
                <p><strong>Customer Name:</strong> ' . ($_POST['customerName'] ?? 'N/A') . '</p>
                <p><strong>Total Amount:</strong> $' . number_format($totalPrice, 2) . '</p>
                <p>Please find your order details attached as a PDF.</p>
                <p>If you have any questions, please contact our support team.</p>
            ';
            
            $mail->AltBody = 'Thank you for your order! Your order has been received and is being processed. Please check the attached PDF for order details.';

            // Attach PDF
            $mail->addStringAttachment($pdfContent, 'order_summary.pdf');

            $mail->send();
        } catch (Exception $e) {
            // Log error but don't stop the process
            error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }
}

// Instantiate and run the controller
$controller = new OrderController();
$controller->checkout();
?>