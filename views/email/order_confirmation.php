<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        .order-details {
            margin: 20px 0;
        }
        .order-items {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .order-items th, .order-items td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .order-items th {
            background-color: #f5f5f5;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Confirmation</h1>
            <p>Thank you for your order!</p>
        </div>
        
        <div class="order-details">
            <h2>Order #<?php echo $order['id']; ?></h2>
            <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
            <p><strong>Name:</strong> <?php echo $order['customer_name']; ?></p>
            <p><strong>Email:</strong> <?php echo $order['customer_email']; ?></p>
            <p><strong>Shipping Address:</strong> <?php echo $order['customer_address']; ?></p>
        </div>
        
        <h3>Order Summary</h3>
        <table class="order-items">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $subtotal = 0;
                foreach ($order['items'] as $item): 
                    $itemTotal = $item['quantity'] * $item['price'];
                    $subtotal += $itemTotal;
                ?>
                <tr>
                    <td><?php echo $item['product_name']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td>$<?php echo number_format($itemTotal, 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"><strong>Subtotal</strong></td>
                    <td>$<?php echo number_format($subtotal, 2); ?></td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Tax</strong></td>
                    <td>$<?php echo number_format($order['tax'], 2); ?></td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td>$<?php echo number_format($subtotal + $order['tax'], 2); ?></td>
                </tr>
            </tfoot>
        </table>
        
        <p>You can download your invoice <a href="<?php echo 'https://example.com/order/' . $order['id'] . '/invoice'; ?>">here</a>.</p>
        
        <div class="footer">
            <p>If you have any questions, please contact our customer support at support@example.com</p>
            <p>&copy; <?php echo date('Y'); ?> Your Company. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

