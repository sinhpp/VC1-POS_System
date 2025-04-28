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
                orders.created_at,
                GROUP_CONCAT(products.name) AS product_names
            FROM orders
            LEFT JOIN customers ON orders.customer_id = customers.id
            LEFT JOIN order_items ON orders.id = order_items.order_id
            LEFT JOIN products ON order_items.product_id = products.id
            GROUP BY orders.id
            ORDER BY orders.created_at DESC";
        
        $orders = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
        // Log for debugging
        error_log("Fetched orders: " . print_r($orders, true));
        return $orders;
    }

    public function getOrderDetails($orderId) {
        $query = "
            SELECT 
                orders.id,
                orders.customer_id,
                customers.name AS customer_name,
                orders.total_amount,
                orders.payment_status,
                orders.created_at,
                order_items.product_id,
                products.name AS product_name,
                order_items.quantity,
                order_items.unit_price,
                order_items.total_price
            FROM orders
            LEFT JOIN customers ON orders.customer_id = customers.id
            LEFT JOIN order_items ON orders.id = order_items.order_id
            LEFT JOIN products ON order_items.product_id = products.id
            WHERE orders.id = :order_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([':order_id' => $orderId]);
        $details = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Order details for ID $orderId: " . print_r($details, true));
        return $details;
    }

    public function storeOrder($customer_id, $total_amount, $orderItems) {
        $this->db->beginTransaction();
        try {
            $query = "INSERT INTO orders (customer_id, total_amount, payment_status, created_at)
                      VALUES (:customer_id, :total_amount, 'pending', NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':customer_id' => $customer_id,
                ':total_amount' => $total_amount
            ]);
    
            $orderId = $this->db->lastInsertId();
    
            $query = "INSERT INTO order_items (order_id, product_id, quantity, unit_price, total_price) 
                      VALUES (:order_id, :product_id, :quantity, :unit_price, :total_price)";
            $stmt = $this->db->prepare($query);
    
            foreach ($orderItems as $item) {
                $stmt->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $item['id'],
                    ':quantity' => $item['quantity'],
                    ':unit_price' => $item['price'],
                    ':total_price' => $item['quantity'] * $item['price']
                ]);
            }
    
            $this->db->commit();
            error_log("Order $orderId stored successfully with items: " . print_r($orderItems, true));
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error saving order: " . $e->getMessage());
            throw new Exception("Error saving order: " . $e->getMessage());
        }
    }

    public function deleteOrder($order_id) {
        $query = "DELETE FROM orders WHERE id = :order_id";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([':order_id' => $order_id]);
        error_log("Delete order $order_id: " . ($result ? "Success" : "Failed"));
        return $result;
    }

    public function getConnection() {
        return $this->db;
    }
}