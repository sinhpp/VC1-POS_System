<?php
require_once "Database/Database.php";

class ProductModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance(); // Get PDO instance
    }

    public function getProducts() {
        $stmt = $this->db->prepare("SELECT * FROM products");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createProduct($name, $barcode, $price, $stock, $category) {
        // Check if the barcode already exists
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM products WHERE barcode = :barcode");
        $stmt->execute([':barcode' => $barcode]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            // Return false if the barcode already exists
            return false; 
        }

        // Insert new product
        $stmt = $this->db->prepare("INSERT INTO products (name, barcode, price, stock, category) VALUES (:name, :barcode, :price, :stock, :category)");
        return $stmt->execute([
            ':name' => $name,
            ':barcode' => $barcode, // Ensure barcode is used here
            ':price' => $price,
            ':stock' => $stock,
            ':category' => $category
        ]);
    }
    public function deleteProduct($id) {
        // Prepare the SQL statement to delete the product based on its ID
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Other methods remain unchanged...
}