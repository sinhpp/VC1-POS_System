<?php
require_once "Middleware/AuthMiddleware.php";
require_once "Models/DashboardModel.php";

class DashboardController extends BaseController {
    
    private $dashboardModel;
    
    public function __construct() {
        $this->dashboardModel = new DashboardModel();
    }
    
    public function index() {
        // Check if user has access (only admin can access dashboard)
        AuthMiddleware::checkAccess(['admin']);
        
        // Load initial dashboard data
        $dashboardData = [
            'performanceMetrics' => $this->dashboardModel->getPerformanceMetrics('month'),
            'topProducts' => $this->dashboardModel->getTopProducts('today'),
            'salesByCategory' => $this->dashboardModel->getSalesByCategory(),
            'inventoryStatus' => $this->dashboardModel->getInventoryStatus(),
            'topCategories' => $this->dashboardModel->getTopCategories(5),
            'salesExpensesData' => $this->dashboardModel->getSalesExpensesData('6'),
            'recentActivity' => $this->dashboardModel->getSystemActivity(5)
        ];
        
        // Pass data to the view
        $this->view('dashboard/dashboard', $dashboardData);
    }
    
    // Add this method to handle the show() call that's causing the error
    public function show() {
        // Check if user has access (only admin can access dashboard)
        AuthMiddleware::checkAccess(['admin']);
        
        // Redirect to index or handle appropriately
        $this->index();
    }
    
    /**
     * API endpoint to get dashboard data for AJAX requests
     */
    public function getDashboardData() {
        // Check if user has access (only admin can access dashboard)
        AuthMiddleware::checkAccess(['admin']);
        
        try {
            // Get dashboard data
            $dashboardData = [
                'totalSalesToday' => $this->dashboardModel->getTotalSalesToday(),
                'ordersToday' => $this->dashboardModel->getOrdersCountToday(),
                'lowStockCount' => $this->dashboardModel->getLowStockCount(),
                'expensesToday' => $this->dashboardModel->getExpensesToday(),
                'salesLastSevenDays' => $this->dashboardModel->getSalesLastSevenDays(),
                'expensesLastSevenDays' => $this->dashboardModel->getExpensesLastSevenDays(),
                'salesByCategory' => $this->dashboardModel->getSalesByCategory(),
                'inventoryStatus' => $this->dashboardModel->getInventoryStatus()
            ];
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode($dashboardData);
        } catch (Exception $e) {
            // Log the error
            error_log("Dashboard API error: " . $e->getMessage());
            
            // Return error response
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred while fetching dashboard data.']);
        }
    }

    /**
     * API endpoint to get customer activity for AJAX requests
     */
    public function getCustomerActivity() {
        // Check if user has access (only admin can access dashboard)
        AuthMiddleware::checkAccess(['admin']);
        
        try {
            $activities = $this->dashboardModel->getCustomerActivity(10);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode($activities);
        } catch (Exception $e) {
            // Log the error
            error_log("Customer activity API error: " . $e->getMessage());
            
            // Return error response
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred while fetching customer activity.']);
        }
    }

    /**
     * Get sales vs expenses data for the specified period
     */
    public function getSalesExpensesData() {
        // Check if user has access (only admin can access dashboard)
        AuthMiddleware::checkAccess(['admin']);
        
        try {
            $period = isset($_GET['period']) ? $_GET['period'] : '6';
            $data = $this->dashboardModel->getSalesExpensesData($period);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode($data);
        } catch (Exception $e) {
            // Log the error
            error_log("Sales vs Expenses API error: " . $e->getMessage());
            
            // Return error response
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred while fetching sales vs expenses data.']);
        }
    }

    /**
     * Get top products for the specified period
     */
    public function getTopProducts() {
        // Check if user has access (only admin can access dashboard)
        AuthMiddleware::checkAccess(['admin']);
        
        try {
            $period = isset($_GET['period']) ? $_GET['period'] : 'today';
            $products = $this->dashboardModel->getTopProducts($period);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode($products);
        } catch (Exception $e) {
            // Log the error
            error_log("Top Products API error: " . $e->getMessage());
            
            // Return error response
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred while fetching top products.']);
        }
    }

    /**
     * Get system activity data for the dashboard
     */
    public function getSystemActivity() {
        // Check if user has access (only admin can access dashboard)
        AuthMiddleware::checkAccess(['admin']);
        
        try {
            $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
            $activities = $this->dashboardModel->getSystemActivity($limit);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode($activities);
        } catch (Exception $e) {
            // Log the error
            error_log("System Activity API error: " . $e->getMessage());
            
            // Return error response
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred while fetching system activity data.']);
        }
    }

    /**
     * Get performance metrics for the dashboard
     */
    public function getPerformanceData() {
        // Check if user has access (only admin can access dashboard)
        AuthMiddleware::checkAccess(['admin']);
        
        try {
            $period = isset($_GET['period']) ? $_GET['period'] : 'month';
            $metrics = $this->dashboardModel->getPerformanceMetrics($period);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode($metrics);
        } catch (Exception $e) {
            // Log the error
            error_log("Performance Metrics API error: " . $e->getMessage());
            
            // Return error response
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred while fetching performance metrics.']);
        }
    }

    /**
     * Get inventory status data for the dashboard
     */
    public function getInventoryStatus() {
        // Check if user has access (only admin can access dashboard)
        AuthMiddleware::checkAccess(['admin']);
        
        try {
            $status = $this->dashboardModel->getInventoryStatus();
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode($status);
        } catch (Exception $e) {
            // Log the error
            error_log("Inventory Status API error: " . $e->getMessage());
            
            // Return error response
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred while fetching inventory status data.']);
        }
    }
}
