<?php
require_once __DIR__ . '../../Controllers/BaseController.php';
require_once __DIR__ . '../../Models/OrderModel.php';

class DashboardController extends BaseController {
    public function show() {
        $this->view('dashboard/dashboard');
    }

    private $orderModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
    }

    public function index() {
        // Check if user is admin
        if (!isAdmin()) {
            header("Location: /errors/403");
            return;
        }

        // Get order history
        $sortBy = $_GET['sort'] ?? 'order_date';
        $filterStatus = $_GET['status'] ?? null;
        $orders = $this->orderModel->getOrderHistory($sortBy, $filterStatus);

        // Render dashboard view
        $this->renderView('dashboard/index', ['orders' => $orders]);
    }

    private function renderView($view, $data) {
        extract($data);
        include __DIR__ . "/../views/$view.php";
    }

    public function getOrderHistory($sortBy, $filterStatus) {
        $query = "SELECT o.id, u.name AS customer_name, o.order_date, o.total_amount, o.status 
                  FROM orders o 
                  JOIN users u ON o.user_id = u.id";
        if ($filterStatus) {
            $query .= " WHERE o.status = ?";
        }
        $query .= " ORDER BY $sortBy";
        return $this->db->fetchAll($query, $filterStatus ? [$filterStatus] : []);
    }
}