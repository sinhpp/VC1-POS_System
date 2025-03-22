<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <div class="row">
            <div class="col-md-7">
                <div class="card p-4 shadow-sm">
                    <h5 class="mb-4">Payment method</h5>
                    <form method="POST" action="/product/process-checkout">
                        <input type="hidden" name="checkout" value="1">
                        <div class="mb-3">
                            <label for="customerName" class="form-label">Customer Name:</label>
                            <input type="text" class="form-control" id="customerName" name="customerName" required>
                        </div>
                        <div class="mb-3">
                            <label for="shippingAddress" class="form-label">Shipping Address:</label>
                            <input type="text" class="form-control" id="shippingAddress" name="shippingAddress" required>
                        </div>
                        <div class="mb-3">
                            <label for="billingAddress" class="form-label">Billing Address:</label>
                            <input type="text" class="form-control" id="billingAddress" name="billingAddress" required>
                        </div>
                        <div class="mb-3">
                            <label for="contactDetails" class="form-label">Contact Details:</label>
                            <input type="text" class="form-control" id="contactDetails" name="contactDetails" required>
                        </div>
                        <div class="mb-3">
                            <label for="paymentMethod" class="form-label">Payment Method:</label>
                            <select class="form-select" id="paymentMethod" name="paymentMethod">
                                <option value="Mastercard">Mastercard</option>
                                <option value="Visa">Visa</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Order</button>
                    </form>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card p-4 shadow-sm">
                    <h5 class="mb-4">Order Summary</h5>
                    <?php foreach ($order as $item): ?>
                        <div class="d-flex align-items-center mb-3">
                            <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="me-3" width="50">
                            <div>
                                <p class="mb-0"><strong><?php echo $item['name']; ?></strong></p>
                                <p class="text-muted small">Quantity: <?php echo $item['quantity']; ?></p>
                            </div>
                            <span class="ms-auto">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                        </div>
                    <?php endforeach; ?>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <h6>Total</h6>
                        <h6>$<?php echo number_format($totalPrice, 2); ?></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>