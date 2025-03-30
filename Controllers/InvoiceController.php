<?php
require('vendor/autoload.php');
require_once 'Models/Order.php';

use setasign\Fpdi\Fpdi;

class InvoiceController {
     public static function generateInvoice($orderId) {
          $order = Order::getOrderDetails($orderId);
          if (!$order) {
               die("Order not found.");
          }

          $pdf = new FPDF();
          $pdf->AddPage();
          $pdf->SetFont('Arial', 'B', 16);
          $pdf->Cell(40, 10, "Invoice for Order #OrderId");
          $pdf->Ln();
          $pdf->SetFont('Arial', '', 12);
          $pdf->Cell(40, 10, "Total Amount: $" . $order['total_amount']);
          $pdf->Output('D', "invoice_$orderId.pdf");
     }
}