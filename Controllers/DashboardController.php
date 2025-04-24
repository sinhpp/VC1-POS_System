<?php
require_once "Middleware/AuthMiddleware.php";

class DashboardController extends BaseController {
    
    public function __construct() {
        // Any constructor code
    }
    
    public function index() {
        // Check if user has access (only admin can access dashboard)
        AuthMiddleware::checkAccess(['admin']);
        
        // Rest of the dashboard code...
        // ...
        $this->view('dashboard/dashboard');
    }
    
    // Add this method to handle the show() call that's causing the error
    public function show() {
        // Check if user has access (only admin can access dashboard)
        AuthMiddleware::checkAccess(['admin']);
        
        // Redirect to index or handle appropriately
        $this->index();
    }
    
    // Add similar access control to all dashboard methods
    public function anyOtherMethod() {
        AuthMiddleware::checkAccess(['admin']);
        // Method implementation
    }
}
