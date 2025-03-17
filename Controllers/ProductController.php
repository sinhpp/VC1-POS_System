<?php
require_once "Models/ProductModel.php";

class ProductController extends BaseController {
    private $products;

    public function __construct() {
        $this->products = new ProductModel();
    }

    public function index() {
        $products = $this->products->getProducts();
        $this->view("products/product", ['products' => $products]); // Ensure 'products/product.php' exists
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
    
        $name = $_POST['name'];
        $barcode = $_POST['barcode'];
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);
    
        // Check if product creation is successful
        if ($this->products->createProduct($name, $barcode, $price, $stock)) {
            $_SESSION['product_success'] = "Product added successfully!";
            header("Location: /products"); // Redirect to products list
            exit();
        } else {
            $_SESSION['product_error'] = "Error adding product. Please try again.";
            header("Location: /products/create"); // Redirect back to product creation form
            exit();
        }
    }
    public function delete($id) {
        $this->products->deleteProduct($id);
        header("Location: /products");
    }
}