<?php
require_once "Models/ProductModel.php";

use Picqer\Barcode\BarcodeGeneratorPNG;

class ProductController extends BaseController {
    private $products;
    private $users;

    public function __construct() {
        // Initialize models
        $this->products = new ProductModel();
        $this->users = new UserModel();
    }

    // Display list of products (Existing functionality)
    public function index() {
        $products = $this->products->getProducts();  // Assuming 'getProducts' fetches all products
        $this->view("products/index", ['products' => $products]);
    }
    public function showProduct($id) {
        $product = $this->products->getProductById($id);  // Assuming 'getProductById' fetches product by ID
        $this->view("products/product_details", ['product' => $product]);
    }

    // Create product and generate barcode (New functionality for barcode generation)
    public function create_pro() {
        $barcode = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['generate']) && !empty($_POST['barcode'])) {
                // Initialize the barcode generator
                $generator = new BarcodeGeneratorPNG();
                
                // Generate the barcode for the input value
                $barcode = base64_encode($generator->getBarcode($_POST['barcode'], $generator::TYPE_CODE_128));

                // Optionally, save the barcode to a file
                $barcodeFile = 'path/to/save/barcode.png'; // Set the correct file path
                file_put_contents($barcodeFile, base64_decode($barcode));
            }

            // Handle product creation logic (after barcode generation)
            if (isset($_POST['name'], $_POST['price'], $_POST['stock'], $_POST['category'])) {
                // Assuming you have a method for adding products
                $productData = [
                    'name' => $_POST['name'],
                    'price' => $_POST['price'],
                    'stock' => $_POST['stock'],
                    'category' => $_POST['category'],
                    'barcode' => $_POST['barcode'],  // Store barcode generated
                    'image' => $_POST['image'] ?? null, // Assuming image is uploaded
                ];
                $this->products->addProduct($productData);  // Assuming you have 'addProduct' in your ProductModel

                // Redirect to product list or success page
                header("Location: /products/create_pro"); // Change the location to where you want to redirect
                exit();
            }
        } else {
            // Load the create form (create.php)
            $this->view("products/create");
        }
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
        $this->view("products/edit_pro", ['product' => $product]);
    }
    public function update($id) {
        session_start(); 
    
        $name = $_POST['name'];
        $barcode = $_POST['barcode'];
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);
        $category = $_POST['category'] ?? null;
        $image = $_FILES['image'] ?? null;
    
        // Debugging
        error_log("Updating product ID: " . $id);
        error_log("Received category: " . var_export($category, true));
    
        if (!$category) {
            $_SESSION['product_error'] = "Category is missing!";
            header("Location: /products/edit/$id");
            exit();
        }
    
        if ($this->products->updateProduct($id, $name, $barcode, $price, $stock, $category, $image)) {
            $_SESSION['product_success'] = "Product updated successfully!";
        } else {
            $_SESSION['product_error'] = "Failed to update product.";
        }
    
        header("Location: /products");
        exit();
    }
    
    // Other methods remain unchanged...

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

?>


