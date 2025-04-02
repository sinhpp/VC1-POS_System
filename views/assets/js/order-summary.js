document.addEventListener('DOMContentLoaded', function() {
    const increaseButtons = document.querySelectorAll('.increase-quantity');
    const checkoutForm = document.querySelector("form[action='/views/order/checkout.php']");
const orderTableBody = document.querySelector("#orderproducts");

checkoutForm.addEventListener("submit", function (event) {
    if (orderTableBody.children.length === 0) {
        event.preventDefault(); // Prevent form submission

        const Toast = Swal.mixin({
            toast: true,
            position: "bottom-end", // ✅ Bottom-Right Position
            showConfirmButton: false,
            timer: 2000, // Closes in 2 seconds
            timerProgressBar: true,
            customClass: {
                popup: "small-toast"
            }
        });

        Toast.fire({
            icon: "warning",
            title: "Your order list is empty!"
        });
    }
});

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
    // order-summary.js

// Function to check for error messages and display alerts
function checkForErrors() {
    const errorElement = document.querySelector('.text-danger');
    if (errorElement) {
        const errorMessage = errorElement.textContent;
        if (errorMessage === "Product is out of stock!") {
            alert("Sorry, this product is out of stock!");
        }
        // Optional: Handle other errors
        else if (errorMessage === "Product not found!") {
            alert("Product not found! Please check the barcode and try again.");
        }
    }
}

// Run the error check when the page loads
document.addEventListener('DOMContentLoaded', function() {
    checkForErrors();

    // Optional: Auto-focus on barcode input
    const barcodeInput = document.getElementById('barcodeInput');
    if (barcodeInput) {
        barcodeInput.focus();
    }
});
});
