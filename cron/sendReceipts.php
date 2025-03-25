<?php
require_once __DIR__ . '/../Controllers/EmailController.php';
require_once __DIR__ . '/../Database/Database.php';

$emailController = new EmailController();
$db = new Database();

// Fetch new orders that haven't received a receipt
$query = "SELECT id FROM orders WHERE status = 'new'";
$stmt = $db->query($query);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($orders as $order) {
    $emailController->sendReceipt($order['id']);

    // Update order status to avoid resending
    $updateQuery = "UPDATE orders SET status = 'receipt_sent' WHERE id = :orderId";
    $updateStmt = $db->prepare($updateQuery);
    $updateStmt->bindParam(':orderId', $order['id']);
    $updateStmt->execute();
}

echo "Receipts sent successfully!";
?>
