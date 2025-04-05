<?php
require_once "Database/Database.php";

class CategoryModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance(); // Get PDO instance
    }

    public function getCategory() {
        $stmt = $this->db->prepare("SELECT * FROM categories");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
     /////////////////category////////////
    // Method to create a category
    public function createCategory($name) {
        $stmt = $this->db->prepare("INSERT INTO categories (name) VALUES (:name)");
        $stmt->bindParam(':name', $name);
        return $stmt->execute(); // Return true on success, false on failure
    }

    // Method to retrieve categories
    public function getCategories() {
        $stmt = $this->db->prepare("SELECT id, name, created_at FROM categories"); // Ensure created_at is included
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return an array of categories
    }
}