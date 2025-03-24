<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class OrderController {
    private $orderModel;

    public function __construct() {
        require_once '../../Models/OrderModel.php';
        $this->orderModel = new OrderModel();
    }

    // Function to send receipt email after a purchase
    public function sendReceipt($orderId) {
        // Load PHPMailer
        require '../../vendor/autoload.php';

        // Load the email template
        require_once '../../views/email/receipt_template.php';

        // Fetch order details
        $order = $this->orderModel->getOrderById($orderId);
        if (!$order) {
            return false; // Order not found
        }

        $customerEmail = $order['customer_email'];
        $customerName = $order['customer_name'];
        $orderDetails = $order;

        // Generate the email content
        $receiptContent = generateReceiptContent($customerName, $orderDetails);

        // Send the email
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'zenngii168@gmail.com';
            $mail->Password = 'hdzj larg wckf ziyq';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            // Recipients
            $mail->setFrom('zenngii168@gmail.com', 'Cashier Service');
            $mail->addAddress($customerEmail, $customerName);
            $mail->addCC('zenngii168@gmail.com');

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your Purchase Receipt';
            $mail->Body = $receiptContent;
            $mail->AltBody = 'Thank you for your purchase. Please contact us for receipt details.';

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Email sending failed: " . $mail->ErrorInfo);
            return false;
        }
    }

    // Example method to process an order (call this after a successful purchase)
    public function processOrder($orderId) {
        if ($this->sendReceipt($orderId)) {
            return "Receipt sent successfully";
        } else {
            return "Failed to send receipt";
        }
    }
}