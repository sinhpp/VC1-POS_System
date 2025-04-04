<?php
require_once __DIR__ . '../../layout.php';
require_once __DIR__ . '/../../Database/Database.php';

// Retrieve and validate order data
$totalPrice = isset($_POST['totalPrice']) && is_numeric($_POST['totalPrice'])
    ? floatval($_POST['totalPrice'])
    : 0;
$order = isset($_POST['order']) ? json_decode($_POST['order'], true) : [];
// $discountRate = 0.06; // 6% discount
// $discountAmount = $totalPrice * $discountRate;
// $finalTotal = $totalPrice - $discountAmount;

// Define how many items to show initially
$itemsPerPage = 1;
$totalItems = count($order);
$showMore = $totalItems > $itemsPerPage;
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
                    <div class="btn-submit">
                        <button type="submit" name="print_receipt" class="btn-print">Order Completed</button>
                        <button type="submit" name="complete_order" class="btn-order">Print Receipt</button>

                    </div>
                    <!-- <div class="button">
                        <button type="submit" name="print_receipt" class="btn-print">Print Receipt</button>
                        <button type="button" name="complete_order" class="btn" onclick="completeOrder()">Complete Order</button>
                    </div> -->
                </form>
            </div>

            <div class="order-summary">
                <h2>Order Summary</h2>
                <?php if (empty($order)): ?>
                    <p>No items in your order.</p>
                <?php else: ?>
                    <div class="order-items">
                        <?php foreach (array_slice($order, 0, $itemsPerPage) as $product): ?>
                            <div class="order-item">
                                <img src="<?php echo htmlspecialchars($product['image']); ?>"
                                    alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <div>
                                    <p><strong><?php echo htmlspecialchars($product['name']); ?></strong></p>
                                    <p>Barcode: <?php echo htmlspecialchars($product['barcode']); ?></p>
                                    <p>Price: $<?php echo htmlspecialchars($product['price']); ?></p>
                                    <p>Quantity: <?php echo htmlspecialchars($product['quantity']); ?></p>
                                </div>
                                <span>$<?php echo number_format($product['price'] * $product['quantity'], 2); ?></span>
                            </div>
                        <?php endforeach; ?>
                        <?php foreach (array_slice($order, $itemsPerPage) as $product): ?>
                            <div class="order-item hidden">
                                <img src="<?php echo htmlspecialchars($product['image']); ?>"
                                    alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <div>
                                    <p><strong><?php echo htmlspecialchars($product['name']); ?></strong></p>
                                    <p>Barcode: <?php echo htmlspecialchars($product['barcode']); ?></p>
                                    <p>Price: $<?php echo htmlspecialchars($product['price']); ?></p>
                                    <p>Quantity: <?php echo htmlspecialchars($product['quantity']); ?></p>
                                </div>
                                <span>$<?php echo number_format($product['price'] * $product['quantity'], 2); ?></span>
                            </div>
                        <?php endforeach; ?>
                        <?php if ($showMore): ?>
                            <div class="toggle-buttons mt-2">
                                <button id="seeMoreBtn" class="btn btn-secondary">See More</button>
                                <button id="seeLessBtn" class="btn btn-secondary" style="display: none;">See Less</button>
                            </div>
                        <?php endif; ?>
                        <hr>
                        <div class="totals">
                            <p>Product Total: <span>$<?php echo number_format($totalPrice, 2); ?></span></p>
                            <p>Delivery Fee: <span>Free</span></p>
                        </div>
                        <hr>
                        <div class="total">
                            <h3>Total: <span>$<?php echo number_format($totalPrice, 2); ?></span></h3>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="/views/assets/js/checkout.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const seeMoreBtn = document.getElementById('seeMoreBtn');
            const seeLessBtn = document.getElementById('seeLessBtn');
            const hiddenItems = document.querySelectorAll('.order-item.hidden');

            if (seeMoreBtn && seeLessBtn) {
                seeMoreBtn.addEventListener('click', function() {
                    hiddenItems.forEach(item => item.classList.remove('hidden'));
                    seeMoreBtn.style.display = 'none';
                    seeLessBtn.style.display = 'inline-block';
                });

                seeLessBtn.addEventListener('click', function() {
                    hiddenItems.forEach(item => item.classList.add('hidden'));
                    seeLessBtn.style.display = 'none';
                    seeMoreBtn.style.display = 'inline-block';
                });
            }
        });
    </script>
</body>

</html>

<style>
    .hidden {
        display: none;
    }

    .toggle-buttons {
        display: flex;
        gap: 10px;
    }
</style>
