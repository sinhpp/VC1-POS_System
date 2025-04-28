<?php require_once __DIR__ . '../../layout.php'; ?>
<link rel="stylesheet" href="/views/assets/css/order-list.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<div class="container-order-list mt-5">
    <h2 class="text-center  mb-4">Order List</h2>

    <!-- Controls -->
    <div class="d-flex justify-content-between mb-3">
        <input type="text" id="searchInput" class="form-control w-50" placeholder="Search by product name..." aria-label="Search orders">
        <div class="btn-group">
            <button id="sortPriceBtn" class="btn btn-outline-secondary me-2" onclick="sortByPrice()" aria-label="Sort by Price">Sort by Price ↓</button>
            <button class="btn btn-success" onclick="exportToExcel()" aria-label="Export to Excel">Export to Excel</button>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow rounded">
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="orderTable">
                    <thead class="table-success">
                        <tr>
                            <th scope="col">Order ID</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Total Amount</th>
                            <th scope="col">Payment Status</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($completedOrders)): ?>
                            <?php foreach ($completedOrders as $order): ?>
                                <tr>
                                    <td data-label="Order ID"><?= htmlspecialchars($order["id"]); ?></td>
                                    <td data-label="Product Name"><?= !empty($order["product_names"]) ? htmlspecialchars($order["product_names"]) : "No Product"; ?></td>
                                    <td data-label="Total Amount">$<?= number_format($order["total_amount"], 2); ?></td>
                                    <td data-label="Payment Status">
                                        <span class="badge bg-<?= $order["payment_status"] === 'paid' ? 'success' : 'danger'; ?>">
                                            <?= ucfirst(htmlspecialchars($order["payment_status"])); ?>
                                        </span>
                                    </td>
                                    <td data-label="Created At"><?= htmlspecialchars($order["created_at"]); ?></td>
                                    <td data-label="Actions">
                                        <!-- View Button with Modal Trigger -->
                                        <button type="button" class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#viewOrderModal-<?= $order['id']; ?>" aria-label="View order details">View</button>
                                        <!-- Delete Button Form -->
                                        <form action="/order/delete" method="POST" class="delete-form" style="display:inline;">
                                            <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Delete order">Delete</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal for Each Order -->
                                <div class="modal fade" id="viewOrderModal-<?= $order['id']; ?>" tabindex="-1" aria-labelledby="viewOrderModalLabel-<?= $order['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewOrderModalLabel-<?= $order['id']; ?>">Order #<?= htmlspecialchars($order["id"]); ?> Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Order Summary -->
                                                <p><strong>Customer Name:</strong> <?= htmlspecialchars($order["customer_name"] ?? 'N/A'); ?></p>
                                                <p><strong>Total Amount:</strong> $<?= number_format($order["total_amount"], 2); ?></p>
                                                <p><strong>Payment Status:</strong> <?= ucfirst(htmlspecialchars($order["payment_status"])); ?></p>
                                                <p><strong>Created At:</strong> <?= htmlspecialchars($order["created_at"]); ?></p>
                                                <hr>
                                                <!-- Product Details (To be populated via AJAX) -->
                                                <h6>Product Details</h6>
                                                <div id="productDetails-<?= $order['id']; ?>">
                                                    <p>Loading product details...</p>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="/views/assets/js/order-list.js"></script>

<script>
// Debounce function
function debounce(func, wait) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', debounce(function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#orderTable tbody tr');

    rows.forEach(row => {
        const productNames = row.cells[1].textContent.toLowerCase();
        row.style.display = productNames.includes(searchTerm) || searchTerm === '' ? '' : 'none';
    });
}, 300));

// Sort by Price functionality
let sortDirection = 'desc';
function sortByPrice() {
    const table = document.getElementById('orderTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    rows.sort((a, b) => {
        const priceA = parseFloat(a.cells[2].textContent.replace('$', ''));
        const priceB = parseFloat(b.cells[2].textContent.replace('$', ''));
        return sortDirection === 'desc' ? priceB - priceA : priceA - priceB;
    });

    sortDirection = sortDirection === 'desc' ? 'asc' : 'desc';
    document.getElementById('sortPriceBtn').textContent = `Sort by Price ${sortDirection === 'desc' ? '↓' : '↑'}`;

    const fragment = document.createDocumentFragment();
    rows.forEach(row => fragment.appendChild(row));
    tbody.appendChild(fragment);
}

// Export to Excel functionality
function exportToExcel() {
    const table = document.getElementById('orderTable');
    let csv = [];

    const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent);
    csv.push(headers.join(','));

    const rows = table.querySelectorAll('tbody tr');
    rows.forEach(row => {
        if (row.style.display !== 'none') {
            const cells = Array.from(row.querySelectorAll('td')).map(td => `"${td.textContent.replace(/"/g, '""')}"`);
            csv.push(cells.join(','));
        }
    });

    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'order_list.csv';
    link.click();
}

// Fetch Product Details via AJAX when Modal Opens
document.querySelectorAll('[id^="viewOrderModal-"]').forEach(modal => {
    modal.addEventListener('show.bs.modal', function (event) {
        const orderId = modal.id.split('-')[1];
        const productDetailsDiv = document.getElementById(`productDetails-${orderId}`);

        fetch(`/order/get_products?order_id=${orderId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Product details response:', data); // Debugging
                if (data.success && data.products && data.products.length > 0) {
                    let html = '<ul>';
                    data.products.forEach(product => {
                        html += `
                            <li>
                                <strong>Name:</strong> ${product.name || 'N/A'}<br>
                                <strong>Barcode:</strong> ${product.barcode || 'N/A'}<br>
                                <strong>Price:</strong> $${parseFloat(product.price || 0).toFixed(2)}<br>
                                <strong>Quantity Ordered:</strong> ${product.quantity || 'N/A'}<br>
                                <strong>Stock:</strong> ${product.stock || 'N/A'}<br>
                                <strong>Category:</strong> ${product.category || 'N/A'}<br>
                               
                                <strong>Description:</strong> ${product.descriptions || 'N/A'}<br>
                        
                                <strong>Gender:</strong> ${product.gender || 'N/A'}<br>
                    
                            </li>
                            <hr>
                        `;
                    });
                    html += '</ul>';
                    productDetailsDiv.innerHTML = html;
                } else {
                    productDetailsDiv.innerHTML = `<p>No products found for this order. ${data.message || 'Please ensure items are associated with this order.'}</p>`;
                }
            })
            .catch(error => {
                console.error('Error fetching product details:', error);
                productDetailsDiv.innerHTML = '<p>Failed to load product details. Please try again later.</p>';
            });
    });
});
</script>