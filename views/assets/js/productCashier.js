let cartItems = []; // Array to store cart items
const discountAmount = 0; // Fixed discount amount
const cartCountElement = document.getElementById('cart-count');
const cartModal = document.getElementById('cartModal');
const closeModal = document.querySelector('.close');
const cartItemsContainer = document.getElementById('cart-items');
const totalItemsElement = document.getElementById('total-items');
// const totalPriceElement = document.getElementById('total-price');
const finalPriceElement = document.getElementById('final-price');

const addToCartButtons = document.querySelectorAll('.add-to-cart');

function updateCartItems() {
    cartItemsContainer.innerHTML = ''; // Clear current items
    let totalPrice = 0; // Initialize total price

    cartItems.forEach((item, index) => {
        const cartItemDiv = document.createElement('div');
        cartItemDiv.classList.add('cart-item');

        const img = document.createElement('img');
        img.src = item.image;
        img.alt = item.title;

        const itemInfo = document.createElement('div');
        itemInfo.classList.add('cart-item-info');

        const title = document.createElement('span');
        title.classList.add('cart-item-title');
        title.textContent = item.title;

        const size = document.createElement('span');
        size.classList.add('cart-item-size');
        size.textContent = `Size: ${item.size}`;

        const price = document.createElement('span');
        price.textContent = `${item.price}$`;

        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'Cancel';
        deleteButton.classList.add('delete-button');
        deleteButton.addEventListener('click', function () {
            removeItemFromCart(index); // Call remove function
        });

        itemInfo.appendChild(title);
        itemInfo.appendChild(size);
        itemInfo.appendChild(price);
        cartItemDiv.appendChild(img);
        cartItemDiv.appendChild(itemInfo);
        cartItemDiv.appendChild(deleteButton);
        cartItemsContainer.appendChild(cartItemDiv);

        totalPrice += item.price; // Sum up the price
    });

    // totalPriceElement.textContent = `$${totalPrice.toFixed(2)}`; // Update total price in modal
    const finalPrice = totalPrice - discountAmount; // Calculate final price after discount
    finalPriceElement.textContent = `$${finalPrice > 0 ? finalPrice.toFixed(2) : 0.00}`; // Update final price
    totalItemsElement.textContent = cartItems.length; // Update total items count
    cartCountElement.textContent = cartItems.length; // Update cart icon count
}

function addToCart(productTitle, productImage, productPrice, selectedSize) {
    cartItems.push({ title: productTitle, image: productImage, price: productPrice, size: selectedSize }); // Add item to cart
    updateCartItems(); // Update cart items display
}

addToCartButtons.forEach(button => {
    button.addEventListener('click', function () {
        const productCard = this.parentElement;
        const productTitle = productCard.querySelector('.product-title').textContent;
        const productImage = productCard.querySelector('img').src;
        const productPrice = parseFloat(productCard.querySelector('.product-price').textContent.replace('$', ''));
        const selectedSize = productCard.querySelector('.size-selector').value; // Get selected size

        addToCart(productTitle, productImage, productPrice, selectedSize); // Add item and update cart
        alert(`Do you want to add ${productTitle} item (Size: ${selectedSize}) to your cart?`);
    });
});

document.getElementById('cart-icon').addEventListener('click', function () {
    // Show cart items in modal
    cartModal.style.display = 'block';
    updateCartItems(); // Update cart items display
});

closeModal.addEventListener('click', function () {
    cartModal.style.display = 'none'; // Close the modal
});

window.onclick = function (event) {
    if (event.target == cartModal) {
        cartModal.style.display = 'none'; // Close the modal if clicked outside
    }
};

function removeItemFromCart(index) {
    cartItems.splice(index, 1); // Remove item from cart
    updateCartItems(); // Update cart items display
}

document.getElementById('search').addEventListener('input', function (event) {
    const searchTerm = event.target.value.toLowerCase();
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        const title = card.querySelector('.product-title').textContent.toLowerCase();
        const price = card.querySelector('.product-price').textContent.replace('$', '');
        card.style.display = title.includes(searchTerm) || price.includes(searchTerm) ? '' : 'none';
    });
});

const filterButtons = document.querySelectorAll('.filter button');
filterButtons.forEach(button => {
    button.addEventListener('click', function () {
        filterButtons.forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
        // Implement filtering logic as needed
    });
});

// Checkout button functionality
document.getElementById('checkout-button').addEventListener('click', function () {
    if (cartItems.length > 0) {
        alert('Would you like to proceed to checkout now?');
        // Here, you could redirect to a checkout page or perform any other action
    } else {
        alert('Your cart is empty. Please add items to cart before checking out.');
    }
});