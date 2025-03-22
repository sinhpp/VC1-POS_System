<?php 
session_start(); // Start session at the very top

require_once __DIR__ . '/../layout.php';

?>
<div class="container-order mt-2">
    <div class="row">
        <!-- Product Scanner Section -->
        <div class="col-md-4">
            <div class="scanner-section"> 
                <h4>Product Scanner</h4>
                <form action="productDetails" method="POST">
                    <input type="text" id="barcodeInput" name="barcode" class="form-control mb-3" placeholder="Scan or enter barcode">
                    <button type="submit" name="scan" class="btn btn-primary">Scan Product</button>
                </form>
                <div id="productDetails" class="mt-3">
                    <?php
                    if (isset($_SESSION['product'])) {
                        $product = $_SESSION['product']; // Fix the variable name
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
                                    <form action='process.php' method='POST'>
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
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Order Page Section -->
        <div class="col-md-5 order-page">
            <h3>Order List</h3>
            <div class="order-table ">
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
                        <?php
                        if (!isset($_SESSION['order'])) {
                            $_SESSION['order'] = [];
                        }
                        foreach ($_SESSION['order'] as $index => $item) {
                            echo "
                                <tr>
                                    <td><img src='{$item['image']}' width='50' alt='{$item['name']}'></td>
                                    <td>{$item['name']}</td>
                                    <td>{$item['barcode']}</td>
                                    <td>\${$item['price']}</td>
                                    <td>{$item['quantity']}</td>
                                    <td>{$item['stock']}</td>
                                    <td>{$item['created_at']}</td>
                                    <td>
                                        <form action='checkout.php' method='POST'>
                                            <input type='hidden' name='index' value='$index'>
                                            <button type='submit' name='delete' class='btn btn-danger btn-sm'>Cancel</button>
                                        </form>
                                    </td>
                                </tr>
                            ";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <a href="../order/checkout.php" class="btn btn-info mt-3">Proceed to Checkout</a>
        </div>
    </div>
</div>
