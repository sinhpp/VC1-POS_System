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
}