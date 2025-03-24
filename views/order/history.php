<table>
    <tr>
        <th>Order ID</th>
        <th>Customer Name</th>
        <th>Total</th>
        <th>Date</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($orders as $order): ?>
    <tr>
        <td><?= $order['id'] ?></td>
        <td><?= $order['customer_name'] ?></td>
        <td>$<?= $order['total'] ?></td>
        <td><?= $order['created_at'] ?></td>
        <td>
            <form action="/Controllers/OrderConttroller.php" method="POST">
                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                <button type="submit" name="send_receipt">Send Receipt</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
