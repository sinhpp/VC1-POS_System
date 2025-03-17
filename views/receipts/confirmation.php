<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="../assets/styles.css"> <!-- Assuming you have a CSS file -->
</head>
<body>
    <div class="confirmation-container">
        <div class="confirmation-message">
            <h1>Thank you for your purchase!</h1>
            <p>Your order will be processed within 24 hours during working days. We will notify you by email once your order has been shipped.</p>

            <h3>Billing Address</h3>
            <p><strong>Name:</strong> <?php echo $order['billing']['name']; ?></p>
            <p><strong>Address:</strong> <?php echo $order['billing']['address']; ?></p>
            <p><strong>Phone:</strong> <?php echo $order['billing']['phone']; ?></p>
            <p><strong>Email:</strong> <?php echo $order['billing']['email']; ?></p>

            <a href="/order/download/<?php echo $order['order_id']; ?>" class="track-button">Download Invoice as PDF</a>
            <a href="/track/<?php echo $order['order_id']; ?>" class="track-button">Track Your Order</a>
        </div>

        <div class="order-summary">
            <h2>Order Summary</h2>
            <p><strong>Date:</strong> <?php echo $order['date']; ?></p>
            <p><strong>Order Number:</strong> <?php echo $order['order_number']; ?></p>
            <p><strong>Payment Method:</strong> <?php echo $order['payment_method']; ?></p>
            <hr>
            <?php foreach ($order['items'] as $item): ?>
                <div class="order-item">
                    <p><?php echo $item['name']; ?></p>
                    <p>Pack: <?php echo $item['pack']; ?></p>
                    <p>Qty: <?php echo $item['quantity']; ?></p>
                    <p>$<?php echo number_format($item['price'], 2); ?></p>
                </div>
            <?php endforeach; ?>
            <hr>
            <p><strong>Subtotal:</strong> $<?php echo number_format($order['subtotal'], 2); ?></p>
            <p><strong>Shipping:</strong> $<?php echo number_format($order['shipping'], 2); ?></p>
            <p><strong>Tax:</strong> $<?php echo number_format($order['tax'], 2); ?></p>
            <h3><strong>Order Total:</strong> $<?php echo number_format($order['total'], 2); ?></h3>
        </div>
    </div>
</body>
</html>