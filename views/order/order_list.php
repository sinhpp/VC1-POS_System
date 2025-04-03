<!-- Ensure layout file exists -->
<?php
require_once __DIR__ . '../../layout.php';
?>


<h2 style="text-align: center; color: #4CAF50;">Order List</h2>

<table style="width: 65%; border-collapse: collapse; margin: 20px auto; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; overflow: hidden;">
    <thead style="background-color: #4CAF50; color: white;">
        <tr>
            <th style="padding: 15px; border: none;">Order ID</th>
            <th style="padding: 15px; border: none;">Customer Name</th>
            <th style="padding: 15px; border: none;">Total Amount</th>
            <th style="padding: 15px; border: none;">Payment Status</th>
            <th style="padding: 15px; border: none;">Created At</th>
            <th style="padding: 15px; border: none;">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($completedOrders)): ?>
            <?php foreach ($completedOrders as $order): ?>
                <tr style="background-color: <?= ($order["payment_status"] === 'completed') ? '#e8f5e9' : '#ffebee'; ?>; transition: background-color 0.3s;">
                    <td style="padding: 15px; border-bottom: 1px solid #ddd;"><?= htmlspecialchars($order["id"]); ?></td>
                    <td style="padding: 15px; border-bottom: 1px solid #ddd;"><?= !empty($order["customer_name"]) ? htmlspecialchars($order["customer_name"]) : "Guest"; ?></td>
                    <td style="padding: 15px; border-bottom: 1px solid #ddd;">$<?= number_format($order["total_amount"], 2); ?></td>
                    <td style="padding: 15px; border-bottom: 1px solid #ddd;"><?= htmlspecialchars(ucfirst($order["payment_status"])); ?></td>
                    <td style="padding: 15px; border-bottom: 1px solid #ddd;"><?= htmlspecialchars($order["created_at"]); ?></td>
                    <td style="padding: 15px; border-bottom: 1px solid #ddd;">
                        <a href="view_order.php?id=<?= $order['id']; ?>" style="color: #2196F3; text-decoration: none; font-weight: bold;">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px; border-bottom: 1px solid #ddd;">No orders found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<form action="/order/store" method="POST" class="mt-3">
    <input type="hidden" name="customer_id" value="<?php echo $customerId; ?>">
    <input type="hidden" name="order" value="<?php echo htmlentities(json_encode($_SESSION['order'])); ?>">
    <button type="submit" class="btn btn-info">Store Order</button>
</form>
<style>
    tbody tr:hover {
        background-color: #d1e7dd; /* Light green for hover effect */
    }
    table {
        margin-left:26% !important;
        margin-top: 10% !important; /* Adjusted margin for a modern look */
    }
</style>