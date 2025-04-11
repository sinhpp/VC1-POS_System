<?php
var_dump($orders);

function getOrderHistory() {
     global $conn;
     $query = "SELECT id, created_at AS date, customer_name AS customerName, total_price AS total, status FROM orders ORDER BY created_at DESC";
     $result = $conn->query($query);

     if (!$result) {
          die("Database Error: " . $conn->error);
     }

     return $result->fecth_all(MYSQLI_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header img { max-width: 150px; }
        .details { display: flex; justify-content: space-between; margin: 20px 0; }
        .details div { width: 45%; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; }
        .summary { text-align: right; margin-top: 20px; }
        a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="header">
        <img src="https://www.awesomestore.com/assets/logo.png" alt="Awesome Store Logo">
        <h1>Order Confirmation</h1>
    </div>
    <main>
        <p>Dear <?php echo htmlspecialchars($customer->name); ?>,</p>
        <p>Thank you for your order! Your order has been received by Awesome Store and is being processed.</p>
        <p><strong>Order Number:</strong> #<?php echo htmlspecialchars($order->id); ?></p>
        <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order->date); ?></p>

        <!-- Delivery and Billing Details -->
        <div class="details">
            <div class="delivery">
                <h2>Delivery To:</h2>
                <p><?php echo htmlspecialchars($customer->name); ?></p>
                <p><?php echo htmlspecialchars($customer->address); ?></p>
            </div>
            <div class="billing">
                <h2>Billing Details:</h2>
                <p><?php echo htmlspecialchars($customer->name); ?></p>
                <p><?php echo htmlspecialchars($customer->address); ?></p>
            </div>
        </div>

        <!-- Product Table -->
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Each</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order->products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product->name) . ' (Variant: ' . htmlspecialchars($product->variant) . ', Code: ' . htmlspecialchars($product->code) . ')'; ?></td>
                    <td><?php echo htmlspecialchars($product->quantity); ?></td>
                    <td>$<?php echo number_format($product->price, 2); ?></td>
                    <td>$<?php echo number_format($product->quantity * $product->price, 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Order Summary -->
        <div class="summary">
            <p>Subtotal: $<?php echo number_format($order->subtotal, 2); ?></p>
            <p>Shipping: $<?php echo number_format($order->shipping, 2); ?></p>
            <p>Promo/Coupon Code: <?php echo htmlspecialchars($order->promoCode); ?> - $<?php echo number_format($order->discount, 2); ?></p>
            <p><strong>Total (NZD):</strong> $<?php echo number_format($order->total, 2); ?> (Includes GST of: $<?php echo number_format($order->gst, 2); ?>)</p>
        </div>

        <p>If you have any questions, please contact us at <a href="mailto:support@awesomestore.com">support@awesomestore.com</a>.</p>
        <p>Thank you for shopping with us!</p>
        <p>Awesome Store<br><a href="https://www.awesomestore.com">https://www.awesomestore.com</a></p>
        <p><a href="<?php echo htmlspecialchars($pdfDownloadLink); ?>">Download Invoice as PDF</a></p>
    </main>
</body>
</html>
