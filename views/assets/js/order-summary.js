document.addEventListener('DOMContentLoaded', function() {
    // Focus on barcode input when the page loads
    const barcodeInput = document.getElementById('barcodeInput');
    barcodeInput.focus();

    // Handle scanner form submission
    const scannerForm = document.querySelector('form');
    scannerForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        const barcode = barcodeInput.value;

        fetch('scan_product.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `barcode=${encodeURIComponent(barcode)}`
        })
        .then(response => response.json())
        .then(data => {
            const productDetails = document.getElementById('productDetails');
            if (data.success) {
                // Display product details
                const product = data.product;
                productDetails.innerHTML = `
                    <div class="card product-card">
                        <img src="${product.image}" class="card-img-top" alt="${product.name}" width="30px">
                        <div class="card-body">
                            <h5 class="card-title">${product.name}</h5>
                            <p class="card-text">Barcode: ${product.barcode}</p>
                            <p class="card-text">Price: $${product.price}</p>
                            <p class="card-text">Stock: ${product.stock}</p>
                            <p class="card-text">Category: ${product.category}</p>
                            <p class="card-text">Created At: ${product.created_at}</p>
                            <button class="btn btn-success add-to-order" data-barcode="${product.barcode}">Add to Order</button>
                        </div>
                    </div>
                `;
            } else {
                // Display error message
                productDetails.innerHTML = `<p class="text-danger">${data.error}</p>`;
            }
        })
        .catch(() => {
            document.getElementById('productDetails').innerHTML = `<p class="text-danger">Error fetching product details.</p>`;
        });
    });

    // Handle "Add to Order" button clicks (delegated event)
    document.getElementById('productDetails').addEventListener('click', function(e) {
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
                    barcodeInput.value = ''; // Clear input
                    barcodeInput.focus(); // Refocus on input
                } else {
                    alert(data.error);
                }
            })
            .catch(() => alert('Error adding product to order.'));
        }
    });

    // Handle "Cancel" button clicks (delegated event)
    document.getElementById('orderItems').addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-danger')) {
            e.preventDefault(); // Prevent form submission
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
        const orderItems = document.getElementById('orderItems');
        let html = '';
        order.forEach((item, index) => {
            html += `
                <tr>
                    <td><img src="${item.image}" width="50" alt="${item.name}"></td>
                    <td>${item.name}</td>
                    <td>${item.barcode}</td>
                    <td>$${item.price}</td>
                    <td>${item.quantity}</td>
                    <td>${item.stock}</td>
                    <td>${item.created_at}</td>
                    <td>
                        <form action="checkout.php" method="POST">
                            <input type="hidden" name="index" value="${index}">
                            <button type="submit" name="delete" class="btn btn-danger btn-sm">Cancel</button>
                        </form>
                    </td>
                </tr>
            `;
        });
        orderItems.innerHTML = html;
    }
});