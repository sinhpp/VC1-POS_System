<?PHP
// Controllers/OrderController.php
class OrderController {
    private $orderModel;
    private $emailService;

    public function __construct() {
        $this->orderModel = new OrderModel();
        $this->emailService = new EmailService();
    }

    public function placeOrder() {
        // Validate and process the order
        $orderData = $_POST; // Example: Get order data from form
        $orderId = $this->orderModel->createOrder($orderData);

        if ($orderId) {
            // Prepare email data
            $orderDetails = $this->orderModel->getOrderDetails($orderId);
            $emailData = [
                'to' => $orderDetails['user_email'],
                'subject' => "Order Confirmation - Order #$orderId",
                'body' => $this->renderEmailTemplate($orderDetails),
            ];

            // Send email
            $this->emailService->sendEmail($emailData);

            // Redirect to order confirmation page
            header("Location: /order/confirmation?order_id=$orderId");
        } else {
            header("Location: /errors/500");
        }
    }

    private function renderEmailTemplate($orderDetails) {
        ob_start();
        include __DIR__ . '/../views/email/order_confirmation.php';
        return ob_get_clean();
    }

    public function downloadInvoice($orderId) {
        // Check if user has access to the order
        $orderDetails = $this->orderModel->getOrderDetails($orderId);
        if (!$orderDetails || $orderDetails['user_id'] !== getCurrentUserId()) {
            header("Location: /errors/403");
            return;
        }

        // Generate PDF
        $pdfContent = $this->generateInvoicePDF($orderDetails);

        // Send PDF as download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="invoice_' . $orderId . '.pdf"');
        echo $pdfContent;
    }

    private function generateInvoicePDF($orderDetails) {
        // Use a PDF library like TCPDF or Dompdf
        require_once 'path/to/tcpdf/tcpdf.php';
        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Image('assets/images/logo.png', 10, 10, 30); // Company logo
        $pdf->Ln(20);
        $pdf->Write(0, "Invoice - Order #$orderDetails[id]");
        $pdf->Ln();
        $pdf->Write(0, "Order Date: $orderDetails[order_date]");
        $pdf->Ln();
        $pdf->Write(0, "Total Amount: $$orderDetails[total_amount]");
        // Add more details as needed
        return $pdf->Output('', 'S'); // Return as string
    }
}