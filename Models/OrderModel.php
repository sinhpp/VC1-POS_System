<?php
class OrderModel {
    private $db;

    public function __construct() {
        $this->db = new PDO("mysql:host=localhost;dbname=pos", "root", "");
    }

    public function createOrder($orderData) {
        $stmt = $this->db->prepare("INSERT INTO orders (customer_name, customer_email, total) VALUES (?, ?, ?)");
        $stmt->execute([$orderData['customer_name'], $orderData['customer_email'], $orderData['total']]);
        return $this->db->lastInsertId();
    }
    public function getOrderById($orderId) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}
?>
