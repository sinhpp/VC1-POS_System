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
    
    /**
     * Get inventory status data
     */
    public function getInventoryStatus() {
        try {
            // Check if database connection is valid
            if (!$this->db) {
                return [
                    'inStockCount' => 0,
                    'lowStockCount' => 0,
                    'outOfStockCount' => 0,
                    'disabledCount' => 0
                ];
            }
            
            // Get counts for each inventory status
            $query = "SELECT 
                        SUM(CASE WHEN quantity > low_stock_threshold AND status = 1 THEN 1 ELSE 0 END) as in_stock,
                        SUM(CASE WHEN quantity <= low_stock_threshold AND quantity > 0 AND status = 1 THEN 1 ELSE 0 END) as low_stock,
                        SUM(CASE WHEN quantity = 0 AND status = 1 THEN 1 ELSE 0 END) as out_of_stock,
                        SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as disabled
                      FROM products";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return [
                'inStockCount' => intval($result['in_stock']),
                'lowStockCount' => intval($result['low_stock']),
                'outOfStockCount' => intval($result['out_of_stock']),
                'disabledCount' => intval($result['disabled'])
            ];
        } catch (Exception $e) {
            error_log("Error fetching inventory status: " . $e->getMessage());
            return [
                'inStockCount' => 0,
                'lowStockCount' => 0,
                'outOfStockCount' => 0,
                'disabledCount' => 0
            ];
        }
    }
    
    /**
     * Get sales by category data
     */
    public function getSalesByCategory() {
        try {
            // Check if database connection is valid
            if (!$this->db) {
                return [
                    'labels' => [],
                    'data' => []
                ];
            }
            
            // Get sales by category for the last 30 days
            $query = "SELECT p.category, SUM(oi.quantity * oi.price) as total_sales
                     FROM order_items oi
                     JOIN products p ON oi.product_id = p.id
                     JOIN orders o ON oi.order_id = o.id
                     WHERE o.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                     GROUP BY p.category
                     ORDER BY total_sales DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $labels = [];
            $data = [];
            
            foreach ($results as $row) {
                $labels[] = $row['category'];
                $data[] = floatval($row['total_sales']);
            }
            
            return [
                'labels' => $labels,
                'data' => $data
            ];
        } catch (Exception $e) {
            error_log("Error fetching sales by category: " . $e->getMessage());
            return [
                'labels' => [],
                'data' => []
            ];
        }
    }
    
    /**
     * Get recent customer activity
     */
    public function getCustomerActivity($limit = 10) {
        try {
            // Check if database connection is valid
            if (!$this->db) {
                return [];
            }
            
            // Get recent orders
            $query = "SELECT o.id, o.customer_id, COALESCE(c.name, 'Walk-in Customer') as customer_name, 
                            o.total_amount, o.created_at, 'order' as type,
                            CONCAT('Placed an order for $', FORMAT(o.total_amount, 2)) as description
                     FROM orders o
                     LEFT JOIN customers c ON o.customer_id = c.id
                     WHERE o.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                     ORDER BY o.created_at DESC
                     LIMIT :limit";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Format the time ago for each activity
            foreach ($activities as &$activity) {
                $activity['time_ago'] = $this->timeAgo(strtotime($activity['created_at']));
            }
            
            return $activities;
        } catch (Exception $e) {
            error_log("Error fetching customer activity: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Helper function to format time ago
     */
    private function timeAgo($timestamp) {
        $currentTime = time();
        $timeDiff = $currentTime - $timestamp;
        
        $seconds = $timeDiff;
        $minutes = round($timeDiff / 60);
        $hours = round($timeDiff / 3600);
        $days = round($timeDiff / 86400);
        $weeks = round($timeDiff / 604800);
        $months = round($timeDiff / 2629440);
        $years = round($timeDiff / 31553280);
        
        if ($seconds <= 60) {
            return "Just now";
        } else if ($minutes <= 60) {
            return ($minutes == 1) ? "1 minute ago" : "$minutes minutes ago";
        } else if ($hours <= 24) {
            return ($hours == 1) ? "1 hour ago" : "$hours hours ago";
        } else if ($days <= 7) {
            return ($days == 1) ? "Yesterday" : "$days days ago";
        } else if ($weeks <= 4.3) {
            return ($weeks == 1) ? "1 week ago" : "$weeks weeks ago";
        } else if ($months <= 12) {
            return ($months == 1) ? "1 month ago" : "$months months ago";
        } else {
            return ($years == 1) ? "1 year ago" : "$years years ago";
        }
    }

    /**
     * Get sales vs expenses data for the specified period
     */
    public function getSalesExpensesData($period = '6') {
        try {
            // Check if database connection is valid
            if (!$this->db) {
                return [
                    'labels' => [],
                    'sales' => [],
                    'expenses' => [],
                    'profit' => []
                ];
            }
            
            // Convert period to integer
            $months = intval($period);
            
            // Generate labels (month names)
            $labels = [];
            $sales = [];
            $expenses = [];
            $profit = [];
            
            for ($i = $months - 1; $i >= 0; $i--) {
                $date = new DateTime();
                $date->modify("-$i month");
                $labels[] = $date->format('M Y');
                
                $monthStart = $date->format('Y-m-01');
                $monthEnd = $date->format('Y-m-t');
                
                // Get sales for this month
                $salesQuery = "SELECT COALESCE(SUM(total_amount), 0) as total FROM orders 
                              WHERE created_at BETWEEN :start AND :end";
                $salesStmt = $this->db->prepare($salesQuery);
                $salesStmt->execute([':start' => $monthStart, ':end' => $monthEnd]);
                $salesResult = $salesStmt->fetch(PDO::FETCH_ASSOC);
                $monthlySales = floatval($salesResult['total']);
                $sales[] = $monthlySales;
                
                // Get expenses for this month
                $expensesQuery = "SELECT COALESCE(SUM(amount), 0) as total FROM expenses 
                                 WHERE date BETWEEN :start AND :end";
                $expensesStmt = $this->db->prepare($expensesQuery);
                $expensesStmt->execute([':start' => $monthStart, ':end' => $monthEnd]);
                $expensesResult = $expensesStmt->fetch(PDO::FETCH_ASSOC);
                $monthlyExpenses = floatval($expensesResult['total']);
                $expenses[] = $monthlyExpenses;
                
                // Calculate profit
                $profit[] = $monthlySales - $monthlyExpenses;
            }
            
            return [
                'labels' => $labels,
                'sales' => $sales,
                'expenses' => $expenses,
                'profit' => $profit
            ];
        } catch (Exception $e) {
            error_log("Error fetching sales vs expenses data: " . $e->getMessage());
            return [
                'labels' => [],
                'sales' => [],
                'expenses' => [],
                'profit' => []
            ];
        }
    }

    /**
     * Get top products for the specified period
     */
    public function getTopProducts($period = 'today') {
        try {
            // Check if database connection is valid
            if (!$this->db) {
                return [];
            }
            
            // Determine date range based on period
            $startDate = '';
            $endDate = date('Y-m-d H:i:s');
            
            switch ($period) {
                case 'today':
                    $startDate = date('Y-m-d 00:00:00');
                    break;
                case 'week':
                    $startDate = date('Y-m-d 00:00:00', strtotime('-7 days'));
                    break;
                case 'month':
                    $startDate = date('Y-m-d 00:00:00', strtotime('-30 days'));
                    break;
                default:
                    $startDate = date('Y-m-d 00:00:00');
            }
            
            // Get top selling products for the period
            $query = "SELECT p.id, p.name, p.barcode, p.price, p.category, p.image, 
                            SUM(oi.quantity) as quantity_sold, 
                            SUM(oi.quantity * oi.price) as revenue
                     FROM order_items oi
                     JOIN products p ON oi.product_id = p.id
                     JOIN orders o ON oi.order_id = o.id
                     WHERE o.created_at BETWEEN :start AND :end
                     GROUP BY p.id
                     ORDER BY quantity_sold DESC
                     LIMIT 10";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':start' => $startDate, ':end' => $endDate]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $products;
        } catch (Exception $e) {
            error_log("Error fetching top products: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get top categories by revenue
     */
    public function getTopCategories($limit = 5) {
        try {
            // Check if database connection is valid
            if (!$this->db) {
                return [];
            }
            
            // Get top categories by revenue
            $query = "SELECT p.category, SUM(oi.quantity * oi.price) as revenue
                     FROM order_items oi
                     JOIN products p ON oi.product_id = p.id
                     JOIN orders o ON oi.order_id = o.id
                     WHERE o.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                     GROUP BY p.category
                     ORDER BY revenue DESC
                     LIMIT :limit";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $categories;
        } catch (Exception $e) {
            error_log("Error fetching top categories: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get system activity log
     */
    public function getSystemActivity($limit = 10) {
        try {
            // Check if database connection is valid
            if (!$this->db) {
                return [];
            }
            
            // Get recent system activity
            $query = "SELECT a.id, a.user_id, a.action, a.entity_type, a.entity_id, a.details, a.created_at,
                        u.username as user
                 FROM activity_log a
                 LEFT JOIN users u ON a.user_id = u.id
                 ORDER BY a.created_at DESC
                 LIMIT :limit";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Format activities for display
            $formattedActivities = [];
            foreach ($activities as $activity) {
                $type = '';
                $title = '';
                $description = '';
            
                switch ($activity['entity_type']) {
                    case 'user':
                        $type = 'user';
                        $title = 'User Management';
                        if ($activity['action'] === 'create') {
                            $description = "Created a new user account";
                        } elseif ($activity['action'] === 'update') {
                            $description = "Updated user information";
                        } elseif ($activity['action'] === 'delete') {
                            $description = "Deleted a user account";
                        } elseif ($activity['action'] === 'login') {
                            $type = 'login';
                            $title = 'User Login';
                            $description = "Logged into the system";
                        }
                        break;
                    
                    case 'product':
                        $type = 'product';
                        $title = 'Product Management';
                        if ($activity['action'] === 'create') {
                            $description = "Added a new product";
                        } elseif ($activity['action'] === 'update') {
                            $description = "Updated product information";
                        } elseif ($activity['action'] === 'delete') {
                            $description = "Deleted a product";
                        }
                        break;
                    
                    case 'order':
                        $type = 'order';
                        $title = 'Order Management';
                        if ($activity['action'] === 'create') {
                            $description = "Created a new order";
                        } elseif ($activity['action'] === 'update') {
                            $description = "Updated an order";
                        } elseif ($activity['action'] === 'delete') {
                            $description = "Deleted an order";
                        }
                        break;
                    
                    case 'setting':
                        $type = 'setting';
                        $title = 'System Settings';
                        $description = "Updated system settings";
                        break;
                    
                    default:
                        $type = 'info';
                        $title = 'System Activity';
                        $description = $activity['action'] . ' ' . $activity['entity_type'];
                }
            
                $formattedActivities[] = [
                    'id' => $activity['id'],
                    'type' => $type,
                    'title' => $title,
                    'description' => $description,
                    'user' => $activity['user'] ?? 'System',
                    'time_ago' => $this->timeAgo(strtotime($activity['created_at']))
                ];
            }
            
            return $formattedActivities;
        } catch (Exception $e) {
            error_log("Error fetching system activity: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get store performance metrics
     */
    public function getPerformanceMetrics($period = 'month') {
        try {
            // Check if database connection is valid
            if (!$this->db) {
                return [
                    'totalSales' => 0,
                    'totalOrders' => 0,
                    'avgOrderValue' => 0,
                    'profitMargin' => 0,
                    'salesGrowth' => 0,
                    'ordersGrowth' => 0,
                    'aovGrowth' => 0,
                    'marginGrowth' => 0,
                    'chartData' => [
                        'labels' => [],
                        'sales' => [],
                        'expenses' => [],
                        'profit' => []
                    ]
                ];
            }
            
            // Determine date ranges based on period
            $currentStart = '';
            $currentEnd = date('Y-m-d H:i:s');
            $previousStart = '';
            $previousEnd = '';
            
            switch ($period) {
                case 'week':
                    $currentStart = date('Y-m-d 00:00:00', strtotime('-7 days'));
                    $previousStart = date('Y-m-d 00:00:00', strtotime('-14 days'));
                    $previousEnd = date('Y-m-d 23:59:59', strtotime('-8 days'));
                    break;
                case 'month':
                    $currentStart = date('Y-m-01 00:00:00');
                    $previousStart = date('Y-m-01 00:00:00', strtotime('-1 month'));
                    $previousEnd = date('Y-m-d 23:59:59', strtotime('last day of previous month'));
                    break;
                case 'quarter':
                    $currentQuarter = ceil(date('n') / 3);
                    $currentYear = date('Y');
                    $currentStart = date('Y-m-d 00:00:00', strtotime($currentYear . '-' . (($currentQuarter - 1) * 3 + 1) . '-01'));
                    $previousStart = date('Y-m-d 00:00:00', strtotime('-3 months', strtotime($currentStart)));
                    $previousEnd = date('Y-m-d 23:59:59', strtotime('-1 day', strtotime($currentStart)));
                    break;
                case 'year':
                    $currentStart = date('Y-01-01 00:00:00');
                    $previousStart = date('Y-01-01 00:00:00', strtotime('-1 year'));
                    $previousEnd = date('Y-12-31 23:59:59', strtotime('-1 year'));
                    break;
                default:
                    $currentStart = date('Y-m-01 00:00:00');
                    $previousStart = date('Y-m-01 00:00:00', strtotime('-1 month'));
                    $previousEnd = date('Y-m-d 23:59:59', strtotime('last day of previous month'));
            }
            
            // Get current period metrics
            $currentSalesQuery = "SELECT COALESCE(SUM(total_amount), 0) as total_sales, COUNT(*) as total_orders 
                             FROM orders 
                             WHERE created_at BETWEEN :start AND :end";
            $currentSalesStmt = $this->db->prepare($currentSalesQuery);
            $currentSalesStmt->execute([':start' => $currentStart, ':end' => $currentEnd]);
            $currentSalesResult = $currentSalesStmt->fetch(PDO::FETCH_ASSOC);
            
            $currentExpensesQuery = "SELECT COALESCE(SUM(amount), 0) as total_expenses 
                                FROM expenses 
                                WHERE date BETWEEN :start AND :end";
            $currentExpensesStmt = $this->db->prepare($currentExpensesQuery);
            $currentExpensesStmt->execute([':start' => $currentStart, ':end' => $currentEnd]);
            $currentExpensesResult = $currentExpensesStmt->fetch(PDO::FETCH_ASSOC);
            
            // Get previous period metrics
            $previousSalesQuery = "SELECT COALESCE(SUM(total_amount), 0) as total_sales, COUNT(*) as total_orders 
                              FROM orders 
                              WHERE created_at BETWEEN :start AND :end";
            $previousSalesStmt = $this->db->prepare($previousSalesQuery);
            $previousSalesStmt->execute([':start' => $previousStart, ':end' => $previousEnd]);
            $previousSalesResult = $previousSalesStmt->fetch(PDO::FETCH_ASSOC);
            
            $previousExpensesQuery = "SELECT COALESCE(SUM(amount), 0) as total_expenses 
                                 FROM expenses 
                                 WHERE date BETWEEN :start AND :end";
            $previousExpensesStmt = $this->db->prepare($previousExpensesQuery);
            $previousExpensesStmt->execute([':start' => $previousStart, ':end' => $previousEnd]);
            $previousExpensesResult = $previousExpensesStmt->fetch(PDO::FETCH_ASSOC);
            
            // Calculate metrics
            $totalSales = floatval($currentSalesResult['total_sales']);
            $totalOrders = intval($currentSalesResult['total_orders']);
            $totalExpenses = floatval($currentExpensesResult['total_expenses']);
            
            $previousTotalSales = floatval($previousSalesResult['total_sales']);
            $previousTotalOrders = intval($previousSalesResult['total_orders']);
            $previousTotalExpenses = floatval($previousExpensesResult['total_expenses']);
            
            $avgOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
            $previousAvgOrderValue = $previousTotalOrders > 0 ? $previousTotalSales / $previousTotalOrders : 0;
            
            $profit = $totalSales - $totalExpenses;
            $previousProfit = $previousTotalSales - $previousTotalExpenses;
            
            $profitMargin = $totalSales > 0 ? ($profit / $totalSales) * 100 : 0;
            $previousProfitMargin = $previousTotalSales > 0 ? ($previousProfit / $previousTotalSales) * 100 : 0;
            
            // Calculate growth percentages
            $salesGrowth = $previousTotalSales > 0 ? (($totalSales - $previousTotalSales) / $previousTotalSales) * 100 : 0;
            $ordersGrowth = $previousTotalOrders > 0 ? (($totalOrders - $previousTotalOrders) / $previousTotalOrders) * 100 : 0;
            $aovGrowth = $previousAvgOrderValue > 0 ? (($avgOrderValue - $previousAvgOrderValue) / $previousAvgOrderValue) * 100 : 0;
            $marginGrowth = $previousProfitMargin > 0 ? (($profitMargin - $previousProfitMargin) / $previousProfitMargin) * 100 : 0;
            
            // Get chart data
            $chartData = $this->getPerformanceChartData($period);
            
            return [
                'totalSales' => $totalSales,
                'totalOrders' => $totalOrders,
                'avgOrderValue' => $avgOrderValue,
                'profitMargin' => round($profitMargin, 1),
                'salesGrowth' => round($salesGrowth, 1),
                'ordersGrowth' => round($ordersGrowth, 1),
                'aovGrowth' => round($aovGrowth, 1),
                'marginGrowth' => round($marginGrowth, 1),
                'chartData' => $chartData
            ];
        } catch (Exception $e) {
            error_log("Error fetching performance metrics: " . $e->getMessage());
            return [
                'totalSales' => 0,
                'totalOrders' => 0,
                'avgOrderValue' => 0,
                'profitMargin' => 0,
                'salesGrowth' => 0,
                'ordersGrowth' => 0,
                'aovGrowth' => 0,
                'marginGrowth' => 0,
                'chartData' => [
                    'labels' => [],
                    'sales' => [],
                    'expenses' => [],
                    'profit' => []
                ]
            ];
        }
    }

    /**
     * Get performance chart data
     */
    private function getPerformanceChartData($period = 'month') {
        try {
            // Check if database connection is valid
            if (!$this->db) {
                return [
                    'labels' => [],
                    'sales' => [],
                    'expenses' => [],
                    'profit' => []
                ];
            }
            
            $labels = [];
            $sales = [];
            $expenses = [];
            $profit = [];
            
            // Determine date format and number of data points based on period
            $format = '';
            $interval = '';
            $dataPoints = 0;
            
            switch ($period) {
                case 'week':
                    $format = 'D';
                    $interval = '1 day';
                    $dataPoints = 7;
                    break;
                case 'month':
                    $format = 'd M';
                    $interval = '1 day';
                    $dataPoints = date('t'); // Number of days in current month
                    break;
                case 'quarter':
                    $format = 'W';
                    $interval = '1 week';
                    $dataPoints = 13; // ~13 weeks in a quarter
                    break;
                case 'year':
                    $format = 'M';
                    $interval = '1 month';
                    $dataPoints = 12;
                    break;
                default:
                    $format = 'd M';
                    $interval = '1 day';
                    $dataPoints = date('t');
            }
            
            // Generate data points
            $endDate = new DateTime();
            $startDate = clone $endDate;
            
            if ($period === 'week') {
                $startDate->modify('-6 days');
            } elseif ($period === 'month') {
                $startDate->modify('first day of this month');
            } elseif ($period === 'quarter') {
                $currentQuarter = ceil($endDate->format('n') / 3);
                $startDate->setDate($endDate->format('Y'), ($currentQuarter - 1) * 3 + 1, 1);
            } elseif ($period === 'year') {
                $startDate->setDate($endDate->format('Y'), 1, 1);
            }
            
            $currentDate = clone $startDate;
            
            while ($currentDate <= $endDate) {
                $labels[] = $currentDate->format($format);
                
                $periodStart = $currentDate->format('Y-m-d 00:00:00');
                
                // For the last data point, use current time as end
                if ($currentDate->format('Y-m-d') === $endDate->format('Y-m-d')) {
                    $periodEnd = $endDate->format('Y-m-d H:i:s');
                } else {
                    $nextDate = clone $currentDate;
                    $nextDate->modify('+' . $interval);
                    $nextDate->modify('-1 second');
                    $periodEnd = $nextDate->format('Y-m-d H:i:s');
                }
                
                // Get sales for this period
                $salesQuery = "SELECT COALESCE(SUM(total_amount), 0) as total FROM orders 
                              WHERE created_at BETWEEN :start AND :end";
                $salesStmt = $this->db->prepare($salesQuery);
                $salesStmt->execute([':start' => $periodStart, ':end' => $periodEnd]);
                $salesResult = $salesStmt->fetch(PDO::FETCH_ASSOC);
                $periodSales = floatval($salesResult['total']);
                $sales[] = $periodSales;
                
                // Get expenses for this period
                $expensesQuery = "SELECT COALESCE(SUM(amount), 0) as total FROM expenses 
                                 WHERE date BETWEEN :start AND :end";
                $expensesStmt = $this->db->prepare($expensesQuery);
                $expensesStmt->execute([':start' => $periodStart, ':end' => $periodEnd]);
                $expensesResult = $expensesStmt->fetch(PDO::FETCH_ASSOC);
                $periodExpenses = floatval($expensesResult['total']);
                $expenses[] = $periodExpenses;
                
                // Calculate profit
                $profit[] = $periodSales - $periodExpenses;
                
                // Move to next period
                $currentDate->modify('+' . $interval);
            }
            
            return [
                'labels' => $labels,
                'sales' => $sales,
                'expenses' => $expenses,
                'profit' => $profit
            ];
        } catch (Exception $e) {
            error_log("Error generating performance chart data: " . $e->getMessage());
            return [
                'labels' => [],
                'sales' => [],
                'expenses' => [],
                'profit' => []
            ];
        }
    }

}