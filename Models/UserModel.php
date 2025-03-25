<?php

namespace Models;

use Database\Database;

class User
{
    private $db;
    
    public function __construct()
    {
        $this->db = new Database();
    }
    
    /**
     * Find a user by ID
     * 
     * @param int $id
     * @return array|false
     */
    public function findById($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $params = [':id' => $id];
        
        return $this->db->single($sql, $params);
    }
    
    /**
     * Find a user by email
     * 
     * @param string $email
     * @return array|false
     */
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $params = [':email' => $email];
        
        return $this->db->single($sql, $params);
    }
    
    /**
     * Create or update a user
     * 
     * @param array $userData
     * @return int User ID
     */
    public function createOrUpdate($userData)
    {
        // Check if user exists
        $existingUser = $this->findByEmail($userData['email']);
        
        if ($existingUser) {
            // Update user
            $sql = "UPDATE users SET 
                    name = :name,
                    phone = :phone,
                    updated_at = :updated_at
                    WHERE id = :id";
            
            $params = [
                ':id' => $existingUser['id'],
                ':name' => $userData['name'] ?? $existingUser['name'],
                ':phone' => $userData['phone'] ?? $existingUser['phone'],
                ':updated_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->query($sql, $params);
            return $existingUser['id'];
        } else {
            // Create new user
            $sql = "INSERT INTO users (name, email, phone, created_at) 
                    VALUES (:name, :email, :phone, :created_at)";
            
            $params = [
                ':name' => $userData['name'] ?? '',
                ':email' => $userData['email'],
                ':phone' => $userData['phone'] ?? '',
                ':created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->query($sql, $params);
            return $this->db->lastInsertId();
        }
    }
}

