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

    public function getProById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProduct($id, $name, $barcode, $price, $stock, $category, $image) {
        // Prepare the SQL statement
        $stmt = $this->db->prepare("UPDATE products SET name = :name, barcode = :barcode, price = :price, stock = :stock, category = :category WHERE id = :id");
    
        // Execute the update
        $result = $stmt->execute([
            ':name' => $name,
            ':barcode' => $barcode,
            ':price' => $price,
            ':stock' => $stock,
            ':category' => $category,
            ':id' => $id
        ]);
    
        // Handle image upload if a new image is provided
        if (!empty($image['name'])) {
            // Move the uploaded file to the desired directory
            $targetDir = "uploads/"; // Ensure this directory exists
            $targetFile = $targetDir . basename($image['name']);
    
            if (move_uploaded_file($image['tmp_name'], $targetFile)) {
                // Update the image path in the database if the upload is successful
                $stmt = $this->db->prepare("UPDATE products SET image = :image WHERE id = :id");
                $stmt->execute([
                    ':image' => $targetFile,
                    ':id' => $id
                ]);
            }
        }
    
        return $result;
    }

    public function deleteProduct($id) {
        // Prepare the SQL statement to delete the product based on its ID
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Other methods remain unchanged...
}