<?php
namespace Controllers; // Adjust based on your project structure

use Order;
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
    public function index() {
        $orders = Order::getAllOrders(); // Assuming this method fetches all orders
        include './views/order/admin_order_history.php';
    }
}