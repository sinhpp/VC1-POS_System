<!-- views/email/order_confirmation.php -->
<h1>Order Confirmation</h1>
<p>Thank you for your order!</p>
<p>Order ID: <?php echo $orderDetails['id']; ?></p>
<p>Order Date: <?php echo $orderDetails['order_date']; ?></p>
<p>Total Amount: $<?php echo $orderDetails['total_amount']; ?></p>
<!-- Add more details as needed -->