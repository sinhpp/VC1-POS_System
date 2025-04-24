<?php require_once __DIR__ . '../../layout.php'; ?>
<link rel="stylesheet" href="/views/assets/css/order-list.css">
<!-- Include SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<div class="container-order-list mt-5">
    <h2 class="text-center text-success mb-4">Order List</h2>

    <!-- Controls -->
    <div class="d-flex justify-content-between mb-3">
        <input type="text" id="searchInput" class="form-control w-50" placeholder="Search by product name...">
        <div>
            <button id="sortPriceBtn" class="btn btn-outline-secondary me-2" onclick="sortByPrice()">Sort by Price ↓</button>
            <button class="btn btn-success" onclick="exportToExcel()">Export to Excel</button>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow rounded">
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="orderTable">
                    <thead class="table-success">
                        <tr>
                            <th>Order ID</th>
                            <th>Product Name</th>
                            <th>Total Amount</th>
                            <th>Payment Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($completedOrders)): ?>
                            <?php foreach ($completedOrders as $order): ?>
                                <tr>
                                    <td><?= htmlspecialchars($order["id"]); ?></td>
                                    <td><?= !empty($order["name"]) ? htmlspecialchars($order["name"]) : "No Product"; ?></td>
                                    <td><?= number_format($order["total_amount"], 2); ?></td>
                                    <td>
                                        <span class="badge bg-<?= $order["payment_status"] === 'completed' ? 'success' : 'danger'; ?>">
                                            <?= ucfirst(htmlspecialchars($order["payment_status"])); ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($order["created_at"]); ?></td>
                                    <td>
                                        <a href="view_order.php?id=<?= $order['id']; ?>" class="btn btn-sm btn-outline-primary me-2">View</a>
                                        <!-- Delete Button Form -->
                                        <form action="/order/delete" method="POST" class="delete-form" style="display:inline;">
                                            <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No orders found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Hidden Form -->
    <form action="/order/store" method="POST" class="mt-4">
        <input type="hidden" name="customer_id" value="<?= $customerId; ?>">
        <input type="hidden" name="order" value="<?= htmlentities(json_encode($_SESSION['order'])); ?>">
    </form>
</div>

<!-- Include SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="/views/assets/js/order-list.js"></script>


<script>
document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to delete this order?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form via AJAX
                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this)
                })
                .then(response => {
                    if (response.ok) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'The order has been deleted.',
                            icon: 'success',
                            timer: 1500, // Auto-close after 1.5 seconds
                            showConfirmButton: false
                        });
                        // Remove the row from the table
                        this.closest('tr').remove();
                    } else {
                        Swal.fire('Error!', 'Failed to delete the order.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'An error occurred while deleting.', 'error');
                });
            }
        });
    });
});
</script>
