<?php
require_once "Database/Database.php";

class UserModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance(); // Get PDO instance
    }

    public function getUsers() {
        $stmt = $this->db->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($name, $email, $password, $role, $image = null) {
        // Check if the email already exists
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);

        if ($stmt->fetchColumn() > 0) {
            return "Email already exists.";
        }

        // Validate role
        $validRoles = ['admin', 'stock_manager', 'user'];
        $role = in_array($role, $validRoles) ? $role : 'user';

        // Insert new user
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role, image) VALUES (:name, :email, :password, :role, :image)");
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $password,
            ':role' => $role,
            ':image' => $image // Store image path
        ]);
    }

    public function deleteUser($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function usercreate($name, $email, $password, $role, $phone, $address, $image) {
        // Check if the email already exists
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
    
        if ($stmt->fetchColumn() > 0) {
            return "Email already exists.";
        }
    
        // Validate role
        $validRoles = ['admin', 'stock_manager', 'user'];
        $role = in_array($role, $validRoles) ? $role : 'user';

        // Insert new user
        $stmt = $this->db->prepare("
            INSERT INTO users (name, email, password, role, phone, address, image) 
            VALUES (:name, :email, :password, :role, :phone, :address, :image)
        ");
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $password,
            ':role' => $role,
            ':phone' => $phone,
            ':address' => $address,
            ':image' => $image // Store image path
        ]);
    }

    public function updateUser($id, $name, $email, $role, $phone, $address, $image = null) {
        try {
            // Validate role
            $validRoles = ['admin', 'stock_manager', 'user'];
            $role = in_array($role, $validRoles) ? $role : 'user';

            $sql = "UPDATE users SET name = :name, email = :email, role = :role, phone = :phone, address = :address";
            $params = [
                ':id' => $id,
                ':name' => $name,
                ':email' => $email,
                ':role' => $role,
                ':phone' => $phone,
                ':address' => $address
            ];
    
            // Only update image if a new one was uploaded
            if ($image !== null) {
                $sql .= ", image = :image";
                $params[':image'] = $image;
            }
    
            $sql .= " WHERE id = :id";
    
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
    
            if ($stmt->rowCount() > 0) {
                return true; // Successfully updated
            } else {
                return false; // No rows updated (maybe no change was made)
            }
        } catch (PDOException $e) {
            die("Error updating user: " . $e->getMessage());
        }
    }

    public function user_detail($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>