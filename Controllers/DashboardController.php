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
        
        try {
            // Initialize default values
            $dashboardData = [
                'totalSalesToday' => '0.00',
                'ordersToday' => '0',
                'lowStockCount' => '0',
                'expensesToday' => '0.00',
                'salesLastSevenDays' => ['labels' => [], 'data' => []],
                'expensesLastSevenDays' => ['labels' => [], 'data' => []],
                'topSellingProducts' => []
            ];
            
            // Get dashboard data with error handling for each method
            $dashboardData['totalSalesToday'] = number_format($this->dashboardModel->getTotalSalesToday(), 2);
            $dashboardData['ordersToday'] = $this->dashboardModel->getOrdersCountToday();
            $dashboardData['lowStockCount'] = $this->dashboardModel->getLowStockCount();
            $dashboardData['expensesToday'] = number_format($this->dashboardModel->getExpensesToday(), 2);
            $dashboardData['salesLastSevenDays'] = $this->dashboardModel->getSalesLastSevenDays();
            $dashboardData['expensesLastSevenDays'] = $this->dashboardModel->getExpensesLastSevenDays();
            $dashboardData['topSellingProducts'] = $this->dashboardModel->getTopSellingProducts();
            
            $this->view('dashboard/dashboard', $dashboardData);
        } catch (Exception $e) {
            // Log the error
            error_log("Dashboard error: " . $e->getMessage());
            
            // Set error message
            $dashboardData = [
                'error' => 'An error occurred while loading the dashboard: ' . $e->getMessage()
            ];
            
            $this->view('dashboard/dashboard', $dashboardData);
        }
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
                'expensesLastSevenDays' => $this->dashboardModel->getExpensesLastSevenDays()
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
}
