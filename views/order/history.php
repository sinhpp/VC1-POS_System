<!-- views/order/history.php -->
<?php include __DIR__ . '/../layouts/layout.php'; ?>
<h2>My Orders</h2>
<table>
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Order Date</th>
            <th>Total Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo $order['order_date']; ?></td>
                <td>$<?php echo $order['total_amount']; ?></td>
                <td>
                    <a href="/order/download-invoice?order_id=<?php echo $order['id']; ?>">Download Invoice</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>