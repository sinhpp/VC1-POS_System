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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="/views/assets/css/order-summary.css">

    <div class="container-order">
        <div class="scanner-section">
            <h4>Product Scanner</h4>
            <div class="input-group">
                <input type="text" id="barcodeInput" class="form-control" placeholder="Scan or type barcode here" autofocus>
                <button id="scanButton" class="btn btn-primary">Scan</button>
            </div>
            <?php if ($error): ?>
                <p class='text-danger' style='margin-top: 15px;'><?php echo $error; unset($_SESSION['error']); ?></p>
            <?php endif; ?>
        </div>

        <div class="order-page">
            <h3>Order List</h3>
            <div class="order-table" id="orderTableContainer" style="<?php echo empty($order) ? 'display: none;' : ''; ?>">
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
                    <tbody id="orderProducts">
                        <?php foreach ($order as $index => $product): ?>
                            <tr data-index="<?php echo $index; ?>">
                                <td><img src='<?php echo $product['image'] ?? ''; ?>' width='50' alt='<?php echo $product['name']; ?>'></td>
                                <td><?php echo $product['name']; ?></td>
                                <td><?php echo $product['barcode']; ?></td>
                                <td>$<?php echo number_format($product['price'], 2); ?></td>
                                <td><?php echo $product['quantity']; ?></td>
                                <td class="stock-value"><?php echo $product['stock']; ?></td>
                                <td><?php echo date('Y-m-d'); ?></td>
                                <td>
                                    <button class="btn btn-danger btn-sm delete-btn" data-index="<?php echo $index; ?>" data-barcode="<?php echo $product['barcode']; ?>" data-quantity="<?php echo $product['quantity']; ?>">Cancel</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <p id="noItemsMessage" style="<?php echo empty($order) ? '' : 'display: none;'; ?>">No items in the order yet. Scan or type a barcode to add it!</p>
            <div id="totalPriceContainer" style="margin-top: 15px; <?php echo empty($order) ? 'display: none;' : ''; ?>">
                <p><strong>Total Price: $<span id="totalPrice"><?php echo number_format($totalPrice, 2); ?></span></strong></p>
                <form action="/views/order/checkout.php" method="POST" id="checkoutForm">
                    <input type="hidden" name="order" value="<?php echo htmlentities(json_encode($order)); ?>">
                    <input type="hidden" name="totalPrice" value="<?php echo $totalPrice; ?>">
                    <button type="submit" class="btn btn-primary">Proceed to Checkout</button>
                </form>
            </div>
        </div>
    </div>

    
