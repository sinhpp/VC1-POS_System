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
    public function createProduct($name, $barcode, $price, $stock, $category, $size, $discount, $discount_type, $descriptions, $gender, $image) {
        // Check if barcode exists
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM products WHERE barcode = :barcode");
        $stmt->execute([':barcode' => $barcode]);
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            return false; // Barcode exists, return failure
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
    
            // Move the uploaded file and check for errors
            if (!move_uploaded_file($imageTmpPath, $imagePath)) {
                error_log("Error moving file: " . print_r(error_get_last(), true));
                return false; // Return false on error
            }
        } else {
            // Handle cases where no image was uploaded
            error_log("Image upload error: " . print_r($image, true));
            return false;
        }
        
        // Store relative path in database
        $dbImagePath = 'uploads/' . $imageName;
    
        // Insert new product into the database
        $stmt = $this->db->prepare("INSERT INTO products (name, barcode, price, stock, category, size, discount, discount_type, descriptions, gender, image) VALUES (:name, :barcode, :price, :stock, :category, :size, :discount, :discount_type, :descriptions, :gender, :image)");
        return $stmt->execute([
            ':name' => $name,
            ':barcode' => $barcode,
            ':price' => $price,
            ':stock' => $stock,
            ':category' => $category,
            ':size' => $size ?? 'N/A', // Default to 'N/A' if size not provided
            ':discount' => $discount,
            ':discount_type' => $discount_type, // Use the passed discount type
            ':descriptions' => $descriptions,
            ':gender' => $gender,
            ':image' => $dbImagePath
        ]);
    }
    
    public function updateProduct($id, $name, $barcode, $price, $stock, $category, $size, $discount, $discount_type, $descriptions, $gender, $image) {
        // Handle image upload
        $imagePath = null;
        if (isset($image) && $image['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/';
            
            // Create the uploads directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
    
            $imageTmpPath = $image['tmp_name'];
            
            // Generate a unique image name to avoid clashes
            $imageName = uniqid() . '-' . basename($image['name']);
            $imagePath = $uploadDir . $imageName;
    
            if (!move_uploaded_file($imageTmpPath, $imagePath)) {
                error_log("Error moving file: " . print_r(error_get_last(), true));
                return false;
            }
    
            $dbImagePath = 'uploads/' . $imageName;
        } else {
            // If no new image is uploaded, use the existing image path
            $existingProduct = $this->getProById($id);
            $dbImagePath = $existingProduct['image'];
        }
    
        // Prepare SQL statement to update product in database
        $stmt = $this->db->prepare("UPDATE products SET 
            name = :name, 
            barcode = :barcode, 
            price = :price, 
            stock = :stock, 
            category = :category,  
            size = :size,
            discount = :discount,
            discount_type = :discount_type,
            descriptions = :descriptions,
            gender = :gender,
            image = :image 
            WHERE id = :id");
    
        // Execute the update statement
        $result = $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':barcode' => $barcode,
            ':price' => $price,
            ':stock' => $stock,
            ':category' => $category, 
            ':size' => $size,
            ':discount' => $discount,
            ':discount_type' => $discount_type,
            ':descriptions' => $descriptions, 
            ':gender' => $gender,
            ':image' => $dbImagePath
        ]);
    
        // Logging based on the result of execution
        if ($result) {
            error_log("Product updated successfully in database.");
        } else {
            error_log("Failed to update product: " . implode(", ", $stmt->errorInfo()));
        }
    
        return $result;
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
    public function getProductByBarcode($barcode) {
        $query = "SELECT * FROM products WHERE barcode = :barcode LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':barcode' => $barcode]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Returns product as associative array or false if not found
    }

    // Method to update stock
    public function updateStock($barcode, $quantity) {
        $query = "UPDATE products SET stock = stock - :quantity WHERE barcode = :barcode AND stock >= :quantity";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':barcode' => $barcode,
            ':quantity' => $quantity
        ]);
        return $stmt->rowCount() > 0; // Returns true if stock was updated
    }
    // Other methods remain unchanged...


    public function getLowStockProducts($threshold = 10)
{
    $stmt = $this->db->prepare("SELECT * FROM products WHERE stock <= :threshold ORDER BY stock ASC");
    $stmt->execute([':threshold' => $threshold]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}