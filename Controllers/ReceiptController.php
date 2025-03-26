<?php
require_once 'Database/Database.php';
require_once 'Models/Order.php';
require_once 'Models/Receipt.php';
require 'vendor/autoload.php'; // For PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ReceiptController {
    public function sendReceipt($order_id) {
        $db = new Database();
        $orderModel = new Order($db);
        $receiptModel = new Receipt();

        // Get order details
        $orderDetails = $orderModel->getOrderById($order_id);
        if (!$orderDetails) {
            die("Order not found.");
        }

        // Generate PDF receipt
        $receiptPath = $receiptModel->generatePDF($orderDetails);

        // Send email
        $this->sendEmail($orderDetails['customer_email'], $receiptPath);
    }

    private function sendEmail($email, $receiptPath) {
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@example.com';
            $mail->Password = 'your-email-password';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Email content
            $mail->setFrom('no-reply@example.com', 'Your Store');
            $mail->addAddress($email);
            $mail->addAttachment($receiptPath);
            $mail->isHTML(true);
            $mail->Subject = 'Your Order Receipt';
            $mail->Body = 'Thank you for your purchase! Attached is your receipt.';

            // Send email
            $mail->send();
            echo "Receipt sent successfully.";
        } catch (Exception $e) {
            echo "Error sending email: {$mail->ErrorInfo}";
        }
    }
}
?>
