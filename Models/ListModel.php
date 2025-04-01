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
}
?>
