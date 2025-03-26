<?php
require_once '/../Database/Database.php';
require 'vendor/autoload.php'; // FPDF library

$db = Database::getInstance(); 
$stmt = $db->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll();
print_r($users);

use FPDF\FPDF;

class Receipt {
    public function generatePDF($orderDetails) {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(40, 10, "Order Receipt");
        $pdf->Ln();

        // Add order details
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, "Order ID: " . $orderDetails['id']);
        $pdf->Ln();
        $pdf->Cell(40, 10, "Customer: " . $orderDetails['customer_name']);
        $pdf->Ln();
        $pdf->Cell(40, 10, "Total: $" . $orderDetails['total']);
        $pdf->Ln();

        // Save PDF
        $receiptPath = "uploads/receipts/order_" . $orderDetails['id'] . ".pdf";
        $pdf->Output('F', $receiptPath);

        return $receiptPath;
    }
}
?>
