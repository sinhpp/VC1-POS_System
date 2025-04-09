<?php
require_once __DIR__ . '../../layout.php';
require_once __DIR__ . '/../../Database/Database.php';

$totalPrice = isset($_POST['totalPrice']) && is_numeric($_POST['totalPrice'])
    ? floatval($_POST['totalPrice'])
    : 0;
$order = isset($_POST['order']) ? json_decode($_POST['order'], true) : [];

$subtotal = $totalPrice;
$shippingFee = 0;
$total = $subtotal + $shippingFee;

$paymentMethod = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : 'card';
$paymentMethodDisplay = $paymentMethod === 'card' ? 'Visa ending in 1234' : ucfirst($paymentMethod);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="/views/assets/css/checkout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        /* Optional: Style adjustments for the new button */
        .view-details {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        .view-details:hover {
            background-color: #0056b3;
        }

        .order-items.hidden {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container-checkout">
        <div class="checkout">
            <div class="order-summary">
                <h2>Order Summary</h2>
                <div class="totals">
                    <p>Subtotal: <span>$<?php echo number_format($subtotal, 2); ?></span></p>
                    <p>Shipping: <span>Free</span></p>
                </div>
                <div class="total">
                    <h3>Total: <span>$<?php echo number_format($total, 2); ?></span></h3>
                </div>
                <!-- Existing toggle button -->
                <button class="toggle-details" onclick="toggleOrderDetails()">
                    Hide Order Details <i class="fas fa-chevron-up"></i>
                </button>
                <!-- New View Order Details button -->
                <button class="view-details" onclick="toggleOrderDetails()">
                    View Order Details <i class="fas fa-eye"></i>
                </button>
                <div class="order-items visible" id="orderDetails">
                    <?php if (empty($order)): ?>
                        <p>No items in your order.</p>
                    <?php else: ?>
                        <?php foreach ($order as $product): ?>
                            <div class="order-item">
                                <span><?php echo htmlspecialchars($product['name']); ?> (<?php echo $product['quantity']; ?>x)</span>
                                <span>$<?php echo number_format($product['price'] * $product['quantity'], 2); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <label for="paymentMethod">Payment Method:</label>
                <select id="paymentMethod" name="paymentMethod">
                    <option value="card">Card (Mastercard/Visa)</option>
                    <option value="cash">Cash</option>
                    <option value="digital_wallet">Digital Wallet</option>
                </select>
                <form id="checkoutForm" action="/order/process-and-print" method="POST">
                    <input type="hidden" name="order" value="<?php echo htmlentities(json_encode($order)); ?>">
                    <input type="hidden" name="subtotal" value="<?php echo $subtotal; ?>">
                    <input type="hidden" name="total" value="<?php echo $total; ?>">
                    <input type="hidden" name="paymentMethod" id="paymentMethodInput" value="<?php echo $paymentMethod; ?>">
                    <div class="print-receipt d-flex"> 
                        <button type="submit" class="place-order">
                        <i class="fa-solid fa-print"></i> Print Receipt
                        </button>
                        <button class="place-order">
                            <i class="fas fa-shopping-cart"></i> Place Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="/views/assets/js/checkout.js"></script>
    <script>
        document.getElementById('paymentMethod').addEventListener('change', function() {
            document.getElementById('paymentMethodInput').value = this.value;
        });

        // Update button states based on visibility
        function updateButtonStates() {
            const orderDetails = document.getElementById('orderDetails');
            const toggleButton = document.querySelector('.toggle-details');
            const viewButton = document.querySelector('.view-details');

            if (orderDetails.classList.contains('hidden')) {
                toggleButton.style.display = 'none';
                viewButton.style.display = 'inline-block';
            } else {
                toggleButton.style.display = 'inline-block';
                viewButton.style.display = 'none';
            }
        }

        // Call updateButtonStates on page load
        document.addEventListener('DOMContentLoaded', updateButtonStates);
    </script>
</body>

</html>