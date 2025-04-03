<?php
require_once __DIR__ . '../../layout.php';

$error = $_SESSION['error'] ?? '';
$order = $_SESSION['order'] ?? [];
$totalPrice = 0;
foreach ($order as $product) {
    $totalPrice += $product['price'] * $product['quantity'];
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<div class="container-order">
    <!-- Product Scanner Section -->
    <div class="scanner-section">
        <h4>Product Scanner</h4>
        <form action="/productDetails" method="POST" id="scanForm">
            <input type="text" id="barcodeInput" name="barcode" class="form-control" placeholder="Scan or enter barcode" required>
            <button type="submit" name="scan" value="1" class="btn btn-primary">Scan Product</button>
        </form>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p class='text-danger' style='margin-top: 15px;'>{$_SESSION['error']}</p>";
            unset($_SESSION['error']);
        }
        ?>
    </div>

    <!-- Order List Section -->
    <div class="order-page">
        <h3>Order List</h3>
        <?php if (empty($order)): ?>
            <p>No items in the order yet. Scan a product to add it!</p>
        <?php else: ?>
            <div class="order-table">
                <table id="orderTable">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Barcode</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Stock</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="orderproducts">
                        <?php foreach ($order as $index => $product): ?>
                            <tr>
                                <td><img src='<?php echo $product['image'] ?? ''; ?>' width='50' alt='<?php echo $product['name']; ?>'></td>
                                <td><?php echo $product['name']; ?></td>
                                <td><?php echo $product['barcode']; ?></td>
                                <td>$<?php echo number_format($product['price'], 2); ?></td>
                                <td><?php echo $product['quantity']; ?></td>
                                <td class="stock-value"><?php echo $product['stock']; ?></td>
                                <td><?php echo $product['created_at'] ?? date('Y-m-d H:i:s'); ?></td>
                                <td>
                                    <form action='/product/delete' method='POST'>
                                        <input type='hidden' name='index' value='<?php echo $index; ?>'>
                                        <button type='submit' name='delete' class='btn btn-danger btn-sm'>Cancel</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 15px;">
                <p><strong>Total Price: $<?php echo number_format($totalPrice, 2); ?></strong></p>
                <form action="/views/order/checkout.php" method="POST">
                    <input type="hidden" name="order" value="<?php echo htmlentities(json_encode($order)); ?>">
                    <input type="hidden" name="totalPrice" value="<?php echo $totalPrice; ?>">
                    <button type="submit" class="btn btn-primary">Proceed to Checkout</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const scanForm = document.getElementById('scanForm');
    const barcodeInput = document.getElementById('barcodeInput');

    // Auto-focus on barcode input for scanner convenience
    barcodeInput.focus();

    scanForm.addEventListener('submit', function(event) {
        const barcode = barcodeInput.value.trim();
        if (!barcode) {
            event.preventDefault();
            alert('Please enter or scan a barcode!');
        }
    });
});
</script>