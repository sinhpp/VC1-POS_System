let cartItems = JSON.parse(localStorage.getItem('cartItems')) || []; // Load from localStorage or initialize empty array
const discountAmount = 0;
const cartCountElement = document.getElementById('cart-count');
const cartModal = document.getElementById('cartModal');
const closeModal = document.querySelector('.close');
const cartItemsContainer = document.getElementById('cart-items');
const totalItemsElement = document.getElementById('total-items');
const finalPriceElement = document.getElementById('final-price');
const addToCartButtons = document.querySelectorAll('.add-to-cart');

// update localStorage
function saveCartToLocalStorage() {
    localStorage.setItem('cartItems', JSON.stringify(cartItems));
}

// update cart display
function updateCartItems() {
    cartItemsContainer.innerHTML = ''; // Clear items
    let totalPrice = 0;

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
            removeItemFromCart(index);
        });

        itemInfo.appendChild(title);
        itemInfo.appendChild(size);
        itemInfo.appendChild(price);
        cartItemDiv.appendChild(img);
        cartItemDiv.appendChild(itemInfo);
        cartItemDiv.appendChild(deleteButton);
        cartItemsContainer.appendChild(cartItemDiv);

        totalPrice += item.price;
    });

    const finalPrice = totalPrice - discountAmount;
    finalPriceElement.textContent = `$${finalPrice > 0 ? finalPrice.toFixed(2) : 0.00}`;
    totalItemsElement.textContent = cartItems.length;
    cartCountElement.textContent = cartItems.length;

    // Save updated cart to localStorage
    saveCartToLocalStorage();
}

// add item to cart
function addToCart(productTitle, productImage, productPrice, selectedSize) {
    cartItems.push({ title: productTitle, image: productImage, price: productPrice, size: selectedSize });
    updateCartItems();
}

// Event listeners for "Add to Cart" buttons
addToCartButtons.forEach(button => {
    button.addEventListener('click', function () {
        const productCard = this.closest('.product-card');
        const productTitle = productCard.querySelector('.product-title').textContent;
        const productImage = productCard.querySelector('img').src;
        const productPrice = parseFloat(productCard.querySelector('.product-price').textContent.replace('$', ''));
        const selectedSize = productCard.querySelector('.size-selector').value;

        addToCart(productTitle, productImage, productPrice, selectedSize);
        alert(`Added ${productTitle} (Size: ${selectedSize}) to your cart!`);
    });
});

// Function to remove an item from the cart
function removeItemFromCart(index) {
    cartItems.splice(index, 1);
    updateCartItems();
}

// Load cart from localStorage when page loads
document.addEventListener('DOMContentLoaded', function () {
    updateCartItems();
});

// Open cart modal
document.getElementById('cart-icon').addEventListener('click', function () {
    cartModal.style.display = 'block';
    updateCartItems();
});


// Close cart modal
closeModal.addEventListener('click', function () {
    cartModal.style.display = 'none';
});

// Close cart when clicking outside
window.onclick = function (event) {
    if (event.target == cartModal) {
        cartModal.style.display = 'none';
    }
};

// Checkout button functionality
document.getElementById('checkout-button').addEventListener('click', function () {
    if (cartItems.length > 0) {
        alert('Proceeding to checkout...');
    } else {
        alert('Your cart is empty!');
    }
});
