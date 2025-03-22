<?php
require_once __DIR__ . '/../Database/Database.php';

class OrderModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function saveOrder($customerName, $shippingAddress, $billingAddress, $contactDetails, $paymentMethod, $totalPrice, $orderItems) {
        // Start a transaction
        $this->db->beginTransaction();

        try {
            // Insert into orders table
            $query = "INSERT INTO orders (customer_name, shipping_address, billing_address, contact_details, payment_method, total_price, status)
                      VALUES (:customer_name, :shipping_address, :billing_address, :contact_details, :payment_method, :total_price, 'Completed')";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':customer_name' => $customerName,
                ':shipping_address' => $shippingAddress,
                ':billing_address' => $billingAddress,
                ':contact_details' => $contactDetails,
                ':payment_method' => $paymentMethod,
                ':total_price' => $totalPrice
            ]);

            // Get the last inserted order ID
            $orderId = $this->db->lastInsertId();

            // Insert order items
            $query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
            $stmt = $this->db->prepare($query);
            foreach ($orderItems as $item) {
                $stmt->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $item['id'],
                    ':quantity' => $item['quantity'],
                    ':price' => $item['price']
                ]);
            }

            // Commit the transaction
            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            // Rollback on error
            $this->db->rollBack();
            throw new Exception("Error saving order: " . $e->getMessage());
        }
    }
}