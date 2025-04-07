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

    public function storeOrder() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerId = filter_input(INPUT_POST, 'customer_id', FILTER_SANITIZE_NUMBER_INT);
            $orderItems = json_decode(filter_input(INPUT_POST, 'order'), true); // Decode the order items
            $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING); // Get the action type

            if (empty($orderItems)) {
                $_SESSION['error'] = "No items in the order.";
                header("Location: /order/order_list");
                exit();
            }

            try {
                // Calculate total amount based on order items
                $totalAmount = array_sum(array_map(function($item) {
                    return $item['quantity'] * $item['price'];
                }, $orderItems));

                // Store the order and get the order ID
                $orderId = $this->orderModel->storeOrder($customerId, $totalAmount, $orderItems);
                $_SESSION['success'] = "Order saved successfully! Order ID: $orderId";

                // Additional actions based on the button clicked
                if ($action === 'print') {
                    header("Location: /order/print_receipt?id=" . $orderId);
                    exit();
                } elseif ($action === 'store') {
                    header("Location: /order/order_list");
                    exit();
                }
            } catch (Exception $e) {
                error_log($e->getMessage());
                $_SESSION['error'] = "Failed to save order: " . $e->getMessage();
            }
        } else {
            $_SESSION['error'] = "Invalid order data.";
        }

        header("Location: /order/order_list");
        exit();
    }

    public function delete() {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['order_id'])) {
            $order_id = filter_input(INPUT_POST, 'order_id', FILTER_SANITIZE_NUMBER_INT);

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