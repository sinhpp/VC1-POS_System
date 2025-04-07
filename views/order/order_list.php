<!-- Ensure layout file exists -->
<?php
require_once __DIR__ . '../../layout.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h2 class="text-center mb-0 py-3">
                        <i class="material-icons align-middle me-2">receipt_long</i>
                        Order Management
                    </h2>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th class="px-4 py-3">Order ID</th>
                                    <th class="px-4 py-3">Customer</th>
                                    <th class="px-4 py-3">Total Amount</th>
                                    <th class="px-4 py-3">Payment Status</th>
                                    <th class="px-4 py-3">Created At</th>
                                    <th class="px-4 py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($completedOrders)): ?>
                                    <?php foreach ($completedOrders as $order): ?>
                                        <tr class="order-row">
                                            <td class="px-4 py-3 fw-bold">#<?= htmlspecialchars($order["id"]); ?></td>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle me-2 bg-<?= !empty($order["customer_name"]) ? 'primary' : 'secondary' ?>">
                                                        <?= !empty($order["customer_name"]) ? strtoupper(substr($order["customer_name"], 0, 1)) : 'G' ?>
                                                    </div>
                                                    <span><?= !empty($order["customer_name"]) ? htmlspecialchars($order["customer_name"]) : "Guest"; ?></span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 fw-bold">$<?= number_format($order["total_amount"], 2); ?></td>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-<?= ($order["payment_status"] === 'completed') ? 'success' : 'danger'; ?> px-3 py-2">
                                                    <?= htmlspecialchars(ucfirst($order["payment_status"])); ?>
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-muted">
                                                <i class="material-icons align-middle me-1" style="font-size: 16px;">calendar_today</i>
                                                <?= htmlspecialchars($order["created_at"]); ?>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <a href="view_order.php?id=<?= $order['id']; ?>" class="btn btn-sm btn-primary">
                                                    <i class="material-icons align-middle">visibility</i>
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="material-icons" style="font-size: 48px; color: #ccc;">receipt</i>
                                                <p class="mt-3 text-muted">No orders found in the system.</p>
                                                <a href="/order/new" class="btn btn-primary mt-2">Create New Order</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <?php if (isset($_SESSION['order']) && !empty($_SESSION['order'])): ?>
                <div class="card mt-4 shadow-sm">
                    <div class="card-body">
                        <form action="/order/store" method="POST" class="d-flex justify-content-end">
                            <input type="hidden" name="customer_id" value="<?php echo isset($customerId) ? $customerId : ''; ?>">
                            <input type="hidden" name="order" value="<?php echo htmlentities(json_encode($_SESSION['order'])); ?>">
                            <button type="submit" class="btn btn-lg btn-success">
                                <i class="material-icons align-middle me-2">save</i>
                                Store Current Order
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
    }
    
    .avatar-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    
    .order-row {
        transition: all 0.2s ease;
    }
    
    .order-row:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        z-index: 10;
        position: relative;
    }
    
    .empty-state {
        padding: 30px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .table th {
        font-weight: 600;
        border-top: none;
    }
    
    .table td {
        vertical-align: middle;
    }
</style>
