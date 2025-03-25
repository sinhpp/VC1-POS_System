<?php

namespace Models;

use Database\Database;
use PDOException;

class Order
{
    private $db;
    
    public function __construct()
    {
        $this->db = new Database();
    }
    
    /**
     * Create a new order
     * 
     * @param array $orderData
     * @return int Order ID
     * @throws PDOException
     */
    public function create(array $orderData): int
    {
        $sql = "INSERT INTO orders (user_id, payment_method, total, tax, discount, status, created_at) 
                VALUES (:user_id, :payment_method, :total, :tax, :discount, :status, :created_at)";
        
        $params = [
            ':user_id' => $orderData['user_id'],
            ':payment_method' => $orderData['payment_method'],
            ':total' => $orderData['total'],
            ':tax' => $orderData['tax'],
            ':discount' => $orderData['discount'],
            ':status' => $orderData['status'],
            ':created_at' => $orderData['created_at']
        ];
        
        try {
            $this->db->query($sql, $params);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new PDOException("Error creating order: " . $e->getMessage());
        }
    }
    
    /**
     * Add an item to an order
     * 
     * @param array $itemData
     * @return int Order item ID
     * @throws PDOException
     */
    public function addItem(array $itemData): int
    {
        $sql = "INSERT INTO order_items (order_id, product_id, quantity, price, subtotal) 
                VALUES (:order_id, :product_id, :quantity, :price, :subtotal)";
        
        $params = [
            ':order_id' => $itemData['order_id'],
            ':product_id' => $itemData['product_id'],
            ':quantity' => $itemData['quantity'],
            ':price' => $itemData['price'],
            ':subtotal' => $itemData['subtotal']
        ];
        
        try {
            $this->db->query($sql, $params);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new PDOException("Error adding item to order: " . $e->getMessage());
        }
    }
    
    /**
     * Find an order by ID
     * 
     * @param int $id
     * @return array|false
     */
    public function findById(int $id)
    {
        $sql = "SELECT * FROM orders WHERE id = :id";
        $params = [':id' => $id];
        
        return $this->db->single($sql, $params);
    }
    
    /**
     * Get all items for an order
     * 
     * @param int $orderId
     * @return array
     */
    public function getOrderItems(int $orderId): array
    {
        $sql = "SELECT oi.*, p.name, p.sku 
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = :order_id";
        
        $params = [':order_id' => $orderId];
        
        return $this->db->resultSet($sql, $params);
    }
    
    /**
     * Get all orders with pagination
     * 
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getAll(int $limit = 10, int $offset = 0): array
    {
        $sql = "SELECT o.*, u.name as customer_name, u.email as customer_email
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $params = [
            ':limit' => $limit,
            ':offset' => $offset
        ];
        
        return $this->db->resultSet($sql, $params);
    }
    
    /**
     * Count total orders
     * 
     * @return int
     */
    public function countAll(): int
    {
        $sql = "SELECT COUNT(*) as total FROM orders";
        $result = $this->db->single($sql);
        
        return $result['total'];
    }
}