<?php
$error = $error ?? '';
$order = $order ?? ($_SESSION['order'] ?? []);
$error = $error ?? '';
$order = $order ?? ($_SESSION['order'] ?? []);
$totalPrice = 0; // Initialize total price
foreach ($order as $item) {
    $totalPrice += $item['price'] * $item['quantity']; // Sum up item totals
}

?>
<link rel="stylesheet" href="/views/assets/css/order-summary.css">

<div class="container-order ">
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
                    if (isset($_SESSION['product'])) {
                        $product = $_SESSION['product'];
                        echo "
                                <div class='card product-card'>
                                    <img src='{$product['image']}' class='card-img-top' alt='{$product['name']}' width='30px'>
                                    <div class='card-body'>
                                        <h5 class='card-title'>{$product['name']}</h5>
                                        <p class='card-text'>Barcode: {$product['barcode']}</p>
                                        <p class='card-text'>Price: \${$product['price']}</p>
                                        <p class='card-text'>Stock: {$product['stock']}</p>
                                        <p class='card-text'>Category: {$product['category']}</p>
                                        <p class='card-text'>Created At: {$product['created_at']}</p>
                                        <form action='/order/add' method='POST'>
                                            <input type='hidden' name='barcode' value='{$product['barcode']}'>
                                            <button type='submit' name='add' class='btn btn-success'>Add to Order</button>
                                        </form>
                                    </div>
                                </div>
                            ";
                        unset($_SESSION['product']);
                    } elseif (isset($_SESSION['error'])) {
                        echo "<p class='text-danger'>{$_SESSION['error']}</p>";
                        unset($_SESSION['error']);
                    } else {
                        echo "<p>Scan a barcode to see product details.</p>";
                    }
                    ?>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
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
            <!-- Rest of the HTML -->
            <form action="/views/order/checkout.php" method="POST" class="mt-3">
                <input type="hidden" name="order" value="<?php echo htmlentities(json_encode($order)); ?>">
                <input type="hidden" name="totalPrice" value="<?php echo $totalPrice; ?>">
                <button type="submit" class="btn btn-info">Proceed to Checkout</button>
            </form>
        </div>
    </div>
</div>

<script src="/views//assets/js/order-summary.js"></script>