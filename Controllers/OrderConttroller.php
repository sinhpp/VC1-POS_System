<?php
require_once 'Models/Order.php';
require_once 'barcode/lib/PDF.php';

class OrderController {
    private $orderModel;
    
    public function __construct() {
        $this->orderModel = new Order();
    }
    
    public function create() {
        try {
            // Get POST data
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data) {
                $data = $_POST;
            }
            
            // Validate order data
            if (empty($data['customer']['name']) || empty($data['customer']['email'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Customer information is required']);
                return;
            }
            
            if (empty($data['items']) || !is_array($data['items'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Order items are required']);
                return;
            }
            
            // Create order
            $orderId = $this->orderModel->create($data);
            
            // Send confirmation email
            $this->sendConfirmationEmail($orderId);
            
            // Return success response
            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Order created successfully',
                'order_id' => $orderId
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create order: ' . $e->getMessage()]);
        }
    }
    
    public function show($params) {
        try {
            $orderId = $params['id'];
            $order = $this->orderModel->find($orderId);
            
            if (!$order) {
                http_response_code(404);
                echo json_encode(['error' => 'Order not found']);
                return;
            }
            
            // Load order view
            include 'views/order/show.php';
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to retrieve order: ' . $e->getMessage()]);
        }
    }
    
    public function index() {
        try {
            // Check if user is admin (in a real app, you would use proper authentication)
            if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Unauthorized']);
                return;
            }
            
            $orders = $this->orderModel->getAll();
            
            // Load admin dashboard view
            include 'views/dashboard/orders.php';
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to retrieve orders: ' . $e->getMessage()]);
        }
    }
    
    public function adminShow($params) {
        try {
            // Check if user is admin (in a real app, you would use proper authentication)
            if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Unauthorized']);
                return;
            }
            
            $orderId = $params['id'];
            $order = $this->orderModel->find($orderId);
            
            if (!$order) {
                http_response_code(404);
                echo json_encode(['error' => 'Order not found']);
                return;
            }
            
            // Load admin order detail view
            include 'views/dashboard/order_detail.php';
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to retrieve order: ' . $e->getMessage()]);
        }
    }
    
    public function generateInvoice($params) {
        try {
            $orderId = $params['id'];
            $order = $this->orderModel->find($orderId);
            
            if (!$order) {
                http_response_code(404);
                echo json_encode(['error' => 'Order not found']);
                return;
            }
            
            // Generate PDF invoice
            $pdf = new PDF();
            $pdfContent = $pdf->generateInvoice($order);
            
            // Set headers for PDF download
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="invoice-' . $orderId . '.pdf"');
            header('Content-Length: ' . strlen($pdfContent));
            
            // Output PDF content
            echo $pdfContent;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to generate invoice: ' . $e->getMessage()]);
        }
    }
    
    private function sendConfirmationEmail($orderId) {
        try {
            $order = $this->orderModel->find($orderId);
            
            if (!$order) {
                throw new Exception('Order not found');
            }
            
            // Prepare email content
            ob_start();
            include 'views/emails/order-confirmation.php';
            $emailContent = ob_get_clean();
            
            // Email headers
            $to = $order['customer_email'];
            $subject = 'Order Confirmation #' . $orderId;
            $headers = "From: noreply@example.com\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            
            // Send email
            mail($to, $subject, $emailContent, $headers);
            
            return true;
        } catch (Exception $e) {
            // Log error but don't stop execution
            error_log('Failed to send confirmation email: ' . $e->getMessage());
            return false;
        }
    }
}

