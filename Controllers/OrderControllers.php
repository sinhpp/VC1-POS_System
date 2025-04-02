<?php
class OrderController extends BaseController {
    public function placeOrder($orderData) {
        // Assume $orderData contains order details from the form
        $orderModel = $this->model('OrderModel'); // Load the OrderModel
        $order = $orderModel->createOrder($orderData); // Save order to database
        $customer = $orderModel->getCustomer($order->customerId); // Fetch customer details

        // Generate PDF download link
        $pdfDownloadLink = 'https://www.awesomestore.com/order/downloadInvoice/' . $order->id;

        // Render email template to string
        $emailBody = $this->view->renderToString('order/confirmation', [
            'order' => $order,
            'customer' => $customer,
            'pdfDownloadLink' => $pdfDownloadLink
        ]);

        // Send the email using a mailer library (e.g., PHPMailer)
        $mailer = new \PHPMailer\PHPMailer\PHPMailer();
        $mailer->isSMTP();
        $mailer->Host = 'smtp.example.com'; // From config.php
        $mailer->SMTPAuth = true;
        $mailer->Username = 'zenngii168@gmail.com';
        $mailer->Password = 'hdzj larg wckf ziyq';
        $mailer->setFrom('support@awesomestore.com', 'Awesome Store');
        $mailer->addAddress($customer->email, $customer->name);
        $mailer->Subject = 'Order Confirmation - #' . $order->id;
        $mailer->isHTML(true);
        $mailer->Body = $emailBody;

        if (!$mailer->send()) {
            // Handle error (e.g., log it)
            error_log('Email sending failed: ' . $mailer->ErrorInfo);
        }

        // Redirect or return response
        header('Location: /order/success');
    }

    public function dowlaodInvoice($orderId) {
        $orderModel = $this->model('OrderModel');
        $order = $orderModel->getOrder($orderId);
        $customer = $orderModel->getCustomer($order->customerId);

        $pdfContent = $this->view->renderToString9('receipts/invoice', [
            'order' => $order,
            'customer' => $customer
        ]);

        require_once 'vendor/autoload.php';
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($pdfContent);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('invoice_' . $orderId . 'pdf', ['Attachment' => true]);
    }

    public function viewOrders() {
        $orderModel = $this->model('OrderModel');
        $orders = $orderModel->getAllOrders();
        $this->view->render('dashboard/orders', ['orders' => $orders]);
    }
}
?>