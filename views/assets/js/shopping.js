// Function to update the cart and calculate total price
function updateCart() {
    const cartItemsContainer = document.getElementById('cart-items');
    const itemTotalElement = document.getElementById('item-total');
    const discountsElement = document.getElementById('discounts');
    const totalPriceElement = document.getElementById('total-price');
    
    // Clear previous cart items
    cartItemsContainer.innerHTML = '';

    // Initialize total price and discount
    let totalPrice = 0;
    const discount = 3.00; // Example discount

    // Select all product rows from the shopping cart
    const productRows = document.querySelectorAll('.shopping-cart tbody tr');

    productRows.forEach(row => {
        const productName = row.cells[0].innerText.trim();
        const productSize = row.cells[1].innerText.trim(); // Original static size
        const productPrice = parseFloat(row.cells[3].innerText.replace('$', ''));
        const quantity = parseInt(row.cells[2].querySelector('input').value);
        const productImage = row.cells[0].querySelector('img').src; // Get the image source

        // New code: Check if a size selection dropdown exists and use its value if present
        let selectedSize = productSize; // Default to static size
        const sizeSelect = row.cells[1].querySelector('select.size-select');
        if (sizeSelect) {
            selectedSize = sizeSelect.value; // Override with selected size if dropdown exists
        }

        // Calculate item total
        const itemTotal = productPrice * quantity;
        totalPrice += itemTotal;

        // Create cart item display
        const cartItem = document.createElement('div');
        cartItem.classList.add('cart-item');

        // Create image element
        const img = document.createElement('img');
        img.src = productImage;
        img.alt = productName;
        img.style.maxWidth = '50px'; // Set image size
        img.style.marginRight = '10px'; // Spacing

        // Set inner text with product details showing only the selected size
        cartItem.innerText = `${productName} (${selectedSize}, x${quantity}) - $${itemTotal.toFixed(2)}`;
        
        // Append image to cart item
        cartItem.prepend(img);

        // Append cart item to the container
        cartItemsContainer.appendChild(cartItem);
    });

    // Update total values
    itemTotalElement.innerText = `$${totalPrice.toFixed(2)}`;
    discountsElement.innerText = `-$${discount.toFixed(2)}`;
    totalPriceElement.innerText = `$${(totalPrice - discount).toFixed(2)}`;
}

// Call updateCart() whenever the quantity or product changes
document.querySelectorAll('.shopping-cart input[type="number"]').forEach(input => {
    input.addEventListener('change', updateCart);
});

// Call updateCart() on page load to initialize cart
document.addEventListener('DOMContentLoaded', updateCart);

// Function to remove a product from the cart
function removeProduct(row) {
    row.remove(); // Remove the row from the table
    updateCart(); // Update the cart totals
}

// Add event listeners to the "Cancel" buttons
document.querySelectorAll('.remove-cart').forEach(button => {
    button.addEventListener('click', function() {
        const row = this.closest('tr'); // Find the closest row
        removeProduct(row); // Remove the product
    });
});

// New code: Add event listener for size selection changes
document.querySelectorAll('.shopping-cart select.size-select').forEach(select => {
    select.addEventListener('change', updateCart);
});