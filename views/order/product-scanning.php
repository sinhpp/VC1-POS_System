<?php
require_once __DIR__ . '../../layout.php';
$error = $error ?? '';
$order = $order ?? ($_SESSION['order'] ?? []);
$totalPrice = 0;
foreach ($order as $product) {
    $totalPrice += $product['price'] * $product['quantity'];
}
?>

<!DOCTYPE html>
<html>
<head>
    
</head>
<body>
    <div class="container-order">
        <!-- Product Scanner Section -->
        <div class="scanner-section">
            <h4>Product Scanner</h4>
            <form action="/productDetails" method="POST">
                <input type="text" id="barcodeInput" name="barcode" class="form-control" placeholder="Scan or enter barcode">
                <button type="submit" name="scan" value="1" class="btn btn-primary">Scan Product</button>
            </form>
            <div id="productDetails" style="margin-top: 15px;">
                <?php
                if (isset($_SESSION['error'])) {
                    echo "<p class='text-danger'>{$_SESSION['error']}</p>";
                    unset($_SESSION['error']);
                }
                ?>
                <p>Scan a barcode to see product details.</p>
            </div>
        </div>

        <!-- Order Page Section -->
        <div class="order-page">
            <h3>Order List</h3>
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
                                <td><img src='<?php echo $product['image']; ?>' width='50' alt='<?php echo $product['name']; ?>'></td>
                                <td><?php echo $product['name']; ?></td>
                                <td><?php echo $product['barcode']; ?></td>
                                <td>$<?php echo $product['price']; ?></td>
                                <td><?php echo $product['quantity']; ?></td>
                                <td><?php echo $product['stock']; ?></td>
                                <td><?php echo $product['created_at']; ?></td>
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
            <form action="/views/order/checkout.php" method="POST" style="margin-top: 15px;">
                <input type="hidden" name="order" value="<?php echo htmlentities(json_encode($order)); ?>">
                <input type="hidden" name="totalPrice" value="<?php echo $totalPrice; ?>">
                <button type="submit" class="btn btn-info">Proceed to Checkout</button>
            </form>
        </div>
    </div>
    
</div>
<script src="/views/assets/js/order-summary.js"></script>
