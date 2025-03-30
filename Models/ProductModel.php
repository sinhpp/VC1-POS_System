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
    public function createProduct($name, $barcode, $price, $stock, $category, $size, $discount,$discount_type, $descriptions, $gender, $image) {
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
        $imageName = null; // Initialize $imageName

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
        $dbImagePath = 'uploads/' . ($imageName ?? ''); // Ensure $imageName is defined

        // Log the size for debugging
        error_log("Creating product with size: " . print_r($size, true)); // Debug line for size

           // Insert new product
    $stmt = $this->db->prepare("INSERT INTO products (name, barcode, price, stock, category, size, discount, discount_type, descriptions, gender, image) VALUES (:name, :barcode, :price, :stock, :category, :size, :discount, :discount_type, :descriptions, :gender, :image)");
        return $stmt->execute([
            ':name' => $name,
            ':barcode' => $barcode,
            ':price' => $price,
            ':stock' => $stock,
            ':category' => $category,
            ':size' => $size ?? 'N/A', // Use 'N/A' only if $size is null
            ':discount' => $discount,
            ':discount_type' => $discount_type, // Ensure this is included
            ':descriptions' => $descriptions,
            ':gender' => $gender,
            ':image' => $dbImagePath
        ]);
    }
    public function updateProduct($id, $name, $barcode, $price, $stock, $category, $size, $discount, $discount_type, $descriptions, $gender, $image) {
        // First, get the current product data to retain previous image if no new image uploaded
        $currentProduct = $this->getProById($id);
        $imagePath = $currentProduct['image']; // Assume current image is maintained
    
        // Check if a new image is uploaded
        if (isset($image) && $image['error'] === UPLOAD_ERR_OK) {
            // Handle the image upload
            $uploadDir = __DIR__ . '/../uploads/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
    
            $imageTmpPath = $image['tmp_name'];
            $imageName = basename($image['name']);
            $imagePath = $uploadDir . $imageName;
    
            // Move the file and check for errors
            if (!move_uploaded_file($imageTmpPath, $imagePath)) {
                error_log("Error moving file: " . print_r(error_get_last(), true));
                return false; // Handle the error accordingly
            }
        } else {
            // If no new image provided, retain the old image path
            $imagePath = $currentProduct['image'];
        }
    
        // Prepare the SQL update statement
        $stmt = $this->db->prepare("UPDATE products SET name = :name, barcode = :barcode, price = :price, stock = :stock, 
            category = :category, size = :size, discount = :discount, discount_type = :discount_type, 
            descriptions = :descriptions, gender = :gender, image = :image 
            WHERE id = :id");
    
        // Bind parameters for the query
        $bindingParams = [
            ':id' => $id,
            ':name' => $name,
            ':barcode' => $barcode,
            ':price' => $price,
            ':stock' => $stock,
            ':category' => $category,
            ':size' => $size ?? 'N/A', // Handle empty size
            ':discount' => $discount,
            ':discount_type' => $discount_type,
            ':descriptions' => $descriptions,
            ':gender' => $gender,
            ':image' => 'uploads/' . basename($imagePath), // Use the new or existing image path
        ];
    
        // Execute the query
        return $stmt->execute($bindingParams);
    }
    public function getProById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    //////////////////////////////
    public function product_detail($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    ///////////////////////////////
    // public function updateProduct($id, $name, $barcode, $price, $stock, $category, $image) {
    //     error_log("Received category: " . var_export($category, true));
        
    
    //     $stmt = $this->db->prepare("SELECT image FROM products WHERE id = :id");
    //     $stmt->execute([':id' => $id]);
    //     $existingProduct = $stmt->fetch(PDO::FETCH_ASSOC);
    
    //     if (!$existingProduct) {
    //         error_log("Product not found");
    //         return false;
    //     }
    
    //     $imagePath = $existingProduct['image']; // Keep existing image
        
    //     if (isset($image) && $image['error'] === UPLOAD_ERR_OK) {
    //         $uploadDir = __DIR__ . '/../uploads/';
    //         if (!is_dir($uploadDir)) {
    //             mkdir($uploadDir, 0777, true);
    //         }
    //         $imageName = basename($image['name']);
    //         $newImagePath = 'uploads/' . $imageName;
    //         if (move_uploaded_file($image['tmp_name'], $uploadDir . $imageName)) {
    //             $imagePath = $newImagePath;
    //         }
    //     }
    
    //     $stmt = $this->db->prepare("UPDATE products SET 
    //         name = :name, 
    //         barcode = :barcode, 
    //         price = :price, 
    //         stock = :stock, 
    //         category = :category,  
    //         image = :image 
    //         WHERE id = :id");
    
    //     $result = $stmt->execute([
    //         ':name' => $name,
    //         ':barcode' => $barcode,
    //         ':price' => $price,
    //         ':stock' => $stock,
    //         ':category' => $category,  
    //         ':image' => $imagePath,  
    //         ':id' => $id
    //     ]);
    
    //     if ($result) {
    //         error_log("Category updated successfully in database.");
    //     } else {
    //         error_log("Failed to update category: " . implode(", ", $stmt->errorInfo()));
    //     }
    
    //     return $result;
    // }
    
    public function deleteProduct($id) {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Bind the ID parameter
        return $stmt->execute(); // Execute the statement
    }
    
    
    public function deleteAllProducts() {
        $stmt = $this->db->prepare("DELETE FROM products");
        return $stmt->execute();
    }

    // Other methods remain unchanged...
}