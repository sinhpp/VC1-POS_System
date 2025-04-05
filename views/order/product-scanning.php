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
    <!-- <style>
        /* Optional: Add some basic styling */
        .container-order { display: flex; gap: 20px; }
        .scanner-section, .order-page { flex: 1; }
        .text-danger { color: red; }
    </style> -->
    <link rel="stylesheet" href="/views/assets/css/order-summary.css">
</head>
<body>
    <div class="container-order">
        <!-- Product Scanner Section -->
        <div class="scanner-section">
            <h4>Product Scanner</h4>
            <form action="/productDetails" method="POST" id="scanForm">
                <input type="text" id="barcodeInput" name="barcode" class="form-control" placeholder="Scan or enter barcode" required>
                <button type="submit" name="scan" value="1" class="btn btn-primary">Scan Product</button>
            </form>
            <?php if (isset($_SESSION['error'])): ?>
                <p class='text-danger' style='margin-top: 15px;'><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
            <?php endif; ?>
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
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Input',
                    text: 'Please enter or scan a barcode!',
                    confirmButtonText: 'OK'
                });
            }
        });

        <?php if (isset($_SESSION['product_not_found_alert'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Product Not Found',
                text: 'The product with barcode "<?php echo $_SESSION['product_not_found_alert']; ?>" was not found in the database!',
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['product_not_found_alert']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['out_of_stock_alert'])): ?>
            Swal.fire({
                icon: 'warning',
                title: 'Out of Stock',
                text: 'The product with barcode "<?php echo $_SESSION['out_of_stock_alert']; ?>" is out of stock!',
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['out_of_stock_alert']); ?>
        <?php endif; ?>
    });
    </script>
</body>
</html>