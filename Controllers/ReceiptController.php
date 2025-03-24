<?php

namespace Controllers;

use Models\Order;
use Models\Receipt;
use Models\User;

class ReceiptController
{
    /**
     * Process a purchase and generate a receipt
     * 
     * @param array $purchaseData
     * @return array
     */
    public function processPurchase($purchaseData)
    {
        // Create a new order
        $order = new Order();
        $orderId = $order->create([
            'user_id' => $purchaseData['user_id'] ?? null,
            'payment_method' => $purchaseData['payment_method'],
            'total' => $purchaseData['total'],
            'tax' => $purchaseData['tax'],
            'discount' => $purchaseData['discount'] ?? 0,
            'status' => 'completed',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Save order items
        foreach ($purchaseData['items'] as $item) {
            $order->addItem([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity']
            ]);
        }

        // Generate receipt
        $receipt = new Receipt();
        $receiptId = $receipt->create([
            'order_id' => $orderId,
            'transaction_id' => $this->generateTransactionId(),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Get receipt data for email
        $receiptData = $this->getReceiptData($orderId);

        // Send email receipt
        $emailSent = $this->sendReceiptEmail($purchaseData['email'], $receiptData);

        return [
            'success' => true,
            'order_id' => $orderId,
            'receipt_id' => $receiptId,
            'email_sent' => $emailSent
        ];
    }

    /**
     * Generate a unique transaction ID
     * 
     * @return string
     */
    private function generateTransactionId()
    {
        return 'TXN-' . strtoupper(uniqid()) . '-' . date('Ymd');
    }

    /**
     * Get receipt data for a specific order
     * 
     * @param int $orderId
     * @return array
     */
    public function getReceiptData($orderId)
    {
        $order = new Order();
        $orderData = $order->findById($orderId);
        $orderItems = $order->getOrderItems($orderId);
        
        // Get store information
        $storeInfo = [
            'name' => 'Your Store Name',
            'address' => '123 Main Street, City, Country',
            'phone' => '+1 234 567 8900',
            'email' => 'store@example.com',
            'website' => 'www.yourstore.com'
        ];

        // Get customer information if available
        $customerInfo = [];
        if (!empty($orderData['user_id'])) {
            $user = new User();
            $customerInfo = $user->findById($orderData['user_id']);
        }

        return [
            'receipt_id' => 'R-' . $orderId,
            'transaction_id' => $orderData['transaction_id'],
            'date' => $orderData['created_at'],
            'store' => $storeInfo,
            'customer' => $customerInfo,
            'items' => $orderItems,
            'subtotal' => $orderData['total'] - $orderData['tax'],
            'tax' => $orderData['tax'],
            'discount' => $orderData['discount'],
            'total' => $orderData['total'],
            'payment_method' => $orderData['payment_method']
        ];
    }

    /**
     * Send receipt email to customer
     * 
     * @param string $email
     * @param array $receiptData
     * @return bool
     */
    public function sendReceiptEmail($email, $receiptData)
    {
        // Include email helper
        require_once __DIR__ . '/../email/EmailSender.php';
        
        $subject = "Your Receipt from " . $receiptData['store']['name'];
        
        // Generate HTML receipt
        ob_start();
        include __DIR__ . '/../views/receipts/email_template.php';
        $htmlContent = ob_get_clean();
        
        // Generate PDF receipt
        $pdfPath = $this->generatePdfReceipt($receiptData);
        
        // Send email with PDF attachment
        $emailSender = new \EmailSender();
        return $emailSender->send($email, $subject, $htmlContent, [
            'attachments' => [$pdfPath]
        ]);
    }

    /**
     * Generate PDF receipt
     * 
     * @param array $receiptData
     * @return string Path to the generated PDF file
     */
    private function generatePdfReceipt($receiptData)
    {
        // Include PDF generator library (e.g., FPDF, TCPDF, etc.)
        require_once __DIR__ . '/../vendor/autoload.php';
        
        // Create PDF directory if it doesn't exist
        $pdfDir = __DIR__ . '/../receipts/pdf';
        if (!is_dir($pdfDir)) {
            mkdir($pdfDir, 0755, true);
        }
        
        $pdfPath = $pdfDir . '/receipt_' . $receiptData['receipt_id'] . '.pdf';
        
        // Generate PDF using a library like TCPDF
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator('Your Store POS System');
        $pdf->SetAuthor($receiptData['store']['name']);
        $pdf->SetTitle('Receipt #' . $receiptData['receipt_id']);
        $pdf->SetSubject('Purchase Receipt');
        
        // Remove header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Add a page
        $pdf->AddPage();
        
        // Include PDF template
        ob_start();
        include __DIR__ . '/../views/receipts/pdf_template.php';
        $pdfContent = ob_get_clean();
        
        // Write HTML content to PDF
        $pdf->writeHTML($pdfContent, true, false, true, false, '');
        
        // Save PDF to file
        $pdf->Output($pdfPath, 'F');
        
        return $pdfPath;
    }

    /**
     * Download receipt as PDF
     * 
     * @param int $receiptId
     */
    public function downloadReceipt($receiptId)
    {
        $receipt = new Receipt();
        $receiptData = $receipt->findById($receiptId);
        
        if (!$receiptData) {
            echo "Receipt not found";
            return;
        }
        
        $order = new Order();
        $orderData = $order->findById($receiptData['order_id']);
        
        $receiptData = $this->getReceiptData($receiptData['order_id']);
        
        // Generate PDF
        $pdfPath = $this->generatePdfReceipt($receiptData);
        
        // Force download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="receipt_' . $receiptData['receipt_id'] . '.pdf"');
        header('Content-Length: ' . filesize($pdfPath));
        readfile($pdfPath);
        exit;
    }
}

