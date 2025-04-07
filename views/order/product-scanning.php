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
            <?php if (empty($order)): ?>
                <p>No items in the order yet. Scan or type a barcode to add it!</p>
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
                                        <button class="btn btn-danger btn-sm delete-btn" data-index="<?php echo $index; ?>">Cancel</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 15px;">
                    <p><strong>Total Price: $<span id="totalPrice"><?php echo number_format($totalPrice, 2); ?></span></strong></p>
                    <form action="/views/order/checkout.php" method="POST" id="checkoutForm">
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
        const barcodeInput = document.getElementById('barcodeInput');
        const scanButton = document.getElementById('scanButton');
        const orderProducts = document.getElementById('orderProducts');
        const totalPriceSpan = document.getElementById('totalPrice');

        // Focus input
        barcodeInput.focus();

        // Track typing vs scanning
        let isTyping = false;
        barcodeInput.addEventListener('keydown', function(event) {
            if (event.key !== 'Enter') {
                isTyping = true; // User is manually typing
            }
        });

        // Automatic scanning (scanner input)
        let timeout;
        barcodeInput.addEventListener('input', function() {
            const barcode = barcodeInput.value.trim();
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                if (barcode !== '' && barcode === barcodeInput.value.trim() && !isTyping) {
                    console.log('Scanner detected, adding:', barcode);
                    addProductToOrder(barcode);
                }
            }, 200); // 200ms to catch scanner input
        });

        // Handle Enter (scanner or manual)
        barcodeInput.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                const barcode = barcodeInput.value.trim();
                if (barcode !== '') {
                    console.log('Enter pressed, adding:', barcode);
                    addProductToOrder(barcode);
                    isTyping = false; // Reset after Enter
                }
            }
        });

        // Manual Scan button
        scanButton.addEventListener('click', function() {
            const barcode = barcodeInput.value.trim();
            if (barcode !== '') {
                console.log('Scan button clicked, adding:', barcode);
                addProductToOrder(barcode);
                isTyping = false; // Reset after manual scan
            } else {
                Swal.fire('Error', 'Please enter a barcode!', 'error');
            }
        });

        // AJAX to add product
        function addProductToOrder(barcode) {
            fetch('/productDetails', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `scan=1&barcode=${encodeURIComponent(barcode)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    updateOrderList(data.order);
                    barcodeInput.value = '';
                    barcodeInput.focus();
                } else {
                    Swal.fire('Error', data.message, 'error');
                    barcodeInput.value = '';
                    barcodeInput.focus();
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                Swal.fire('Error', 'Something went wrong!', 'error');
                barcodeInput.value = '';
                barcodeInput.focus();
            });
        }

        // Update order list dynamically
        function updateOrderList(order) {
            orderProducts.innerHTML = '';
            let totalPrice = 0;
            order.forEach((product, index) => {
                totalPrice += product.price * product.quantity;
                const row = document.createElement('tr');
                row.dataset.index = index;
                row.innerHTML = `
                    <td><img src="${product.image || ''}" width="50" alt="${product.name}"></td>
                    <td>${product.name}</td>
                    <td>${product.barcode}</td>
                    <td>$${Number(product.price).toFixed(2)}</td>
                    <td>${product.quantity}</td>
                    <td class="stock-value">${product.stock}</td>
                    <td>${new Date().toISOString().split('T')[0]}</td>
                    <td><button class="btn btn-danger btn-sm delete-btn" data-index="${index}">Cancel</button></td>
                `;
                orderProducts.appendChild(row);
            });
            totalPriceSpan.textContent = totalPrice.toFixed(2);
            document.querySelector('input[name="order"]').value = JSON.stringify(order);
            document.querySelector('input[name="totalPrice"]').value = totalPrice;
            attachDeleteListeners();
        }

        // Handle delete buttons
        function attachDeleteListeners() {
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const index = this.dataset.index;
                    fetch('/product/delete', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `delete=1&index=${index}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            updateOrderList(data.order);
                        }
                    });
                });
            });
        }
        attachDeleteListeners();

        // Keep focus
        setInterval(() => {
            if (document.activeElement !== barcodeInput) {
                barcodeInput.focus();
            }
        }, 500);
    });
    </script>
</body>
</html>