<?php

namespace Models;

use Database\Database;

class Receipt
{
    private $db;
    
    public function __construct()
    {
        $this->db = new Database();
    }
    
    /**
     * Create a new receipt
     * 
     * @param array $receiptData
     * @return int Receipt ID
     */
    public function create($receiptData)
    {
        $sql = "INSERT INTO receipts (order_id, transaction_id, created_at) 
                VALUES (:order_id, :transaction_id, :created_at)";
        
        $params = [
            ':order_id' => $receiptData['order_id'],
            ':transaction_id' => $receiptData['transaction_id'],
            ':created_at' => $receiptData['created_at']
        ];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
    
    /**
     * Find a receipt by ID
     * 
     * @param int $id
     * @return array|false
     */
    public function findById($id)
    {
        $sql = "SELECT * FROM receipts WHERE id = :id";
        $params = [':id' => $id];
        
        return $this->db->single($sql, $params);
    }
    
    /**
     * Find a receipt by order ID
     * 
     * @param int $orderId
     * @return array|false
     */
    public function findByOrderId($orderId)
    {
        $sql = "SELECT * FROM receipts WHERE order_id = :order_id";
        $params = [':order_id' => $orderId];
        
        return $this->db->single($sql, $params);
    }
    
    /**
     * Get all receipts with pagination
     * 
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getAll($limit = 10, $offset = 0)
    {
        $sql = "SELECT r.*, o.total, o.payment_method, u.name as customer_name, u.email as customer_email
                FROM receipts r
                JOIN orders o ON r.order_id = o.id
                LEFT JOIN users u ON o.user_id = u.id
                ORDER BY r.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $params = [
            ':limit' => $limit,
            ':offset' => $offset
        ];
        
        return $this->db->resultSet($sql, $params);
    }
}

