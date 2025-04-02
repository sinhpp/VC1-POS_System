<?php
require_once __DIR__ . '/../Database/Database.php';

class OrderModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function saveOrder($customerName, $contactDetails, $totalPrice, $orderItems) {
        $this->db->beginTransaction();
        try {
            // Insert order details into the orders table
            $query = "INSERT INTO orders (customer_name, contact_details, total_price, status)
                      VALUES (:customer_name, :contact_details, :total_price, 'Pending')";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':customer_name' => $customerName,
                ':contact_details' => $contactDetails,
                ':total_price' => $totalPrice
            ]);

            $orderId = $this->db->lastInsertId();

            // Prepare to insert order items
            $query = "INSERT INTO order_items (order_id, product_id, quantity, unit_price, total_price) VALUES (:order_id, :product_id, :quantity, :unit_price, :total_price)";
            $stmt = $this->db->prepare($query);
            $stockQuery = "UPDATE products SET stock = stock - :quantity WHERE id = :product_id AND stock >= :quantity";
            $stockStmt = $this->db->prepare($stockQuery);

            // Loop through each order item
            foreach ($orderItems as $item) {
                // Insert the order item
                $stmt->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $item['id'],
                    ':quantity' => $item['quantity'],
                    ':unit_price' => $item['price'],
                    ':total_price' => $item['price'] * $item['quantity']
                ]);

                // Update product stock
                $stockStmt->execute([
                    ':quantity' => $item['quantity'],
                    ':product_id' => $item['id']
                ]);
                
                if ($stockStmt->rowCount() == 0) {
                    throw new Exception("Insufficient stock for product ID: " . $item['id']);
                }
            }

            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception("Error saving order: " . $e->getMessage());
        }
    }
}