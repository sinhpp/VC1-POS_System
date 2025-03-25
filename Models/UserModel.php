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

    public function createUser($name, $email, $password, $role) {
        // Check if the email already exists
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);

        if ($stmt->fetchColumn() > 0) {
            return "Email already exists.";
        }

        // Insert new user
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)");
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $password,
            ':role' => $role
        ]);
    }
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
    
    
}
?>
