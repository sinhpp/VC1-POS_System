<?php

class PDF {
    public function generateInvoice($order) {
        // Require FPDF library (you would need to install this)
        require_once 'barcode/vendor/fpdf/fpdf.php';
        
        // Create new PDF instance
        $pdf = new FPDF();
        $pdf->AddPage();
        
        // Set font
        $pdf->SetFont('Arial', 'B', 16);
        
        // Title
        $pdf->Cell(0, 10, 'INVOICE', 0, 1, 'C');
        $pdf->Cell(0, 10, 'Order #' . $order['id'], 0, 1, 'C');
        $pdf->Ln(10);
        
        // Customer information
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Customer Information:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 6, 'Name: ' . $order['customer_name'], 0, 1);
        $pdf->Cell(0, 6, 'Email: ' . $order['customer_email'], 0, 1);
        $pdf->Cell(0, 6, 'Address: ' . $order['customer_address'], 0, 1);
        $pdf->Ln(10);
        
        // Order items
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Order Items:', 0, 1);
        $pdf->SetFont('Arial', 'B', 10);
        
        // Table header
        $pdf->Cell(90, 7, 'Product', 1);
        $pdf->Cell(30, 7, 'Quantity', 1);
        $pdf->Cell(30, 7, 'Price', 1);
        $pdf->Cell(40, 7, 'Total', 1);
        $pdf->Ln();
        
        // Table content
        $pdf->SetFont('Arial', '', 10);
        $total = 0;
        
        foreach ($order['items'] as $item) {
            $itemTotal = $item['quantity'] * $item['price'];
            $total += $itemTotal;
            
            $pdf->Cell(90, 7, $item['product_name'], 1);
            $pdf->Cell(30, 7, $item['quantity'], 1);
            $pdf->Cell(30, 7, '$' . number_format($item['price'], 2), 1);
            $pdf->Cell(40, 7, '$' . number_format($itemTotal, 2), 1);
            $pdf->Ln();
        }
        
        // Totals
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(150, 7, 'Subtotal:', 1);
        $pdf->Cell(40, 7, '$' . number_format($total, 2), 1);
        $pdf->Ln();
        
        $pdf->Cell(150, 7, 'Tax:', 1);
        $pdf->Cell(40, 7, '$' . number_format($order['tax'], 2), 1);
        $pdf->Ln();
        
        $pdf->Cell(150, 7, 'Total:', 1);
        $pdf->Cell(40, 7, '$' . number_format($total + $order['tax'], 2), 1);
        $pdf->Ln(20);
        
        // Footer
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(0, 10, 'Thank you for your order! If you have any questions, please contact support@example.com', 0, 1, 'C');
        
        // Return PDF as string
        return $pdf->Output('S');
    }
}

