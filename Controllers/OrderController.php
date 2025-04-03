<?php
// After completing an order/purchase
public function completeOrder($orderId) {
    // Existing order completion code...
    
    // Check for low stock after purchase
    $productController = new ProductController();
    $productController->checkLowStock();
    
    // Redirect or return response...
}


?>