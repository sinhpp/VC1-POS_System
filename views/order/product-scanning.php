<?php
require_once __DIR__ . '../../layout.php';
$error = $error ?? '';
$order = $order ?? ($_SESSION['order'] ?? []);
$totalPrice = 0; // Initialize total price
foreach ($order as $item) {
    $totalPrice += $item['price'] * $item['quantity']; // Sum up item totals
}
?>

<link rel="stylesheet" href="/views/assets/css/order-summary.css">

<div class="container-order">
    <div class="row">
        <!-- Product Scanner Section -->
        <div class="col-md-4">
            <div class="scanner-section">
                <h4>Product Scanner</h4>
                <form action="/productDetails" method="POST">
                    <input type="text" id="barcodeInput" name="barcode" class="form-control mb-3" placeholder="Scan or enter barcode">
                    <button type="submit" name="scan" value="1" class="btn btn-primary">Scan Product</button>
                </form>
                <div id="productDetails" class="mt-3">
                    <?php
                    // Removed product details display
                    if (isset($_SESSION['error'])) {
                        echo "<p class='text-danger'>{$_SESSION['error']}</p>";
                        unset($_SESSION['error']);
                    }
                    ?>
                    <p>Scan a barcode to see product details.</p>
                </div>
            </div>
        </div>

        <!-- Order Page Section -->
        <div class="col-md-5 order-page">
            <h3>Order List</h3>
            <div class="order-table">
                <table class="table table-striped" id="orderTable">
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
                    <tbody id="orderItems">
                        <?php foreach ($order as $index => $item): ?>
                            <tr>
                                <td><img src='<?php echo $item['image']; ?>' width='50' alt='<?php echo $item['name']; ?>'></td>
                                <td><?php echo $item['name']; ?></td>
                                <td><?php echo $item['barcode']; ?></td>
                                <td>$<?php echo $item['price']; ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo $item['stock']; ?></td>
                                <td><?php echo $item['created_at']; ?></td>
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
            <form action="/views/order/checkout.php" method="POST" class="mt-3">
                <input type="hidden" name="order" value="<?php echo htmlentities(json_encode($order)); ?>">
                <input type="hidden" name="totalPrice" value="<?php echo $totalPrice; ?>">
                <button type="submit" class="btn btn-info">Proceed to Checkout</button>
            </form>
        </div>
    </div>

    <script>
        // Check if there's an error message in the session
        <?php if (isset($_SESSION['error'])): ?>
            alert("<?php echo $_SESSION['error']; ?>");
            <?php unset($_SESSION['error']); // Clear the error after displaying ?>
        <?php endif; ?>
    </script>
</div>
<script src="/views/assets/js/order-summary.js"></script>