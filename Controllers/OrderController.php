<?php
namespace Controllers; // Adjust based on your project structure

use ProductController;

class OrderController {
    public function completeOrder($orderId) {
        // Existing order completion code...
        
        require_once __DIR__ . '/ProductController.php';
        
        $productController = new ProductController();
        $productController->checkLowStock();
        
        // Redirect or return response...
        return true;
    }
}