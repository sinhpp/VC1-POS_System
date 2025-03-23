<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Details - #<?php echo $order['id']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .order-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .order-info-box {
            flex: 1;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
            margin-right: 15px;
        }
        .order-info-box:last-child {
            margin-right: 0;
        }
        .order-info-box h3 {
            margin-top: 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f8f8f8;
        }
        .totals {
            margin-left: auto;
            width: 300px;
        }
        .totals table {
            margin-top: 0;
        }
        .actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .btn-back {
            background-color: #607D8B;
        }
        .btn-invoice {
            background-color: #FF9800;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Order #<?php echo $order['id']; ?> Details</h1>
        
        <div class="order-info">
            <div class="order-info-box">
                <h3>Customer Information</h3>
                <p><strong>Name:</strong> <?php echo $order['customer_name']; ?></p>
                <p><strong>Email:</strong> <?php echo $order['customer_email']; ?></p>
                <p><strong>Address:</strong> <?php echo $order['customer_address']; ?></p>
            </div>
            
            <div class="order-info-box">
                <h3>Order Information</h3>
                <p><strong>Order Date:</strong> <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
                <p><strong>Order Status:</strong> Completed</p>
                <p><strong>Payment Method:</strong> Credit Card</p>
            </div>
        </div>
        
        <h2>Order Items</h2>
        <table>
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
        </table>
        
        <div class="totals">
            <table>
                <tr>
                    <td><strong>Subtotal</strong></td>
                    <td>$<?php echo number_format($subtotal, 2); ?></td>
                </tr>
                <tr>
                    <td><strong>Tax</strong></td>
                    <td>$<?php echo number_format($order['tax'], 2); ?></td>
                </tr>
                <tr>
                    <td><strong>Total</strong></td>
                    <td>$<?php echo number_format($subtotal + $order['tax'], 2); ?></td>
                </tr>
            </table>
        </div>
        
        <div class="actions">
            <a href="/admin/orders" class="btn btn-back">Back to Orders</a>
            <a href="/order/<?php echo $order['id']; ?>/invoice" class="btn btn-invoice">Download Invoice</a>
        </div>
    </div>
</body>
</html>

