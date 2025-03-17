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
        error_log("Received category: " . var_export($category, true));
        
    
        $stmt = $this->db->prepare("SELECT image FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $existingProduct = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$existingProduct) {
            error_log("Product not found");
            return false;
        }
    
        $imagePath = $existingProduct['image']; // Keep existing image
        
        if (isset($image) && $image['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $imageName = basename($image['name']);
            $newImagePath = 'uploads/' . $imageName;
            if (move_uploaded_file($image['tmp_name'], $uploadDir . $imageName)) {
                $imagePath = $newImagePath;
            }
        }
    
        $stmt = $this->db->prepare("UPDATE products SET 
            name = :name, 
            barcode = :barcode, 
            price = :price, 
            stock = :stock, 
            category = :category,  
            image = :image 
            WHERE id = :id");
    
        $result = $stmt->execute([
            ':name' => $name,
            ':barcode' => $barcode,
            ':price' => $price,
            ':stock' => $stock,
            ':category' => $category,  
            ':image' => $imagePath,  
            ':id' => $id
        ]);
    
        if ($result) {
            error_log("Category updated successfully in database.");
        } else {
            error_log("Failed to update category: " . implode(", ", $stmt->errorInfo()));
        }
    
        return $result;
    }
    
    public function deleteProducts(array $ids) {
        // Prepare the placeholders for the SQL statement
        $placeholders = rtrim(str_repeat('?,', count($ids)), ',');
    
        // Prepare the SQL statement
        $stmt = $this->db->prepare("DELETE FROM products WHERE id IN ($placeholders)");
    
        // Execute the statement with the product IDs
        $result = $stmt->execute($ids);
    
        if ($result) {
            error_log("Products deleted successfully from database.");
        } else {
            error_log("Failed to delete products: " . implode(", ", $stmt->errorInfo()));
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