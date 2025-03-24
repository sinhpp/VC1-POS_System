<?php
session_start();

// Retrieve order data from POST or fallback to empty/default values
$totalPrice = $_POST['totalPrice'] ?? 0;
// Ensure $totalPrice is a valid number
$totalPrice = is_numeric($totalPrice) ? floatval($totalPrice) : 0;
$order = isset($_POST['order']) ? json_decode($_POST['order'], true) : [];
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
                <form method="POST" action="/product/process-checkout">
                    <input type="hidden" name="checkout" value="1">
                    <input type="hidden" name="order" value="<?php echo htmlentities(json_encode($order)); ?>">
                    <input type="hidden" name="totalPrice" value="<?php echo $totalPrice; ?>">
                    
                    <label for="customerName">Customer Name:</label>
                    <input type="text" id="customerName" name="customerName" required>

                    <label for="shippingAddress">Shipping Address:</label>
                    <input type="text" id="shippingAddress" name="shippingAddress" required>

                    <label for="billingAddress">Billing Address:</label>
                    <input type="text" id="billingAddress" name="billingAddress" required>

                    <label for="contactDetails">Contact Details:</label>
                    <input type="text" id="contactDetails" name="contactDetails" required>

                    <label for="paymentMethod">Payment Method:</label>
                    <select id="paymentMethod" name="paymentMethod">
                        <option value="Mastercard">Mastercard</option>
                        <option value="Visa">Visa</option>
                    </select>

                    <button type="submit" class="btn">Print</button>
                </form>
            </div>

            <div class="order-summary">
                <h2>Order Summary</h2>
                <?php if (empty($order)): ?>
                    <p>No items in your order.</p>
                <?php else: ?>
                    <div class="order-items">
                        <?php foreach ($order as $item): ?>
                            <div class="order-item">
                                <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                                <div>
                                    <p><strong><?php echo $item['name']; ?></strong></p>
                                    <p>Quantity: <?php echo $item['quantity']; ?></p>
                                </div>
                                <span>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                            </div>
                        <?php endforeach; ?>
                        <hr>
                        <div class="totals">
                            <p>Product Total: <span>$<?php echo number_format($totalPrice, 2); ?></span></p>
                            <p>Discount (6%): <span>($12.25)</span></p>
                            <p>Delivery Fee: <span>Free</span></p>
                        </div>
                        <hr>
                        <div class="total">
                            <h3>Total: <span>$<?php echo number_format($totalPrice - 12.25, 2); ?></span></h3>
                         
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>