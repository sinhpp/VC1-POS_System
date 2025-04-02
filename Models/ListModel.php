<?php
require_once __DIR__ . '/../Database/Database.php';

class ListModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getOrders() {
        $query = "
            SELECT 
                orders.id, 
                orders.customer_id,
                customers.name AS customer_name, 
                orders.total_amount, 
                orders.payment_status, 
                orders.created_at
            FROM orders
            LEFT JOIN customers ON orders.customer_id = customers.id
            ORDER BY orders.created_at DESC";
        
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function storeOrder() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerId = filter_input(INPUT_POST, 'customer_id', FILTER_SANITIZE_NUMBER_INT);
            $orderItems = json_decode(filter_input(INPUT_POST, 'order'), true); // Decode the order items
    
            if (empty($orderItems)) {
                $_SESSION['error'] = "No items in the order.";
                header("Location: /order/order_list");
                exit();
            }
    
            try {
                $orderId = $this->orderModel->saveOrder($customerId, $orderItems);
                $_SESSION['success'] = "Order saved successfully! Order ID: $orderId";
            } catch (Exception $e) {
                error_log($e->getMessage());
                $_SESSION['error'] = "Failed to save order: " . $e->getMessage();
            }
        } else {
            $_SESSION['error'] = "Invalid order data.";
        }
    
        header("Location: /order/order_list");
        exit();
    }
    public function deleteOrder($order_id) {
        $query = "DELETE FROM orders WHERE id = :order_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':order_id' => $order_id]);
    }
}
?>
