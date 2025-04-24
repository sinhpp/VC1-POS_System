<?php
require_once __DIR__ . "/BaseModel.php";

class DashboardModel extends BaseModel {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get total sales for today with error handling
     */
    public function getTotalSalesToday() {
        try {
            // Check if database connection is valid
            if (!$this->db) {
                return 0;
            }
            
            $query = "SELECT COALESCE(SUM(total_amount), 0) as total_sales 
                     FROM orders 
                     WHERE DATE(created_at) = CURDATE()";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_sales'];
        } catch (Exception $e) {
            error_log("Error fetching total sales: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get order count for today with error handling
     */
    public function getOrdersCountToday() {
        try {
            // Check if database connection is valid
            if (!$this->db) {
                return 0;
            }
            
            $query = "SELECT COUNT(*) as order_count 
                     FROM orders 
                     WHERE DATE(created_at) = CURDATE()";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['order_count'];
        } catch (Exception $e) {
            error_log("Error fetching order count: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get count of products with low stock with error handling
     */
    public function getLowStockCount() {
        try {
            // Check if database connection is valid
            if (!$this->db) {
                return 0;
            }
            
            $query = "SELECT COUNT(*) as low_stock_count 
                     FROM products 
                     WHERE stock <= 10 AND status = 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['low_stock_count'];
        } catch (Exception $e) {
            error_log("Error fetching low stock count: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get total expenses for today with error handling
     */
    public function getExpensesToday() {
        try {
            // Check if database connection is valid
            if (!$this->db) {
                return 0;
            }
            
            // Check if expenses table exists
            $checkTableQuery = "SHOW TABLES LIKE 'expenses'";
            $checkTableStmt = $this->db->prepare($checkTableQuery);
            $checkTableStmt->execute();
            
            if ($checkTableStmt->rowCount() === 0) {
                // Table doesn't exist, return 0
                return 0;
            }
            
            $query = "SELECT COALESCE(SUM(amount), 0) as total_expenses 
                     FROM expenses 
                     WHERE DATE(expense_date) = CURDATE()";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_expenses'];
        } catch (Exception $e) {
            error_log("Error fetching expenses: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get sales data for the last 7 days with error handling
     */
    public function getSalesLastSevenDays() {
        try {
            // Check if database connection is valid
            if (!$this->db) {
                return ['labels' => [], 'data' => []];
            }
            
            $query = "SELECT DATE(created_at) as sale_date, 
                            COALESCE(SUM(total_amount), 0) as daily_sales
                     FROM orders
                     WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                     GROUP BY DATE(created_at)
                     ORDER BY sale_date ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Create a complete 7-day array with zeros for days with no sales
            $salesData = [];
            $labels = [];
            
            // Generate the last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $labels[] = date('D', strtotime($date));
                $salesData[$date] = 0;
            }
            
            // Fill in actual sales data
            foreach ($results as $row) {
                $salesData[$row['sale_date']] = (float)$row['daily_sales'];
            }
            
            return [
                'labels' => $labels,
                'data' => array_values($salesData)
            ];
        } catch (Exception $e) {
            error_log("Error fetching sales data: " . $e->getMessage());
            return [
                'labels' => [],
                'data' => []
            ];
        }
    }
    
    /**
     * Get expenses data for the last 7 days with error handling
     */
    public function getExpensesLastSevenDays() {
        try {
            // Check if database connection is valid
            if (!$this->db) {
                return ['labels' => [], 'data' => []];
            }
            
            // Check if expenses table exists
            $checkTableQuery = "SHOW TABLES LIKE 'expenses'";
            $checkTableStmt = $this->db->prepare($checkTableQuery);
            $checkTableStmt->execute();
            
            if ($checkTableStmt->rowCount() === 0) {
                // Table doesn't exist, return empty data
                $labels = [];
                for ($i = 6; $i >= 0; $i--) {
                    $labels[] = date('D', strtotime("-$i days"));
                }
                return [
                    'labels' => $labels,
                    'data' => array_fill(0, 7, 0)
                ];
            }
            
            $query = "SELECT DATE(expense_date) as expense_date, 
                            COALESCE(SUM(amount), 0) as daily_expenses
                     FROM expenses
                     WHERE expense_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                     GROUP BY DATE(expense_date)
                     ORDER BY expense_date ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Create a complete 7-day array with zeros for days with no expenses
            $expensesData = [];
            $labels = [];
            
            // Generate the last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $labels[] = date('D', strtotime($date));
                $expensesData[$date] = 0;
            }
            
            // Fill in actual expenses data
            foreach ($results as $row) {
                $expensesData[$row['expense_date']] = (float)$row['daily_expenses'];
            }
            
            return [
                'labels' => $labels,
                'data' => array_values($expensesData)
            ];
        } catch (Exception $e) {
            error_log("Error fetching expenses data: " . $e->getMessage());
            return [
                'labels' => [],
                'data' => []
            ];
        }
    }
    
    /**
     * Get top selling products with error handling
     */
    public function getTopSellingProducts($limit = 5) {
        try {
            // Check if database connection is valid
            if (!$this->db) {
                return [];
            }
            
            // Check if order_items table exists
            $checkTableQuery = "SHOW TABLES LIKE 'order_items'";
            $checkTableStmt = $this->db->prepare($checkTableQuery);
            $checkTableStmt->execute();
            
            if ($checkTableStmt->rowCount() === 0) {
                // Table doesn't exist, return empty array
                return [];
            }
            
            $query = "SELECT p.name, SUM(oi.quantity) as total_sold
                     FROM order_items oi
                     JOIN products p ON oi.product_id = p.id
                     JOIN orders o ON oi.order_id = o.id
                     WHERE o.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                     GROUP BY p.id
                     ORDER BY total_sold DESC
                     LIMIT :limit";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error fetching top selling products: " . $e->getMessage());
            return [];
        }
    }
}