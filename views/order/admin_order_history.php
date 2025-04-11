<?php include './views/layouts/header.php'; ?>

<h2>Order History</h2>
<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($orders as $order): ?>
        <tr>
            <td><?= $order['id'] ?></td>
            <td><?= $order['customer_name'] ?></td>
            <td><?= number_format($order['total_amount'], 2) ?></td>
            <td><?= date('Y-m-d H:i', strtotime($order['order_date'])) ?></td>
            <td><?= $order['status'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include './views/layouts/footer.php'; ?>
