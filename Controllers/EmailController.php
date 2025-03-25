<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure PHPMailer is installed via Composer

class EmailController {
    public function sendReceipt($orderId) {
        // Include database connection
        require_once 'Database/Database.php'; 
        $db = new Database();

        // Fetch order and user details
        $query = "SELECT u.email, u.name, o.id, o.total_amount, o.order_date 
                  FROM orders o 
                  JOIN users u ON o.user_id = u.id 
                  WHERE o.id = :orderId";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':orderId', $orderId);
        $stmt->execute();
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            die("Order not found!");
        }

        // Generate receipt PDF
        $pdfPath = "receipts/receipt_{$orderId}.pdf";
        $this->generatePDFReceipt($order, $pdfPath);

        // Send Email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; // Use your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@example.com'; 
            $mail->Password = 'your-email-password'; 
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('your-email@example.com', 'Your Store');
            $mail->addAddress($order['email'], $order['name']);
            $mail->addAttachment($pdfPath);

            $mail->isHTML(true);
            $mail->Subject = 'Your Order Receipt';
            $mail->Body    = 'Thank you for your purchase. Your receipt is attached.';

            $mail->send();
            echo 'Receipt sent successfully!';
        } catch (Exception $e) {
            echo "Error: {$mail->ErrorInfo}";
        }
    }

    private function generatePDFReceipt($order, $filePath) {
        require 'vendor/autoload.php'; // Load FPDF

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(40, 10, "Receipt for Order #{$order['id']}");
        $pdf->Output('F', $filePath);
    }
}
