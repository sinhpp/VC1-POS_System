
<?php
require_once __DIR__ . '/../Controllers/OrderListController.php';

$controller = new OrderListController();
$orderId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$orderId) {
    $_SESSION['error'] = "Invalid order ID.";
    header("Location: /order/order_list");
    exit();
}

$orderModel = new ListModel();
$orderDetails = $orderModel->getOrderDetails($orderId);

if (empty($orderDetails)) {
    $_SESSION['error'] = "Order not found.";
    header("Location: /order/order_list");
    exit();
}

require_once __DIR__ . '/../layout.php';
?>

<div class="container mt-5">
    <h2 class="text-center text-success mb-4">Order Details - Order ID: <?= htmlspecialchars($orderId); ?></h2>

    <!-- Order Summary -->
    <div class="card shadow rounded mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Order Summary</h5>
        </div>
        <div class="card-body">
            <p><strong>Customer:</strong> <?= !empty($orderDetails[0]['customer_name']) ? htmlspecialchars($orderDetails[0]['customer_name']) : 'N/A'; ?></p>
            <p><strong>Total Amount:</strong> $<?= number_format($orderDetails[0]['total_amount'], 2); ?></p>
            <p><strong>Payment Status:</strong> 
                <span class="badge bg-<?= $orderDetails[0]['payment_status'] === 'paid' ? 'success' : 'danger'; ?>">
                    <?= ucfirst(htmlspecialchars($orderDetails[0]['payment_status'])); ?>
                </span>
            </p>
            <p><strong>Created At:</strong> <?= htmlspecialchars($orderDetails[0]['created_at']); ?></p>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card shadow rounded">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Ordered Products</h5>
        </div>
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-success">
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($orderDetails) && !empty($orderDetails[0]['product_id'])): ?>
                            <?php foreach ($orderDetails as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['product_name']); ?></td>
                                    <td><?= htmlspecialchars($item['quantity']); ?></td>
                                    <td>$<?= number_format($item['unit_price'], 2); ?></td>
                                    <td>$<?= number_format($item['total_price'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No products found for this order.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-4">
        <a href="/order/order_list" class="btn btn-outline-primary">Back to Order List</a>
    </div>
</div>
