<?php
namespace Controllers;

use Models\OrderModel;
use PHPMailer\PHPMailer\PHPMailer;

class OrderController {
    public function placeOrder($customerName, $customerEmail, $totalAmount) {
        // Save the order to the database
        $orderModel = new OrderModel();
        $orderId = $orderModel->saveOrder($customerName, $customerEmail, $totalAmount);

        // Send confirmation email
        $this->sendConfirmationEmail($customerEmail, $orderId);

        // Generate PDF receipt
        $this->generatePDFReceipt($orderId);

        return $orderId;
    }

    private function sendConfirmationEmail($email, $orderId) {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com';
        $mail->Password = 'your_password';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('your_email@example.com', 'Shop Name');
        $mail->addAddress($email);
        $mail->Subject = 'Order Confirmation';
        $mail->Body = "Thank you for your order! Your order number is #$orderId.";
        $mail->send();
    }

    private function generatePDFReceipt($orderId) {
        $orderModel = new OrderModel();
        $order = $orderModel->getOrderById($orderId);

        require('vendor/autoload.php');
        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(40, 10, "Invoice for Order #$orderId");
        $pdf->Ln();
        $pdf->Cell(40, 10, "Customer: {$order['customer_name']}");
        $pdf->Ln();
        $pdf->Cell(40, 10, "Total: {$order['total']}");
        $pdf->Output("views/receipts/invoice_$orderId.pdf", 'F');
    }
}
