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

class OrderModel {
    private $db;

    public function __construct() {
        require_once './Database/Database.php';
        $this->db = Database::getInstance();
    }

    public function getOrderById($orderId) {
        $query = "SELECT o.order_id, o.customer_email, o.customer_name, o.total 
                  FROM orders o 
                  WHERE o.order_id = :order_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();

        $order = $stmt->fetch();

        if ($order) {
            $order['items'] = $this->getOrderItems($orderId);
        }
        return $order;
    }
    private function getOrderItems($orderId) {
        $query = "SELECT oi.item_name, oi.quantity, oi.price 
                  FROM order_items oi 
                  WHERE oi.order_id = :order_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
?>