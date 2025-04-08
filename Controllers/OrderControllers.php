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
        $mailer->Host = SMTP_HOST; // Use constant or config variable
        $mailer->SMTPAuth = true;
        $mailer->Username = zenngii168@gmail.com; // Use constant or config variable
        $mailer->Password = hdzj larg wckf ziyq; // Use constant or config variable
        $mailer->setFrom('support@awesomestore.com', 'Awesome Store');
        $mailer->addAddress($customer->email, $customer->name);
        $mailer->Subject = 'Order Confirmation - #' . $order->id;
        $mailer->isHTML(true);
        $mailer->Body = $emailBody;

        if (!$mailer->send()) {
            // Handle error (e.g., log it)
            error_log('Email sending failed: ' . $mailer->ErrorInfo);
        }

        // Send email to cashier
        $cashierEmail = 'cashier@awesomestore.com'; // Replace with actual cashier email or fetch dynamically
        $mailer->clearAddresses(); // Clear previous recipient
        $mailer->addAddress($cashierEmail, 'Cashier');
        $mailer->Subject = 'New Order Placed - #' . $order->id;
        $mailer->Body = 'A new order has been placed. Order ID: #' . $order->id;

        if (!$mailer->send()) {
            // Handle error (e.g., log it)
            error_log('Cashier email sending failed: ' . $mailer->ErrorInfo);
        }

        // Redirect or return response
        header('Location: /order/success');
    }

    public function downloadInvoice($orderId) { // Fixed method name
        $orderModel = $this->model('OrderModel');
        $order = $orderModel->getOrder($orderId);
        $customer = $orderModel->getCustomer($order->customerId);

        $pdfContent = $this->view->renderToString('receipts/invoice', [ // Fixed method name
            'order' => $order,
            'customer' => $customer
        ]);

        require_once 'vendor/autoload.php';
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($pdfContent);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('invoice_' . $orderId . '.pdf', ['Attachment' => true]); // Fixed file extension
    }

    public function viewOrders() {
        $orderModel = $this->model('OrderModel');
        $orders = $orderModel->getAllOrders();
        $this->view->render('dashboard/orders', ['orders' => $orders]);
    }
}
?>