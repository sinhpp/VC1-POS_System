<?php
require_once "Models/ProductModel.php";

class ProductController extends BaseController {
    private $products;

    public function __construct() {
        $this->products = new ProductModel();
    }

    public function index() {
        $products = $this->products->getProducts(); // Fetch products from the model
        $this->view("products/product", ['products' => $products]); // Pass products to the view
    }
/////////////////////////////
public function detail($id) {
    $product = $this->products->product_detail($id);
    $this->view("products/product_detail", ['product' => $product]);
}

    ////////////////////////////////////////////////////////
    public function create() {
        $this->view("/products/create");  // This should point to 'views/products/create_product.php'
    }

  
    public function store() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    
        // Collecting data from the form
        $name = $_POST['name'];
        $barcode = $_POST['barcode'];
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);
        $category = $_POST['category'];
        $size = $_POST['size'] ?? 'N/A'; // Default to 'N/A' if not provided
        $discount = floatval($_POST['discount']);
        $discount_type = $_POST['discount_type'] ?? 'none'; // Capture discount_type, with a default
        $descriptions = $_POST['descriptions'];
        $gender = $_POST['gender'] ?? 'Unisex'; // Default value if not provided
        $id = isset($_POST['id']) ? intval($_POST['id']) : null; // Get ID if present
    
        // Validate price and discount
        if ($price < 0) {
            $_SESSION['product_error'] = "Price cannot be negative.";
            header("Location: /products/create"); // Redirect back to the form
            exit();
        }
    
        if ($discount < 0) {
            $_SESSION['product_error'] = "Discount cannot be negative.";
            header("Location: /products/create"); // Redirect back to the form
            exit();
        }
    
        // Handle product creation or update
        if ($id) {
            // Update the existing product, ensure to pass all required parameters
            if ($this->products->updateProduct($id, $name, $barcode, $price, $stock, $category, $size, $discount, $discount_type, $descriptions, $gender, $_FILES['image'])) {
                $_SESSION['product_success'] = "Product updated successfully!";
            } else {
                $_SESSION['product_error'] = "Error updating product.";
            }
        } else {
            // Create a new product
            if ($this->products->createProduct($name, $barcode, $price, $stock, $category, $size, $discount, $discount_type, $descriptions, $gender, $_FILES['image'])) {
                $_SESSION['product_success'] = "Product added successfully!";
            } else {
                $_SESSION['product_error'] = "Error: Barcode already exists. Please use a different barcode.";
            }
        }
    
        header("Location: /products"); // Redirect to products list
        exit();
    }
    public function edit($id) {
        $product = $this->products->getProById($id);
        $this->view("products/edit_pro", ['product' => $product]);
    }
    
    public function update($id) {
        session_start(); 
    
        // Collecting data from the form
        $name = $_POST['name'] ?? null;
        $barcode = $_POST['barcode'] ?? null;
        $price = floatval($_POST['price'] ?? 0);
        $stock = intval($_POST['stock'] ?? 0);
        $category = $_POST['category'] ?? null;
        $size = $_POST['size'] ?? null;
        $discount = floatval($_POST['discount'] ?? 0);
        $descriptions = $_POST['descriptions'] ?? null;
        $gender = $_POST['gender'] ?? null;
        $image = $_FILES['image'] ?? null;
    
        // Debugging logs
        error_log("Updating product ID: " . $id);
        error_log("Received category: " . var_export($category, true));
        
        // Validate required fields
        if (!$name) {
            $_SESSION['product_error'] = "Product name is required!";
            header("Location: /products/edit/$id");
            exit();
        }
        
        if (!$barcode) {
            $_SESSION['product_error'] = "Barcode is required!";
            header("Location: /products/edit/$id");
            exit();
        }
    
        if ($price < 0) {
            $_SESSION['product_error'] = "Price cannot be negative!";
            header("Location: /products/edit/$id");
            exit();
        }
    
        if (!isset($category)) {
            $_SESSION['product_error'] = "Category is missing!";
            header("Location: /products/edit/$id");
            exit();
        }
    
        if ($this->products->updateProduct($id, $name, $barcode, $price, $stock, $category, $size, $discount, $discount_type, $descriptions, $gender, $_FILES['image'])) {
            $_SESSION['product_success'] = "Product updated successfully!";
        } else {
            $_SESSION['product_error'] = "Failed to update product. Check your input values.";
        }
    
        header("Location: /products");
        exit();
    }




    public function delete($id) {
        // Call the deleteProduct method from the ProductModel
        if ($this->products->deleteProduct($id)) {
            $_SESSION['product_success'] = "Product deleted successfully!";
        } else {
            $_SESSION['product_error'] = "Error deleting product.";
        }
        header("Location: /products");
        exit();
    }
    public function deleteAllProducts() {
        session_start();
        header('Content-Type: application/json'); // Set the content type to JSON

        // Call the model method to delete all products
        if ($this->products->deleteAllProducts()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete products.']);
        }
        exit();
    }
    public function lowStockAlert() {
        // Default threshold value (can be made configurable later)
        $threshold = 5;
        
        // Get low stock products from the model
        $lowStockProducts = $this->products->getLowStockProducts($threshold);
        
        // Process products to ensure status is correctly set
        foreach ($lowStockProducts as &$product) {
            // If stock is 0 or less, force status to disabled
            if ($product['stock'] <= 0) {
                $product['status'] = 0;
            }
            
            // Ensure status exists
            if (!isset($product['status'])) {
                $product['status'] = 1; // Default to enabled
            }
        }
        
        // Pass the data to the view
        $this->view("products/lowStockAlert", ['products' => $lowStockProducts]);
    }

    // Add this method to check for low stock after purchase
    public function checkLowStock() {
        $threshold = 5; // Default threshold
        $lowStockProducts = $this->products->getLowStockProducts($threshold);
        
        if (!empty($lowStockProducts)) {
            // Add to dashboard notifications
            $this->addLowStockNotification($lowStockProducts);
            
            // Send email alert
            require_once "Models/EmailService.php";
            $emailService = new EmailService();
            $emailService->sendLowStockAlert($lowStockProducts);
        }
    }

    // Method to add notification to the dashboard
    private function addLowStockNotification($products) {
        // Create a notification in the database
        require_once "Models/NotificationModel.php";
        $notificationModel = new NotificationModel();
        
        $message = count($products) . " products have low stock levels";
        $link = "/products/lowStockAlert";
        $notificationModel->addNotification('low_stock', $message, $link);
    }
}