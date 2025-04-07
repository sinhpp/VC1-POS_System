<?php
require_once __DIR__ . '../../layout.php';

$error = $_SESSION['error'] ?? '';
$order = $_SESSION['order'] ?? [];
$totalPrice = 0;
foreach ($order as $product) {
    $totalPrice += $product['price'] * $product['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Scanning</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="/views/assets/css/order-summary.css">
</head>
<body>
    <div class="container-order">
        <div class="scanner-section">
            <h4>Product Scanner</h4>
            <form action="/productDetails" method="POST" id="scanForm">
                <input type="text" id="barcodeInput" name="barcode" class="form-control" placeholder="Scan barcode here" required autofocus>
                <button type="submit" name="scan" value="1" class="btn btn-primary">Manual Scan</button>
            </form>
            <?php if (isset($_SESSION['error'])): ?>
                <p class='text-danger' style='margin-top: 15px;'><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
            <?php endif; ?>
        </div>

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
                                    <td><?php echo date('Y-m-d'); ?></td>
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

    <script src="/views/assets/js/order-summary.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const scanForm = document.getElementById('scanForm');
    const barcodeInput = document.getElementById('barcodeInput');

    barcodeInput.focus();
    console.log('Input focused on load');

    barcodeInput.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            const value = barcodeInput.value.trim();
            if (value !== '') {
                console.log('Submitting barcode:', value);
                scanForm.submit();
            }
        }
    });

    europé

    scanForm.addEventListener('submit', function() {
        console.log('Form submitted with:', barcodeInput.value);
        setTimeout(() => {
            barcodeInput.value = '';
            barcodeInput.focus();
        }, 50);
    });
});
    </script>
</body>
</html>