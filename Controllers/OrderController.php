<?php
// After completing an order/purchase
public function completeOrder($orderId) {
    // Existing order completion code...
    
    // Check for low stock after purchase
    $productController = new ProductController();
    $productController->checkLowStock();
    
    // Redirect or return response...
}

/**
 * Get recent orders for dashboard
 */
public function getRecentOrders() {
    // Check if user has access
    AuthMiddleware::checkAccess(['admin']);
    
    try {
        $query = "SELECT o.id, o.customer_id, c.name as customer_name, o.total_amount, o.status, o.created_at 
                 FROM orders o
                 LEFT JOIN customers c ON o.customer_id = c.id
                 ORDER BY o.created_at DESC
                 LIMIT 5";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode($orders);
    } catch (Exception $e) {
        // Log the error
        error_log("Error fetching recent orders: " . $e->getMessage());
        
        // Return error response
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['error' => 'An error occurred while fetching recent orders.']);
    }
}