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
    
        $name = $_POST['name'];
        $barcode = $_POST['barcode']; // Directly retrieve the barcode input
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);
        $category = $_POST['category'];
        $image = $_FILES['image'];  // This is incorrect

    
        // Check if product creation is successful
        if ($this->products->createProduct($name, $barcode, $price, $stock, $category, $image)) {
            $_SESSION['product_success'] = "Product added successfully!";
            header("Location: /products"); // Redirect to products list
            exit();
        } else {
            $_SESSION['product_error'] = "Error: Barcode already exists. Please use a different barcode."; // Handle duplicate barcode
            header("Location: /products/create"); // Redirect back to product creation form
            exit();
        }
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