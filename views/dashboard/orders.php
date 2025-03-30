<?php
require_once 'Controllers/OrderControllers.php';

$orders = Order::getAllOrders();
?>

<h2>Order History</h2>
<table border="1">
    <tr>
        <th>Order ID</th>
        <th>User ID</th>
        <th>Total Amount</th>
        <th>Status</th>
        <th>Date</th>
    </tr>
    <?php foreach ($orders as $order): ?>
    <tr>
        <td><?= $order['id'] ?></td>
        <td><?= $order['user_id'] ?></td>
        <td>$<?= $order['total_amount'] ?></td>
        <td><?= $order['status'] ?></td>
        <td><?= $order['created_at'] ?></td>
    </tr>
    <td>
    <a href="/invoice/download/<?= $order['id'] ?>">Download Invoice</a>
    </td>
    <?php endforeach; ?>
</table>
