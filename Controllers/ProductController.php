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

    public function showProduct($id) {
        $product = $this->products->getProductById($id);
        $this->view("products/product_details", ['product' => $product]);
    }

    public function create() {
        $this->view("/products/create");  // This should point to 'views/products/create_product.php'
    }

  
    public function store() {
        session_start(); // Start session to store the message
    
        // Collecting data from the form
        $name = $_POST['name'];
        $barcode = $_POST['barcode'];
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);
        $category = $_POST['category'];
        $id = isset($_POST['id']) ? intval($_POST['id']) : null; // Get ID if present
    
        if ($id) {
            // Update the existing product
            if ($this->products->updateProduct($id, $name, $barcode, $price, $stock, $category, $_FILES['image'])) {
                $_SESSION['product_success'] = "Product updated successfully!";
            } else {
                $_SESSION['product_error'] = "Error updating product.";
            }
        } else {
            // Create a new product
            if ($this->products->createProduct($name, $barcode, $price, $stock, $category, $_FILES['image'])) {
                $_SESSION['product_success'] = "Product added successfully!";
            } else {
                $_SESSION['product_error'] = "Error: Barcode already exists. Please use a different barcode.";
            }
        }
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
    
        if ($id) {
            // Update product logic
            if ($this->products->updateProduct($id, $name, $barcode, $price, $stock, $description, $discount, $discount_type, $_FILES['image'])) {
                $_SESSION['product_success'] = "Product updated successfully!";
            } else {
                $_SESSION['product_error'] = "Error updating product.";
            }
        } else {
            // Create product logic
            if ($this->products->createProduct($name, $barcode, $price, $stock, $description, $discount, $discount_type, $_FILES['image'])) {
                $_SESSION['product_success'] = "Product added successfully!";
            } else {
                $_SESSION['product_error'] = "Error: Barcode already exists.";
            }
        }
    
        header("Location: /products"); // Redirect to products list
        exit();
    }
    public function edit($id) {
        $product = $this->products->getProById($id);
        $this->view("products/create", ['product' => $product]);
    }
    public function update($id) {
        $name = $_POST['name'];
        $barcode = $_POST['barcode'];
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);
        $category = $_POST['category'];
        $image = $_FILES['image'] ?? null; // Handle case when no image is uploaded
    
        $this->products->updateProduct($id, $name, $barcode, $price, $stock, $category, $image);
    
        header("Location: /products");
        exit();
    }
    
    

    // Other methods remain unchanged...

    public function delete($id) {
        // Call the deleteProduct method from the ProductModel
        if ($this->products->deleteProduct($id)) {
            // Optionally set a success message
            $_SESSION['product_success'] = "Product deleted successfully!";
        } else {
            // Optionally set an error message
            $_SESSION['product_error'] = "Error deleting product.";
        }
        header("Location: /products");
        exit();
    }
}