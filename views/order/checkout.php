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

// Simulate customer ID for now (replace with $_SESSION['user_id'] if using auth)
$customerId = 1;
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

                <button class="toggle-details" onclick="toggleOrderDetails()">
                    Hide Order Details <i class="fas fa-chevron-up"></i>
                </button>
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
                    <option value="card" <?php echo $paymentMethod === 'card' ? 'selected' : ''; ?>>Card (Mastercard/Visa)</option>
                    <option value="cash" <?php echo $paymentMethod === 'cash' ? 'selected' : ''; ?>>Cash</option>
                    <option value="digital_wallet" <?php echo $paymentMethod === 'digital_wallet' ? 'selected' : ''; ?>>Digital Wallet</option>
                </select>

                <!-- ✅ FORM START -->
                <form id="checkoutForm" action="/order/store" method="POST">
                    <!-- ✅ CUSTOMER ID HIDDEN INPUT -->
                    <input type="hidden" name="customer_id" value="<?php echo $customerId; ?>">

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
                <!-- ✅ FORM END -->

            </div>
        </div>
    </div>

    <script src="/views/assets/js/checkout.js"></script>
    <script>
        function setFormAction(actionType) {
            const form = document.getElementById('checkoutForm');
            if (actionType === 'print') {
                form.action = '/order/store';
                const hiddenAction = document.createElement('input');
                hiddenAction.type = 'hidden';
                hiddenAction.name = 'action';
                hiddenAction.value = 'print';
                form.appendChild(hiddenAction);
            }
        }

        document.getElementById('paymentMethod').addEventListener('change', function () {
            document.getElementById('paymentMethodInput').value = this.value;
        });

        function toggleOrderDetails() {
            const orderDetails = document.getElementById('orderDetails');
            orderDetails.classList.toggle('hidden');
            updateButtonStates();
        }

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

        document.addEventListener('DOMContentLoaded', updateButtonStates);
    </script>
</body>
</html>
