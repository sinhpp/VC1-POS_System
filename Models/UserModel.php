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
<<<<<<< HEAD
=======
    public function deleteUser($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    public function usercreate($name, $email, $password, $role) {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role) 
                                    VALUES (:name, :email, :password, :role)");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $password,
            ':role' => $role
        ]);
        return $stmt->rowCount(); // Returns the number of affected rows
    }
    public function updateUser($id, $name, $email, $role) {
        $stmt = $this->db->prepare("UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id");
        $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':email' => $email,
            ':role' => $role
        ]);
        return $stmt->rowCount(); // Returns affected rows
    }
    
    ///////////////////////////////
    public function view_user($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //////////////////////////////////////////
    
>>>>>>> main
}

