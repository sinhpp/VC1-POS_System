<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Controllers\ReceiptController;
use Models\User;

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $customerEmail = $_POST['customer_email'] ?? '';
    $customerName = $_POST['customer_name'] ?? '';
    $customerPhone = $_POST['customer_phone'] ?? '';
    $paymentMethod = $_POST['payment_method'] ?? 'cash';
    
    // Get cart items from session
    $cartItems = $_SESSION['cart'] ?? [];
    
    if (empty($cartItems)) {
        // Redirect back to cart with error
        header('Location: /product_cashier/cart.php?error=empty_cart');
        exit;
    }
    
    // Calculate totals
    $subtotal = 0;
    $items = [];
    
    foreach ($cartItems as $item) {
        $subtotal += $item['price'] * $item['quantity'];
        $items[] = [
            'product_id' => $item['id'],
            'quantity' => $item['quantity'],
            'price' => $item['price']
        ];
    }
    
    // Calculate tax (e.g., 10%)
    $taxRate = 0.10;
    $tax = $subtotal * $taxRate;
    
    // Calculate total
    $total = $subtotal + $tax;
    
    // Create or update user if email is provided
    $userId = null;
    if (!empty($customerEmail)) {
        $user = new User();
        $userId = $user->createOrUpdate([
            'name' => $customerName,
            'email' => $customerEmail,
            'phone' => $customerPhone
        ]);
    }
    
    // Prepare purchase data
    $purchaseData = [
        'user_id' => $userId,
        'email' => $customerEmail,
        'payment_method' => $paymentMethod,
        'items' => $items,
        'subtotal' => $subtotal,
        'tax' => $tax,
        'total' => $total
    ];
    
    // Process purchase and send receipt
    $receiptController = new ReceiptController();
    $result = $receiptController->processPurchase($purchaseData);
    
    if ($result['success']) {
        // Clear cart
        unset($_SESSION['cart']);
        
        // Redirect to success page
        header('Location: /product_cashier/success.php?order_id=' . $result['order_id']);
        exit;
    } else {
        // Redirect back to cart with error
        header('Location: /product_cashier/cart.php?error=checkout_failed');
        exit;
    }
}

// Get cart items from session
$cartItems = $_SESSION['cart'] ?? [];
$itemCount = count($cartItems);

// Calculate totals
$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

// Calculate tax (e.g., 10%)
$taxRate = 0.10;
$tax = $subtotal * $taxRate;

// Calculate total
$total = $subtotal + $tax;

// Include header
include __DIR__ . '/../layouts/header.php';
?>

<div class="container py-4">
    <h1>Checkout</h1>
    
    <?php if (empty($cartItems)): ?>
    <div class="alert alert-warning">
        Your cart is empty. <a href="/products/index.php">Continue shopping</a>
    </div>
    <?php else: ?>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Order Summary</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td><?php echo $item['name']; ?></td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                <td>$<?php echo number_format($subtotal, 2); ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Tax (<?php echo $taxRate * 100; ?>%):</strong></td>
                                <td>$<?php echo number_format($tax, 2); ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Customer Information</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="">
                        <div class="mb-3">
                            <label for="customer_email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="customer_email" name="customer_email" required>
                            <small class="text-muted">Receipt will be sent to this email</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name">
                        </div>
                        
                        <div class="mb-3">
                            <label for="customer_phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="customer_phone" name="customer_phone">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_cash" value="cash" checked>
                                <label class="form-check-label" for="payment_cash">Cash</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_card" value="card">
                                <label class="form-check-label" for="payment_card">Credit/Debit Card</label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Complete Purchase</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <?php endif; ?>
</div>

<?php
// Include footer
include __DIR__ . '/../layouts/footer.php';
?>

