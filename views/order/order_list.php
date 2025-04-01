<!-- Ensure layout file exists -->


<h2>Order List</h2>

<table border="1">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Total Amount</th>
            <th>Payment Status</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($completedOrders)): ?>
            <?php foreach ($completedOrders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order["id"]); ?></td>
                    <td><?= !empty($order["customer_name"]) ? htmlspecialchars($order["customer_name"]) : "Guest"; ?></td>
                    <td>$<?= number_format($order["total_amount"], 2); ?></td>
                    <td><?= htmlspecialchars(ucfirst($order["payment_status"])); ?></td>
                    <td><?= htmlspecialchars($order["created_at"]); ?></td>
                    <td>
                        <a href="view_order.php?id=<?= $order['id']; ?>">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No orders found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
