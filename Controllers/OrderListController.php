<?php
require_once __DIR__ . '/../Models/ListModel.php';

class OrderListController extends BaseController {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new ListModel();
    }

    public function displayOrders() {
        // Fetch orders with customer names
        $completedOrders = $this->orderModel->getOrders();

        // Load the view
        require_once __DIR__ . '/../views/order/order_list.php';
    }
}
?>
