<!DOCTYPE html>
<html>
<head>
    <title>Order Receipt</title>
</head>
<body>
    <h2>Thank you for your purchase!</h2>
    <p>Order Number: <?= $orderData['order_id'] ?></p>
    <p>Customer Name: <?= $orderData['customer_name'] ?></p>
    <p>Total: $<?= $orderData['total'] ?></p>
    <p>We appreciate your business. If you have any questions, contact us.</p>
</body>
</html>
