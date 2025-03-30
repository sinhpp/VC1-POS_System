<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Load Composer dependencies

class EmailController {
    public static function sendOrderConfirmation($toEmail, $orderDetails) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; // Change to your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@example.com'; 
            $mail->Password = 'your-email-password';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('your-email@example.com', 'Your Store');
            $mail->addAddress($toEmail);
            $mail->Subject = 'Order Confirmation';
            $mail->Body    = "Thank you for your order!\n\nOrder Details:\n$orderDetails";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
