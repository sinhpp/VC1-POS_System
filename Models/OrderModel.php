<?php

require_once 'OrderModel.php';

$orderModel = new OrderModel();
$order = $orderModel->getOrderById(1);

if ($order) {
    echo "Order Id: " . $order['order_id'] . "<br>";
    echo "Customer: " . $order['customer_name'] . "<br>";
    echo "Email: " . $order['customer_email'] . "<br>";
    echo "Total: " . $order['total'] . "<br>";
    echo "Items: <br>";
    foreach ($order['items'] as $item) {
        echo "- " . $item['item_name'] . " (Qty: " . $item['quantity'] . ", Price: " . $item['price'] . ")<br>";
    }
} else {
    echo "Order not found!";
}
// File: OrderModel.php
class OrderModel {
    private $db;

    public function __construct() {
        // Include the Database class
        require_once '../../Database/Database.php';
        // Get the PDO instance from the Database singleton
        $this->db = Database::getInstance();
    }

    // Fetch order details by order ID
    public function getOrderById($orderId) {
        // Prepare the query
        $query = "SELECT o.order_id, o.customer_email, o.customer_name, o.total 
                  FROM orders o 
                  WHERE o.order_id = :order_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        
        // Fetch a single row as an associative array
        $order = $stmt->fetch();

        if ($order) {
            // Fetch order items
            $order['items'] = $this->getOrderItems($orderId);
        }
        return $order;
    }

    // Fetch items for a specific order
    private function getOrderItems($orderId) {
        $query = "SELECT oi.item_name, oi.quantity, oi.price 
                  FROM order_items oi 
                  WHERE oi.order_id = :order_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        
        // Fetch all rows as an associative array
        return $stmt->fetchAll();
    }
}
?>