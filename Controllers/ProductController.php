<?php
require_once "Models/ProductModel.php";
require_once "Models/CategoryModel.php"; // Make sure to include CategoryModel

class ProductController extends BaseController {
    private $products;
    private $categories; // Add a property for CategoryModel

    public function __construct() {
        $this->products = new ProductModel();
        $this->categories = new CategoryModel(); // Initialize CategoryModel
    }

    public function getCategories() {
        return $this->categories->getCategories(); // Fetch categories from CategoryModel
    }

    public function index() {
        $products = $this->products->getProducts(); // Fetch products from the model
        $this->view("products/product", ['products' => $products]); // Pass products to the view
    }
    
    public function create() {
        $categories = $this->getCategories(); // Get categories to pass to the view
        $this->view("/products/create", ['categories' => $categories]);  // Pass categories to the view
    }

    public function store() {
        session_start();
        $name = trim($_POST['name'] ?? '');
        $barcode = trim($_POST['barcode'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $stock = intval($_POST['stock'] ?? 0);
        $category = $_POST['category'] ?? '';
        $size = $_POST['size'] ?? 'N/A';
        $discount = floatval($_POST['discount'] ?? 0);
        $discount_type = $_POST['discount_type'] ?? 'percentage'; // Capture from form, default to 'percentage'
        $descriptions = trim($_POST['descriptions'] ?? '');
        $gender = $_POST['gender'] ?? 'Unisex';
        $image = $_FILES['image'] ?? null;
    
        // Validation logic (e.g., price, discount, etc.)
        if ($price < 0 || $discount < 0) {
            $_SESSION['product_error'] = "Price and discount cannot be negative.";
            header("Location: /products/create");
            exit();
        }
    
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;
        if ($id) {
            if ($this->products->updateProduct($id, $name, $barcode, $price, $stock, $category, $size, $discount, $discount_type, $descriptions, $gender, $image)) {
                $_SESSION['product_success'] = "Product updated successfully!";
            } else {
                $_SESSION['product_error'] = "Error updating product.";
            }
        } else {
            if ($this->products->createProduct($name, $barcode, $price, $stock, $category, $size, $discount, $discount_type, $descriptions, $gender, $image)) {
                $_SESSION['product_success'] = "Product added successfully!";
            } else {
                $_SESSION['product_error'] = "Error: Barcode already exists.";
            }
        }
    
        header("Location: /products");
        exit();
    }
    
    public function edit($id) {
        $product = $this->products->getProById($id);
        $this->view("products/edit_pro", ['product' => $product]);
    }
 
    public function update($id) {
        session_start();
        $name = trim($_POST['name'] ?? '');
        $barcode = trim($_POST['barcode'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $stock = intval($_POST['stock'] ?? 0);
        $category = $_POST['category'] ?? '';
        $size = $_POST['size'] ?? 'N/A';
        $discount = floatval($_POST['discount'] ?? 0);
        $discount_type = $_POST['discount_type'] ?? 'percentage'; // Capture from form
        $descriptions = trim($_POST['descriptions'] ?? '');
        $gender = $_POST['gender'] ?? 'Unisex';
        $image = $_FILES['image'] ?? null;
    
        if (empty($category)) {
            $_SESSION['product_error'] = "Category is required.";
            header("Location: /products/edit/$id");
            exit();
        }

        if ($this->products->updateProduct($id, $name, $barcode, $price, $stock, $category, $size, $discount, $discount_type, $descriptions, $gender, $image)) {
            $_SESSION['product_success'] = "Product updated successfully!";
        } else {
            $_SESSION['product_error'] = "Failed to update product. Check your input values.";
        }
    
        header("Location: /products");
        exit();
    }

    public function detail($id) {
        // Fix: Make sure to convert $id to integer and validate it
        $id = intval($id);
        
        if ($id <= 0) {
            // Handle invalid ID
            $_SESSION['product_error'] = "Invalid product ID.";
            header("Location: /products");
            exit();
        }
        
        // Get the product by ID using the same method as edit
        $product = $this->products->getProById($id);
        
        if (!$product) {
            // Handle product not found
            $_SESSION['product_error'] = "Product not found.";
            header("Location: /products");
            exit();
        }
        
        // Pass the product to the view
        $this->view("products/product_detail", ['product' => $product]);
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
public function cashierView()
{
    // Get all products from the database
    $products = $this->productModel->getAllProducts();
    
    // Get all categories for filtering
    $categories = $this->productModel->getCategories();
    
    // Load the cashier view with product data
    $this->view('product_cashier/product', [
        'products' => $products,
        'categories' => $categories
    ]);
}
}
