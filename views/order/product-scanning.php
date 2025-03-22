<?php
// Ensure variables are defined before use
$error = $error ?? ''; // Set $error to an empty string if not defined
$order = $order ?? []; // Set $order to an empty array if not defined
?>
<div class="container-order mt-2">
    <div class="row">
        <!-- Product Scanner Section -->
        <div class="col-md-4">
            <div class="scanner-section">
                <h4>Product Scanner</h4>
                <form action="/productDetails" method="POST">
                    <input type="text" id="barcodeInput" name="barcode" class="form-control mb-3" placeholder="Scan or enter barcode">
                    <button type="submit" name="scan" class="btn btn-primary">Scan Product</button>
                </form>
                <div id="productDetails" class="mt-3">
                    <?php if (!empty($products)): ?>
                        <?php $product = $products[0]; ?>
                        <div class='card product-card'>
                            <img src='<?php echo $product['image']; ?>' class='card-img-top' alt='<?php echo $product['name']; ?>' width='30px'>
                            <div class='card-body'>
                                <h5 class='card-title'><?php echo $product['name']; ?></h5>
                                <p class='card-text'>Barcode: <?php echo $product['barcode']; ?></p>
                                <p class='card-text'>Price: $<?php echo $product['price']; ?></p>
                                <p class='card-text'>Stock: <?php echo $product['stock']; ?></p>
                                <p class='card-text'>Created At: <?php echo $product['created_at']; ?></p>
                                <form action='/order/add' method='POST'>
                                    <input type='hidden' name='barcode' value='<?php echo $product['barcode']; ?>'>
                                    <button type='submit' name='add' class='btn btn-success'>Add to Order</button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
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
            <a href="/checkout" class="btn btn-info mt-3">Proceed to Checkout</a>
        </div>
    </div>
</div>

                            