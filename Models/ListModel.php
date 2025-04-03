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
    
            $query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                      VALUES (:order_id, :product_id, :quantity, :price)";
            $stmt = $this->db->prepare($query);
    
            foreach ($orderItems as $item) {
                $stmt->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $item['id'],
                    ':quantity' => $item['quantity'],
                    ':price' => $item['price']
                ]);
            }
    
            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception("Error saving order: " . $e->getMessage());
        }
    }
    public function deleteOrder($order_id) {
        $query = "DELETE FROM orders WHERE id = :order_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':order_id' => $order_id]);
    }
}
?>
