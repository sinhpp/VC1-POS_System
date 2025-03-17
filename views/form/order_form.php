?>
<form action="/place-order" method="POST">
    <input type="text" name="customer_name" placeholder="Name" required>
    <input type="email" name="customer_email" placeholder="Email" required>
    <input type="number" name="total_amount" placeholder="Total Amount" required>
    <button type="submit">Place Order</button>
</form>

// views/receipts/receipt.php
<?php
echo "<h1>Order Receipt</h1>";
echo "<p>Download your receipt: <a href='/views/receipts/invoice_$orderId.pdf'>Download PDF</a></p>";
?>