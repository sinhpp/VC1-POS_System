
    document.addEventListener('DOMContentLoaded', function() {
        const barcodeInput = document.getElementById('barcodeInput');
        const scanButton = document.getElementById('scanButton');
        const orderProducts = document.getElementById('orderProducts');
        const totalPriceSpan = document.getElementById('totalPrice');
        const orderTableContainer = document.getElementById('orderTableContainer');
        const noItemsMessage = document.getElementById('noItemsMessage');
        const totalPriceContainer = document.getElementById('totalPriceContainer');

        // Focus input
        barcodeInput.focus();
        let isTyping = false;
        let isProcessing = false; // Prevent double processing

        // Detect manual typing
        barcodeInput.addEventListener('keydown', function(event) {
            if (event.key !== 'Enter' && event.key.length === 1) {
                isTyping = true;
                console.log('Typing detected');
            }
        });

        // Debounce function to prevent double calls
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        // Automatic scanning
        const debouncedAddProduct = debounce(function(barcode) {
            if (isProcessing) {
                console.log('Scan ignored: already processing');
                return;
            }
            isProcessing = true;
            console.log('Scanner detected, submitting:', barcode);
            addProductToOrder(barcode);
        }, 500); // Increased debounce time to 500ms

        barcodeInput.addEventListener('input', function() {
            const barcode = barcodeInput.value.trim();
            console.log('Raw input received:', barcode);
            if (barcode !== '' && !isTyping) {
                debouncedAddProduct(barcode);
            }
        });

        // Enter key for scanner or manual
        barcodeInput.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                const barcode = barcodeInput.value.trim();
                if (barcode !== '') {
                    if (isProcessing) {
                        console.log('Enter ignored: already processing');
                        return;
                    }
                    isProcessing = true;
                    console.log('Enter pressed, submitting:', barcode);
                    addProductToOrder(barcode);
                    isTyping = false;
                }
            }
        });

        // Manual Scan button
        scanButton.addEventListener('click', function() {
            const barcode = barcodeInput.value.trim();
            if (barcode !== '') {
                if (isProcessing) {
                    console.log('Scan button ignored: already processing');
                    return;
                }
                isProcessing = true;
                console.log('Scan button clicked, submitting:', barcode);
                addProductToOrder(barcode);
                isTyping = false;
            } else {
                Swal.fire('Error', 'Please enter a barcode!', 'error');
            }
        });

        // Add product with AJAX
        function addProductToOrder(barcode) {
            console.log('Sending to server:', barcode);
            fetch('/productDetails', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `scan=1&barcode=${encodeURIComponent(barcode)}`
            })
            .then(response => {
                if (!response.ok) throw new Error(`Server responded with ${response.status}`);
                return response.json();
            })
            .then(data => {
                console.log('Server response:', data);
                if (data.status === 'success') {
                    updateOrderList(data.order, data.updatedStock);
                    barcodeInput.value = '';
                    barcodeInput.focus();
                    isTyping = false;
                    isProcessing = false;
                } else {
                    Swal.fire('Error', data.message || 'Unknown error', 'error');
                    barcodeInput.value = '';
                    barcodeInput.focus();
                    isTyping = false;
                    isProcessing = false;
                }
            })
            .catch(error => {
                console.error('AJAX error:', error);
                Swal.fire('Error', 'Server issue: ' + error.message, 'error');
                barcodeInput.value = '';
                barcodeInput.focus();
                isTyping = false;
                isProcessing = false;
            });
        }

        // Update order list in real-time
        function updateOrderList(order, updatedStock = {}) {
            if (!orderProducts) {
                console.error('orderProducts element not found');
                Swal.fire('Error', 'UI error: Order list not found', 'error');
                return;
            }

            orderProducts.innerHTML = '';
            if (order.length === 0) {
                orderTableContainer.style.display = 'none';
                totalPriceContainer.style.display = 'none';
                noItemsMessage.style.display = 'block';
            } else {
                orderTableContainer.style.display = 'block';
                totalPriceContainer.style.display = 'block';
                noItemsMessage.style.display = 'none';

                let totalPrice = 0;
                order.forEach((product, index) => {
                    totalPrice += product.price * product.quantity;
                    const stockToShow = updatedStock[product.barcode] !== undefined ? updatedStock[product.barcode] : product.stock;
                    const row = document.createElement('tr');
                    row.dataset.index = index;
                    row.innerHTML = `
                        <td><img src="${product.image || ''}" width="50" alt="${product.name}"></td>
                        <td>${product.name}</td>
                        <td>${product.barcode}</td>
                        <td>$${Number(product.price).toFixed(2)}</td>
                        <td>${product.quantity}</td>
                        <td class="stock-value">${stockToShow}</td>
                        <td>${new Date().toISOString().split('T')[0]}</td>
                        <td><button class="btn btn-danger btn-sm delete-btn" data-index="${index}" data-barcode="${product.barcode}" data-quantity="${product.quantity}">Cancel</button></td>
                    `;
                    orderProducts.appendChild(row);
                });
                totalPriceSpan.textContent = totalPrice.toFixed(2);
                document.querySelector('input[name="order"]').value = JSON.stringify(order);
                document.querySelector('input[name="totalPrice"]').value = totalPrice;
            }
            attachDeleteListeners();
        }

        // Handle delete with stock restoration
        function attachDeleteListeners() {
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const index = this.dataset.index;
                    const barcode = this.dataset.barcode;
                    const quantity = parseInt(this.dataset.quantity);
                    console.log('Canceling:', { index, barcode, quantity });

                    fetch('/product/delete', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `delete=1&index=${index}&barcode=${barcode}&quantity=${quantity}`
                    })
                    .then(response => {
                        if (!response.ok) throw new Error(`Server responded with ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Delete response:', data);
                        if (data.status === 'success') {
                            updateOrderList(data.order, data.updatedStock);
                            Swal.fire('Canceled', 'Item removed and stock updated!', 'success');
                        } else {
                            Swal.fire('Error', data.message || 'Unknown error', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Delete AJAX error:', error);
                        Swal.fire('Error', 'Cancel failed: ' + error.message, 'error');
                    });
                });
            });
        }
        attachDeleteListeners();

        // Keep focus
        setInterval(() => {
            if (document.activeElement !== barcodeInput) {
                barcodeInput.focus();
                console.log('Refocused input');
            }
        }, 500);
    });
 