<?php
// Models/OrderModel.php
class OrderModel {
    private $db;

    public function __construct() {
        $this->db = new Database(); // Assuming Database class exists
    }

    public function createOrder($data) {
        // Insert order into database and return order ID
        $query = "INSERT INTO orders (user_id, total_amount, order_date) VALUES (?, ?, NOW())";
        $this->db->query($query, [$data['user_id'], $data['total_amount']]);
        return $this->db->lastInsertId();
    }

    public function getOrderDetails($orderId) {
        $query = "SELECT * FROM orders WHERE id = ?";
        return $this->db->fetch($query, [$orderId]);
    }
}