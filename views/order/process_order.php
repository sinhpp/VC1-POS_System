<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'Controllers/OrderController.php';
    $orderController = new OrderController();

    // Assuming you have an order ID after processing the purchase
    $orderId = $_POST['order_id'] ?? null; // Adjust based on your form data
    if ($orderId) {
        $result = $orderController->processOrder($orderId);
        echo $result;
    } else {
        echo "Error: Order ID not provided";
    }
} else {
    echo "Invalid request method";
}