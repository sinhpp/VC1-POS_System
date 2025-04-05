<?php
require_once "Models/ProductModel.php";

class CategoryController extends BaseController {
    private $products;

    public function __construct() {
        $this->products = new CategoryModel();
    }

        ///////////// create category////////////////
   // Method to create a category
   public function createCategory() {
    $feedbackMessage = ''; // Initialize feedback message

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['categoryName']);

        if (!empty($name)) {
            if ($this->products->createCategory($name)) {
                // Redirect or set a success message
                header("Location: /products"); // Change to your redirect path
                exit;
            } else {
                $feedbackMessage = "Error creating category.";
            }
        } else {
            $feedbackMessage = "Category name cannot be empty.";
        }
    }

    // Include the view or return feedback
    return $feedbackMessage; // You may want to handle this in your view
}

public function getCategories() {
    return $this->products->getCategories(); // Fetch categories from the model
}


public function listCategories() {
    $categories = $this->products->getCategories(); // Fetch categories from the model
    
    // Correct the path to the view file
    $filePath = __DIR__ . '/../views/products/category.php'; // ✅ file name must match!


    if (file_exists($filePath)) {
        include $filePath; // Include the view if it exists
    } else {
        echo "Error: Unable to find the categories view at " . $filePath; // Show the path for debugging
    }
}
}