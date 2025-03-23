<?php

class Order {
    private $db;
    
    public function __construct() {
        // In a real application, you would use a proper database connection
        $this->db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function create($data) {
        try {
            // Begin transaction
            $this->db->beginTransaction();
            
            // Insert order
            $stmt = $this->db->prepare("
                INSERT INTO orders (customer_name, customer_email, customer_address, order_total, tax, created_at)
                VALUES (:name, :email, :address, :total, :tax, NOW())
            ");
            
            $stmt->execute([
                ':name' => $data['customer']['name'],
                ':email' => $data['customer']['email'],
                ':address' => $data['customer']['address'],
                ':total' => $data['orderTotal'],
                ':tax' => $data['tax']
            ]);
            
            $orderId = $this->db->lastInsertId();
            
            // Insert order items
            $itemStmt = $this->db->prepare("
                INSERT INTO order_items (order_id, product_name, quantity, price)
                VALUES (:order_id, :product_name, :quantity, :price)
            ");
            
            foreach ($data['items'] as $item) {
                $itemStmt->execute([
                    ':order_id' => $orderId,
                    ':product_name' => $item['name'],
                    ':quantity' => $item['quantity'],
                    ':price' => $item['price']
                ]);
            }
            
            // Commit transaction
            $this->db->commit();
            
            return $orderId;
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function find($id) {
        $stmt = $this->db->prepare("
            SELECT * FROM orders WHERE id = :id
        ");
        $stmt->execute([':id' => $id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($order) {
            // Get order items
            $itemStmt = $this->db->prepare("
                SELECT * FROM order_items WHERE order_id = :order_id
            ");
            $itemStmt->execute([':order_id' => $id]);
            $order['items'] = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return $order;
    }
    
    public function getAll() {
        $stmt = $this->db->query("
            SELECT * FROM orders ORDER BY created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

