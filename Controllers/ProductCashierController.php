<?php
require_once "Models/ProductModel.php";
require_once "Controllers/BaseController.php";

class ProductCashierController extends BaseController {
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    public function index() {
        // Get all products
        $products = $this->productModel->getProducts();
        
        // Get all categories
        $categories = $this->getCategoryList();
        
        // Load the view with data
        $this->view('product_cashier/product', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
    
    // Helper method to get categories
    private function getCategoryList() {
        // Create a database connection
        $db = Database::getInstance();
        
        // Get distinct categories from products table
        $stmt = $db->prepare("SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != ''");
        $stmt->execute();
        
        $categories = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = ['name' => $row['category']];
        }
        
        return $categories;
    }
}