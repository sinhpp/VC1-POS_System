<?php
namespace Models;

use PDO;

class OrderModel {
    private $pdo;

    public function __construct() {
        $this->pdo = new PDO('mysql:host=localhost;dbname=pos', 'root', '');
    }

    public function saveOrder($customerName, $customerEmail, $totalAmount) {
        $stmt = $this->pdo->prepare("INSERT INTO orders (customer_name, email, total) VALUES (?, ?, ?)");
        $stmt->execute([$customerName, $customerEmail, $totalAmount]);
        return $this->pdo->lastInsertId();
    }

    public function getOrderById($orderId) {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
