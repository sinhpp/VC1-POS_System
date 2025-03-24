<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Models\Order;

// Check if user is logged in as admin
// ... authentication code here ...

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Get orders
$order = new Order();
$orders = $order->getAll($limit, $offset);
$totalOrders = $order->countAll();
$totalPages = ceil($totalOrders / $limit);

// Include header
include __DIR__ . '/../layouts/admin_header.php';
?>

<div class="container-fluid py-4">
    <h1 class="h3 mb-4">Order Management</h1>
    
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Orders</h5>
            <div>
                <a href="/dashboard/index.php" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="7" class="text-center">No orders found</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($orders as $orderItem): ?>
                            <tr>
                                <td><?php echo $orderItem['id']; ?></td>
                                <td>
                                    <?php if (!empty($orderItem['customer_name'])): ?>
                                        <?php echo $orderItem['customer_name']; ?>
                                        <small class="d-block text-muted"><?php echo $orderItem['customer_email']; ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">Guest</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('M d, Y h:i A', strtotime($orderItem['created_at'])); ?></td>
                                <td>$<?php echo number_format($orderItem['total'], 2); ?></td>
                                <td><?php echo ucfirst($orderItem['payment_method']); ?></td>
                                <td>
                                    <span class="badge bg-success"><?php echo ucfirst($orderItem['status']); ?></span>
                                </td>
                                <td>
                                    <a href="/dashboard/order_details.php?id=<?php echo $orderItem['id']; ?>" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="/receipts/download.php?id=<?php echo $orderItem['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-download"></i> Receipt
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                    <?php endfor; ?>
                    
                    <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
// Include footer
include __DIR__ . '/../layouts/admin_footer.php';
?>

