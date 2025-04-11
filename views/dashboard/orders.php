<?php
$orders = getOrderHistory();
var_dump($orders);
?>
<table>
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Customer Name</th>
            <th>Total</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
        <tr>
            <td>#<?php echo htmlspecialchars($order['id']); ?></td>
            <td><?php echo htmlspecialchars($order['date']); ?></td>
            <td><?php echo htmlspecialchars($order['customerName']); ?></td>
            <td>$<?php echo number_format($order['total'], 2); ?></td>
            <td><?php echo htmlspecialchars($order['status']); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
