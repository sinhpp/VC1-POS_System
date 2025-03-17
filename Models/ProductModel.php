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
    public function createProduct($name, $barcode, $price, $stock, $category, $image) {
        // Check if barcode exists
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM products WHERE barcode = :barcode");
        $stmt->execute([':barcode' => $barcode]);
        $count = $stmt->fetchColumn();
    
        if ($count > 0) {
            return false; 
        }
    
        // Ensure the uploads directory exists
        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
    
        // Handle image upload
        $imagePath = null;
        if (isset($image) && $image['error'] === UPLOAD_ERR_OK) {
            $imageTmpPath = $image['tmp_name'];
            $imageName = basename($image['name']);
            $imagePath = $uploadDir . $imageName;
    
            // Move file and check for errors
            if (!move_uploaded_file($imageTmpPath, $imagePath)) {
                error_log("Error moving file: " . print_r(error_get_last(), true));
                return false;
            }
        }
    
        // Store relative path in database
        $dbImagePath = 'uploads/' . $imageName;
    
        // Insert new product
        $stmt = $this->db->prepare("INSERT INTO products (name, barcode, price, stock, category, image) VALUES (:name, :barcode, :price, :stock, :category, :image)");
        return $stmt->execute([
            ':name' => $name,
            ':barcode' => $barcode,
            ':price' => $price,
            ':stock' => $stock,
            ':category' => $category,
            ':image' => $dbImagePath
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