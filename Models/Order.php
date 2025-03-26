<?php
class Order {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }

    public function getOrderById($order_id) {
        $query = "SELECT * FROM orders WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$order_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
