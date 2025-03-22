document.addEventListener('DOMContentLoaded', function() {
    const barcodeInput = document.getElementById('barcodeInput');
    const productDetailsContainer = document.getElementById('productDetailsContainer');
    const productDetails = document.getElementById('productDetails');
    const errorMessage = document.getElementById('errorMessage');
    const orderItems = document.getElementById('orderItems');

    barcodeInput.focus();

    // Handle scanner form submission
    document.getElementById('scannerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const barcode = barcodeInput.value.trim();
        if (!barcode) return;

        fetch('checkout.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `barcode=${encodeURIComponent(barcode)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const product = data.product;
                productDetails.innerHTML = `
                    <div class="card product-card">
                        <img src="${product.image}" class="card-img-top" alt="${product.name}" width="50">
                        <div class="card-body">
                            <h5 class="card-title">${product.name}</h5>
                            <p class="card-text">Barcode: ${product.barcode}</p>
                            <p class="card-text">Price: $${product.price}</p>
                            <p class="card-text">Stock: ${product.stock}</p>
                            <p class="card-text">Created At: ${product.created_at}</p>
                            <button class="btn btn-success add-to-order" data-barcode="${product.barcode}">Add to Order</button>
                        </div>
                    </div>
                `;
                productDetailsContainer.style.display = 'block'; // Show product details
                errorMessage.style.display = 'none'; // Hide error message if any
            } else {
                errorMessage.textContent = data.error;
                errorMessage.style.display = 'block';
                productDetailsContainer.style.display = 'none';
            }
        })
        .catch(() => {
            errorMessage.textContent = "Error fetching product details.";
            errorMessage.style.display = 'block';
            productDetailsContainer.style.display = 'none';
        });
    });

    // Handle "Add to Order" button click
    productDetailsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-to-order')) {
            const barcode = e.target.getAttribute('data-barcode');

            fetch('add_to_order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `barcode=${encodeURIComponent(barcode)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateOrderList(data.order);
                    barcodeInput.value = '';
                    barcodeInput.focus();
                    productDetailsContainer.style.display = 'none'; // Hide product details
                } else {
                    alert(data.error);
                }
            })
            .catch(() => alert('Error adding product to order.'));
        }
    });

    // Handle "Cancel" button clicks
    orderItems.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-danger')) {
            e.preventDefault();
            const form = e.target.closest('form');
            const index = form.querySelector('input[name="index"]').value;

            fetch('delete_from_order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `index=${encodeURIComponent(index)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateOrderList(data.order);
                } else {
                    alert(data.error);
                }
            })
            .catch(() => alert('Error deleting product from order.'));
        }
    });

    // Function to update the order list
    function updateOrderList(order) {
        orderItems.innerHTML = order.map((item, index) => `
            <tr>
                <td><img src="${item.image}" width="50" alt="${item.name}"></td>
                <td>${item.name}</td>
                <td>${item.barcode}</td>
                <td>$${item.price}</td>
                <td>${item.quantity}</td>
                <td>${item.stock}</td>
                <td>${item.created_at}</td>
                <td>
                    <form class="deleteForm">
                        <input type="hidden" name="index" value="${index}">
                        <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                    </form>
                </td>
            </tr>
        `).join('');
    }
});
