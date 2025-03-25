<?php

namespace Controllers;

use Models\Order;
use Models\Receipt;
use Models\User;

class ReceiptController
{
    public function processPurchase($purchaseData)
    {
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

        foreach ($purchaseData['items'] as $item) {
            $order->addItem([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity']
            ]);
        }

        $receipt = new Receipt();
        $receiptId = $receipt->create([
            'order_id' => $orderId,
            'transaction_id' => $this->generateTransactionId(),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $receiptData = $this->getReceiptData($orderId);
        $emailSent = $this->sendReceiptEmail($purchaseData['email'], $receiptData);

        return [
            'success' => true,
            'order_id' => $orderId,
            'receipt_id' => $receiptId,
            'email_sent' => $emailSent
        ];
    }

    private function generateTransactionId()
    {
        return 'TXN-' . strtoupper(uniqid()) . '-' . date('Ymd');
    }

    public function getReceiptData($orderId)
    {
        $order = new Order();
        $orderData = $order->findById($orderId);
        $orderItems = $order->getOrderItems($orderId);

        $storeInfo = [
            'name' => 'Your Store Name',
            'address' => '123 Main Street, City, Country',
            'phone' => '+1 234 567 8900',
            'email' => 'store@example.com',
            'website' => 'www.yourstore.com'
        ];

        $customerInfo = [];
        if (!empty($orderData['user_id'])) {
            $user = new User();
            $customerInfo = $user->findById($orderData['user_id']);
        }

        return [
            'receipt_id' => 'R-' . $orderId,
            'transaction_id' => $orderData['transaction_id'] ?? $this->generateTransactionId(),
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

    public function sendReceiptEmail($email, $receiptData)
    {
        require_once __DIR__ . '/../email/EmailSender.php';

        $subject = "Your Receipt from " . $receiptData['store']['name'];
        ob_start();
        include __DIR__ . '/../views/receipts/email_template.php';
        $htmlContent = ob_get_clean();

        $pdfPath = $this->generatePdfReceipt($receiptData);
        $emailSender = new \EmailSender();
        return $emailSender->send($email, $subject, $htmlContent, ['attachments' => [$pdfPath]]);
    }

    private function generatePdfReceipt($receiptData)
    {
        require_once __DIR__ . '/../vendor/autoload.php';

        $pdfDir = __DIR__ . '/../receipts/pdf';
        if (!is_dir($pdfDir)) {
            mkdir($pdfDir, 0755, true);
        }

        $pdfPath = $pdfDir . '/receipt_' . $receiptData['receipt_id'] . '.pdf';
        $pdf = new \TCPDF();
        $pdf->SetCreator('Your Store POS System');
        $pdf->SetAuthor($receiptData['store']['name']);
        $pdf->SetTitle('Receipt #' . $receiptData['receipt_id']);
        $pdf->SetSubject('Purchase Receipt');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        ob_start();
        include __DIR__ . '/../views/receipts/pdf_template.php';
        $pdfContent = ob_get_clean();
        $pdf->writeHTML($pdfContent);
        $pdf->Output($pdfPath, 'F');

        return $pdfPath;
    }

    public function downloadReceipt($receiptId)
    {
        $receipt = new Receipt();
        $receiptData = $receipt->findById($receiptId);

        if (!$receiptData) {
            http_response_code(404);
            echo "Receipt not found";
            return;
        }

        $receiptData = $this->getReceiptData($receiptData['order_id']);
        $pdfPath = $this->generatePdfReceipt($receiptData);

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="receipt_' . $receiptData['receipt_id'] . '.pdf"');
        header('Content-Length: ' . filesize($pdfPath));
        readfile($pdfPath);
        exit;
    }
}
