<!-- views/dashboard/index.php -->
<?php include __DIR__ . '/../layouts/layout.php'; ?>
<h2>Order History</h2>
<form method="GET">
    <label>Filter by Status:</label>
    <select name="status">
        <option value="">All</option>
        <option value="Pending">Pending</option>
        <option value="Shipped">Shipped</option>
        <option value="Delivered">Delivered</option>
    </select>
    <button type="submit">Filter</button>
</form>
<table>
    <thead>
        <tr>
            <th><a href="?sort=id">Order ID</a></th>
            <th>Customer Name</th>
            <th><a href="?sort=order_date">Order Date</a></th>
            <th><a href="?sort=total_amount">Total Amount</a></th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><a href="/order/details?order_id=<?php echo $order['id']; ?>"><?php echo $order['id']; ?></a></td>
                <td><?php echo $order['customer_name']; ?></td>
                <td><?php echo $order['order_date']; ?></td>
                <td>$<?php echo $order['total_amount']; ?></td>
                <td><?php echo $order['status']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>