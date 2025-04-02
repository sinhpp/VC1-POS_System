<?php
require_once __DIR__ . '/../Models/ListModel.php';

class OrderListController extends BaseController {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new ListModel();
    }

    public function displayOrders() {
        if (!isset($_SESSION['order'])) {
            $_SESSION['order'] = []; // Ensure session order exists
        }

        $completedOrders = $this->orderModel->getOrders();
        require_once __DIR__ . '/../views/order/order_list.php';
    }

    public function store() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $customer_id = filter_input(INPUT_POST, 'customer_id', FILTER_SANITIZE_NUMBER_INT);
            $total_amount = filter_input(INPUT_POST, 'total_amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $orderItems = $_SESSION['order'] ?? [];

            if (empty($orderItems)) {
                $_SESSION['error'] = "No items in the order!";
                header("Location: /order/order_list");
                exit();
            }

            try {
                $orderId = $this->orderModel->storeOrder($customer_id, $total_amount, $orderItems);
                unset($_SESSION['order']); // Clear cart after order is placed
                $_SESSION['success'] = "Order successfully placed!";
            } catch (Exception $e) {
                $_SESSION['error'] = "Error: " . $e->getMessage();
            }
        }
        header("Location: /order/order_list");
        exit();
    }

    public function delete() {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['order_id'])) {
            $order_id = $_POST['order_id'];

            if ($this->orderModel->deleteOrder($order_id)) {
                $_SESSION['success'] = "Order successfully deleted!";
            } else {
                $_SESSION['error'] = "Failed to delete order.";
            }
        }
        header("Location: /order/order_list");
        exit();
    }
}
?>
