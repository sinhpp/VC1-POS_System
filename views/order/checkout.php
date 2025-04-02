<?php
require_once __DIR__ . '../../layout.php';
require_once __DIR__ . '/../../Database/Database.php';

// Retrieve and validate order data
$totalPrice = isset($_POST['totalPrice']) && is_numeric($_POST['totalPrice'])
    ? floatval($_POST['totalPrice'])
    : 0;
$order = isset($_POST['order']) ? json_decode($_POST['order'], true) : [];
$discountRate = 0.06; // 6% discount
$discountAmount = $totalPrice * $discountRate;
$finalTotal = $totalPrice - $discountAmount;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="/views/assets/css/checkout.css">
</head>

<body>
    <div class="container-checkout">
        <div class="checkout">
            <div class="payment-form">
                <h2>Payment Method</h2>
                <form method="POST" action="/order/print-receipt">
                    <input type="hidden" name="checkout" value="1">
                    <input type="hidden" name="order" value="<?php echo htmlentities(json_encode($order)); ?>">
                    <input type="hidden" name="totalPrice" value="<?php echo $finalTotal; ?>">

                    <label for="customerName">Customer Name:</label>
                    <input type="text" id="customerName" name="customerName" required>

                    <label for="shippingAddress">Shipping Address:</label>
                    <input type="text" id="shippingAddress" name="shippingAddress" required>

                    <label for="billingAddress">Billing Address:</label>
                    <input type="text" id="billingAddress" name="billingAddress" required>

                    <label for="contactDetails">Contact Details:</label>
                    <input type="text" id="contactDetails" name="contactDetails" required pattern="[0-9]{9,12}"
                        title="Please enter a valid phone number (9-12 digits)">

                    <label for="paymentMethod">Payment Method:</label>
                    <select id="paymentMethod" name="paymentMethod">
                        <option value="cash">Cash</option>
                        <option value="card">Card (Mastercard/Visa)</option>
                        <option value="digital_wallet">Digital Wallet</option>
                    </select>

                    <div class="button">
                        <button type="submit" class="btn">Print Receipt</button>
                        <form action="/order/store" method="POST" class="mt-3">
    <input type="hidden" name="customer_id" value="<?php echo $customerId; ?>">
    <input type="hidden" name="order" value="<?php echo htmlentities(json_encode($_SESSION['order'])); ?>">
    <input type="hidden" name="action" value="store"> <!-- Hidden field for action -->
    <button type="submit" class="btn btn-info">Complete Order</button>
</form>

                    </div>
                </form>
            </div>

            <div class="order-summary">
                <h2>Order Summary</h2>
                <?php if (empty($order)): ?>
                    <p>No items in your order.</p>
                <?php else: ?>
                    <div class="order-items">
                        <?php foreach ($order as $product): ?>
                            <div class="order-item">
                                <img src="<?php echo htmlspecialchars($product['image']); ?>"
                                    alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <div>
                                    <p><strong><?php echo htmlspecialchars($product['name']); ?></strong></p>
                                    <p>Barcode: <?php echo htmlspecialchars($product['barcode']); ?></p>
                                    <p>Price: $<?php echo htmlspecialchars($product['price']); ?></p>
                                    <p>Stock: <?php echo htmlspecialchars($product['stock']); ?></p>
                                    <p>Category: <?php echo htmlspecialchars($product['category']); ?></p>
                                    <p>Created At: <?php echo htmlspecialchars($product['created_at']); ?></p>
                                    <p>Quantity: <?php echo htmlspecialchars($product['quantity']); ?></p>
                                </div>
                                <span>$<?php echo number_format($product['price'] * $product['quantity'], 2); ?></span>
                            </div>
                        <?php endforeach; ?>
                        <hr>
                        <div class="totals">
                            <p>Product Total: <span>$<?php echo number_format($totalPrice, 2); ?></span></p>
                            <p>Discount (6%): <span>($<?php echo number_format($discountAmount, 2); ?>)</span></p>
                            <p>Delivery Fee: <span>Free</span></p>
                        </div>
                        <hr>
                        <div class="total">
                            <h3>Total: <span>$<?php echo number_format($finalTotal, 2); ?></span></h3>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        function completeOrder() {
            // Redirect to the order list page
            window.location.href = '/order/order_list'; // Adjust the URL as needed
        }
    </script>
    <script src="/views/assets/js/checkout.js"></script>
</body>
<style>
    .button {
        display: flex;
        gap: 10px;
    }
</style>
</html>