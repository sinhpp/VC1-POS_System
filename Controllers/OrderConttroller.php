<?php

require_once 'OrderModel.php';
require_once './vendor/autoload.php'; // PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$orderModel = new OrderModel();
$order = $orderModel->getOrderById(1);

if ($order) {
    echo "Order Id: " . $order['order_id'] . "<br>";
    echo "Customer: " . $order['customer_name'] . "<br>";
    echo "Email: " . $order['customer_email'] . "<br>";
    echo "Total: " . $order['total'] . "<br>";
    echo "Items: <br>";
    foreach ($order['items'] as $item) {
        echo "- " . $item['item_name'] . " (Qty: " . $item['quantity'] . ", Price: " . $item['price'] . ")<br>";
    }

    // Send the receipt via email
    if ($orderModel->sendReceiptByEmail($order)) {
        echo "<br>Receipt sent successfully!";
    } else {
        echo "<br>Failed to send receipt.";
    }
} else {
    echo "Order not found!";
}

class OrderModel {
    private $db;

    public function __construct() {
        require_once './Database/Database.php';
        $this->db = Database::getInstance();
    }

    public function getOrderById($orderId) {
        $query = "SELECT o.order_id, o.customer_email, o.customer_name, o.total 
                  FROM orders o 
                  WHERE o.order_id = :order_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();

        $order = $stmt->fetch();

        if ($order) {
            $order['items'] = $this->getOrderItems($orderId);
        }
        return $order;
    }

    private function getOrderItems($orderId) {
        $query = "SELECT oi.item_name, oi.quantity, oi.price 
                  FROM order_items oi 
                  WHERE oi.order_id = :order_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function sendReceiptByEmail($order) {
        $mail = new PHPMailer(true);

        try {
            // Email server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com';  // Set your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'zenngii168@gmail.com';  // Your email
            $mail->Password = 'hdzj larg wckf ziyq';          // Your password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Email content
            $mail->setFrom('no-reply@pos.com', 'POS System');
            $mail->addAddress($order['customer_email'], $order['customer_name']);
            $mail->Subject = 'Purchase Receipt - Order #' . $order['order_id'];

            // Generate the receipt content
            $receiptBody = "Dear " . $order['customer_name'] . ",\n\n";
            $receiptBody .= "Thank you for your purchase!\n";
            $receiptBody .= "Order ID: " . $order['order_id'] . "\n";
            $receiptBody .= "Total: $" . $order['total'] . "\n";
            $receiptBody .= "Items:\n";

            foreach ($order['items'] as $item) {
                $receiptBody .= "- " . $item['item_name'] . " (Qty: " . $item['quantity'] . ", Price: $" . $item['price'] . ")\n";
            }

            $receiptBody .= "\nBest regards,\nPOS Team";
            $mail->Body = $receiptBody;

            // Attach the PDF receipt (if you have one)
            $pdfPath = './receipts/receipt_' . $order['order_id'] . '.pdf';
            if (file_exists($pdfPath)) {
                $mail->addAttachment($pdfPath);
            }

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Receipt email failed: ' . $mail->ErrorInfo);
            return false;
        }
    }
}
?>
