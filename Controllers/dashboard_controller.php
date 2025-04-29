<?php
public function getOrderDataForChart() {
    // Ensure the request is AJAX
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        return;
    }
    
    try {
        // Get orders for the last 7 days
        $db = new Database();
        $conn = $db->getConnection();
        
        $query = "SELECT DATE(created_at) as order_date, 
                  SUM(total_amount) as daily_total 
                  FROM orders 
                  WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
                  GROUP BY DATE(created_at) 
                  ORDER BY order_date ASC";
        
        $stmt = $conn->prepare($query);
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Format data for chart
        $dates = [];
        $sales = [];
        
        // Create array with all 7 days (including days with no sales)
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $dates[] = date('M d', strtotime($date));
            $sales[$date] = 0;
        }
        
        // Fill in actual sales data
        foreach ($results as $row) {
            $sales[$row['order_date']] = (float)$row['daily_total'];
        }
        
        // Final sales array in the correct order
        $salesData = array_values($sales);
        
        echo json_encode([
            'success' => true,
            'labels' => $dates,
            'sales' => $salesData
        ]);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

public function getOrdersCountToday() {
    // Ensure the request is AJAX
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        return;
    }
    
    try {
        // Get count of orders for today
        $db = new Database();
        $conn = $db->getConnection();
        
        $query = "SELECT COUNT(*) as order_count 
                  FROM orders 
                  WHERE DATE(created_at) = CURDATE()";
        
        $stmt = $conn->prepare($query);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'ordersToday' => $result['order_count']
        ]);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}