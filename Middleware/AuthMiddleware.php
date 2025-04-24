<?php
class AuthMiddleware {
    public static function checkAccess($allowedRoles = []) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['users']) || $_SESSION['users'] !== true) {
            header("Location: /");
            exit();
        }
        
        // If specific roles are required and user doesn't have one of them
        if (!empty($allowedRoles) && (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], $allowedRoles))) {
            // Redirect based on role
            switch ($_SESSION['user_role']) {
                case 'stock_manager':
                    header("Location: /product/low_stock_alert");
                    break;
                case 'cashier':
                    header("Location: /order");
                    break;
                default:
                    header("Location: /");
            }
            exit();
        }
        
        return true;
    }
}