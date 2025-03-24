<?php
session_start();
 // Assume you have a database connection file
require_once '../vendor/fpdf186/fpdf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $order = json_decode($_POST['order'], true);
    $totalPrice = floatval($_POST['totalPrice']);
    $customerName = $_POST['customerName'];
    $shippingAddress = $_POST['shippingAddress'];
    $billingAddress = $_POST['billingAddress'];
    $contactDetails = $_POST['contactDetails'];
    $paymentMethod = $_POST['paymentMethod'];

    // Insert into customers table (if not already existing)
    $stmt = $pdo->prepare("INSERT INTO customers (name, phone, email) VALUES (?, ?, ?)");
    $stmt->execute([$customerName, $contactDetails, null]); // Adjust based on your needs
    $customerId = $pdo->lastInsertId();

    // Insert into orders table
    $userId = $_SESSION['user_id'] ?? 1; // Assume a logged-in user
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, customer_id, total_amount, payment_status) VALUES (?, ?, ?, 'pending')");
    $stmt->execute([$userId, $customerId, $totalPrice]);
    $orderId = $pdo->lastInsertId();

    // Insert order items
    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?)");
    foreach ($order as $item) {
        $stmt->execute([$orderId, $item['id'], $item['quantity'], $item['price'], $item['price'] * $item['quantity']]);
    }

    // Insert payment (assuming payment is processed here)
    $stmt = $pdo->prepare("INSERT INTO payments (order_id, payment_method, amount) VALUES (?, ?, ?)");
    $paymentMethodMapped = $paymentMethod === 'Mastercard' || $paymentMethod === 'Visa' ? 'card' : 'cash';
    $stmt->execute([$orderId, $paymentMethodMapped, $totalPrice]);

    // Update order payment status
    $stmt = $pdo->prepare("UPDATE orders SET payment_status = 'paid' WHERE id = ?");
    $stmt->execute([$orderId]);

    // Redirect or generate receipt
    header("Location: /receipt.php?order_id=" . $orderId);
    exit;
}
?>