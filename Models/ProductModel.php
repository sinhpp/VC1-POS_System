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

    public function getProductById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function createProduct($name, $barcode, $price, $stock, $category) {
        // Insert new product
        $stmt = $this->db->prepare("INSERT INTO products (name, barcode, price, stock, category) VALUES (:name, :barcode, :price, :stock, :category)");
        return $stmt->execute([
            ':name' => $name,
            ':barcode' => $barcode,
            ':price' => $price,
            ':stock' => $stock,
            ':category' => $category
        ]);
    }
    public function deleteProduct($id) {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

}
