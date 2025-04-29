<?php
require_once __DIR__ . '/../Models/ListModel.php';

class OrderListController extends BaseController {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new ListModel();
    }

    public function displayOrders() {
        if (!isset($_SESSION['order'])) {
            $_SESSION['order'] = [];
        }

        $completedOrders = $this->orderModel->getOrders();
        require_once __DIR__ . '/../views/order/order_list.php';
    }

    public function viewOrder($id) {
        $orderId = filter_var($id, FILTER_VALIDATE_INT);
        if (!$orderId) {
            $_SESSION['error'] = "Invalid order ID.";
            header("Location: /order/order_list");
            exit();
        }

        $orderDetails = $this->orderModel->getOrderDetails($orderId);
        if (empty($orderDetails)) {
            $_SESSION['error'] = "Order not found.";
            header("Location: /order/order_list");
            exit();
        }

        require_once __DIR__ . '/../views/order/view_order.php';
    }

    public function storeOrder() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerId = filter_input(INPUT_POST, 'customer_id', FILTER_SANITIZE_NUMBER_INT);
            $rawOrder = filter_input(INPUT_POST, 'order');
            $orderItems = json_decode($rawOrder, true);
            $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

            error_log("storeOrder - Raw POST Data: " . print_r($_POST, true));
            error_log("storeOrder - Customer ID: $customerId, Action: $action");
            error_log("storeOrder - Raw Order: " . print_r($rawOrder, true));
            error_log("storeOrder - Decoded Order Items: " . print_r($orderItems, true));

            if (empty($orderItems) || !is_array($orderItems)) {
                $_SESSION['error'] = "No items in the order or invalid order data.";
                error_log("storeOrder - Error: No items in the order or invalid order data.");
                header("Location: /order/order_list");
                exit();
            }

            try {
                $totalAmount = array_sum(array_map(function($item) {
                    if (!isset($item['quantity']) || !isset($item['price'])) {
                        throw new Exception("Invalid item data: missing quantity or price.");
                    }
                    return $item['quantity'] * $item['price'];
                }, $orderItems));
                error_log("storeOrder - Calculated Total Amount: $totalAmount");

                $orderId = $this->orderModel->storeOrder($customerId, $totalAmount, $orderItems);
                $_SESSION['success'] = "Order saved successfully! Order ID: $orderId";
                error_log("storeOrder - Success: Order ID $orderId saved.");

                if ($action === 'print') {
                    header("Location: /order/print_receipt?id=" . $orderId);
                    exit();
                } elseif ($action === 'store') {
                    header("Location: /order/order_list");
                    exit();
                }
            } catch (Exception $e) {
                error_log("storeOrder - Exception: " . $e->getMessage());
                $_SESSION['error'] = "Failed to save order: " . $e->getMessage();
            }
        } else {
            $_SESSION['error'] = "Invalid order data.";
            error_log("storeOrder - Error: Invalid order data, not a POST request.");
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

    public function getProductsForOrder() {
        header('Content-Type: application/json');
        
        $order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
        
        if ($order_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid order ID']);
            exit;
        }

        try {
            $query = "
                SELECT 
                    p.id, p.name, p.barcode, p.price, p.stock, p.category, p.image,
                    p.size, p.descriptions, p.discount, p.gender, p.discount_type, p.created_at,
                    oi.quantity
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = :order_id
            ";
            $stmt = $this->orderModel->getConnection()->prepare($query);
            $stmt->execute([':order_id' => $order_id]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($products)) {
                error_log("No products found for order_id: $order_id");
                echo json_encode(['success' => true, 'products' => [], 'message' => 'No items associated with this order']);
            } else {
                error_log("Products found for order_id: $order_id: " . print_r($products, true));
                echo json_encode(['success' => true, 'products' => $products]);
            }
        } catch (Exception $e) {
            error_log("Database error for order_id $order_id: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
        exit();
    }
}
?>