<?php
require_once "Models/ProductModel.php";
require_once "Models/CategoryModel.php"; 

class CategoryController extends BaseController {
    private $categories;

    public function __construct() {
        $this->categories = new CategoryModel(); // Initialize CategoryModel
    }

    // Method to get categories
    public function getCategories() {
        return $this->categories->getCategories(); // Fetch categories from CategoryModel
    }

    // Method to create a category
    public function createCategory() {
        $feedbackMessage = ''; // Initialize feedback message

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['categoryName']);

            if (!empty($name)) {
                if ($this->categories->createCategory($name)) {
                    header("Location: /products"); // Change to your redirect path
                    exit;
                } else {
                    $feedbackMessage = "Error creating category.";
                }
            } else {
                $feedbackMessage = "Category name cannot be empty.";
            }
        }

        return $feedbackMessage; // You may want to handle this in your view
    }

    public function listCategories() {
        $categories = $this->getCategories(); // Fetch categories

        $filePath = __DIR__ . '/../views/products/category.php'; // Correct path to the view file

        if (file_exists($filePath)) {
            include $filePath; // Include the view if it exists
        } else {
            echo "Error: Unable to find the categories view at " . $filePath; // Debugging message
        }
    }
}