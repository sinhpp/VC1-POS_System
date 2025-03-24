document.addEventListener('DOMContentLoaded', function() {
    const increaseButtons = document.querySelectorAll('.increase-quantity');
    
    increaseButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const index = this.getAttribute('data-index');
            const currentStock = parseInt(this.getAttribute('data-stock'));
            const row = this.closest('tr');
            const quantityCell = row.cells[4]; // Quantity column
            const stockCell = row.querySelector('.stock-value');
            
            if (currentStock > 0) {
                // Get current quantity
                let currentQuantity = parseInt(quantityCell.textContent.trim());
                
                // Update quantity and stock
                currentQuantity += 1;
                const newStock = currentStock - 1;
                
                // Update display
                quantityCell.firstChild.textContent = currentQuantity + ' ';
                stockCell.textContent = newStock;
                this.setAttribute('data-stock', newStock);
                
                // Send update to server
                updateOrder(index, currentQuantity, newStock);
            } else {
                alert('No more stock available!');
            }
        });
    });
    
    function updateOrder(index, quantity, stock) {
        fetch('/order/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `index=${index}&quantity=${quantity}&stock=${stock}`
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Failed to update order:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
});