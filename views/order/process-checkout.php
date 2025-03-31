<?php
session_start();
require_once __DIR__ . '../../vendor/fpdf186/fpdf.php'; // Path to FPDF
require_once __DIR__ . '../../layout.php'; // Your layout file
require_once __DIR__. '/../../Database/Database.php';

// Retrieve form data
$customerName = $_POST['customerName'] ?? 'N/A';
$shippingAddress = $_POST['shippingAddress'] ?? 'N/A';
$billingAddress = $_POST['billingAddress'] ?? 'N/A';
$contactDetails = $_POST['contactDetails'] ?? 'N/A';
$paymentMethod = $_POST['paymentMethod'] ?? 'N/A';
$totalPrice = isset($_POST['totalPrice']) && is_numeric($_POST['totalPrice']) ? floatval($_POST['totalPrice']) : 0;
$order = isset($_POST['order']) ? json_decode($_POST['order'], true) : [];
$discountRate = 0.06;
$discountAmount = $totalPrice * $discountRate;
$finalTotal = $totalPrice - $discountAmount;

// Initialize FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Header
$pdf->Cell(0, 10, 'Order Receipt', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Generated on ' . date('Y-m-d H:i:s'), 0, 1, 'C');
$pdf->Ln(10);

// Customer Information
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Customer Information', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Name: ' . $customerName, 0, 1);
$pdf->Cell(0, 10, 'Shipping Address: ' . $shippingAddress, 0, 1);
$pdf->Cell(0, 10, 'Billing Address: ' . $billingAddress, 0, 1);
$pdf->Cell(0, 10, 'Contact Details: ' . $contactDetails, 0, 1);
$pdf->Cell(0, 10, 'Payment Method: ' . $paymentMethod, 0, 1);
$pdf->Ln(10);

// Order Summary
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Order Summary', 0, 1);
$pdf->SetFont('Arial', '', 12);

if (empty($order)) {
    $pdf->Cell(0, 10, 'No items in your order.', 0, 1);
} else {
    // Table header
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(80, 10, 'Product', 1);
    $pdf->Cell(30, 10, 'Quantity', 1);
    $pdf->Cell(40, 10, 'Price', 1);
    $pdf->Ln();

    // Table rows
    $pdf->SetFont('Arial', '', 12);
    foreach ($order as $product) {
        $pdf->Cell(80, 10, $product['name'], 1);
        $pdf->Cell(30, 10, $product['quantity'], 1);
        $pdf->Cell(40, 10, '$' . number_format($product['price'] * $product['quantity'], 2), 1);
        $pdf->Ln();
    }

    // Totals
    $pdf->Ln(5);
    $pdf->Cell(110, 10, 'Product Total:', 0);
    $pdf->Cell(40, 10, '$' . number_format($totalPrice, 2), 0, 1);
    $pdf->Cell(110, 10, 'Discount (6%):', 0);
    $pdf->Cell(40, 10, '($' . number_format($discountAmount, 2) . ')', 0, 1);
    $pdf->Cell(110, 10, 'Delivery Fee:', 0);
    $pdf->Cell(40, 10, 'Free', 0, 1);
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(110, 10, 'Final Total:', 0);
    $pdf->Cell(40, 10, '$' . number_format($finalTotal, 2), 0, 1);
}

// Output the PDF as a download
$pdf->Output('D', 'order_receipt_' . date('YmdHis') . '.pdf'); // 'D' forces download
exit;
?>