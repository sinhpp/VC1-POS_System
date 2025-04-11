// let cartItems = JSON.parse(localStorage.getItem('cartItems')) || []; // Load from localStorage or initialize empty array
// const discountAmount = 0;
// const cartCountElement = document.getElementById('cart-count');
// const cartModal = document.getElementById('cartModal');
// const closeModal = document.querySelector('.close');
// const cartItemsContainer = document.getElementById('cart-items');
// const totalItemsElement = document.getElementById('total-items');
// const finalPriceElement = document.getElementById('final-price');
// const addToCartButtons = document.querySelectorAll('.add-to-cart');

// // update localStorage
// function saveCartToLocalStorage() {
//     localStorage.setItem('cartItems', JSON.stringify(cartItems));
// }

// // update cart display
// function updateCartItems() {
//     cartItemsContainer.innerHTML = ''; // Clear items
//     let totalPrice = 0;

//     cartItems.forEach((item, index) => {
//         const cartItemDiv = document.createElement('div');
//         cartItemDiv.classList.add('cart-item');

//         const img = document.createElement('img');
//         img.src = item.image;
//         img.alt = item.title;

//         const itemInfo = document.createElement('div');
//         itemInfo.classList.add('cart-item-info');

//         const title = document.createElement('span');
//         title.classList.add('cart-item-title');
//         title.textContent = item.title;

//         const size = document.createElement('span');
//         size.classList.add('cart-item-size');
//         size.textContent = `Size: ${item.size}`;

//         const price = document.createElement('span');
//         price.textContent = `${item.price}$`;

//         const deleteButton = document.createElement('button');
//         deleteButton.textContent = 'Cancel';
//         deleteButton.classList.add('delete-button');
//         deleteButton.addEventListener('click', function () {
//             removeItemFromCart(index);
//         });

//         itemInfo.appendChild(title);
//         itemInfo.appendChild(size);
//         itemInfo.appendChild(price);
//         cartItemDiv.appendChild(img);
//         cartItemDiv.appendChild(itemInfo);
//         cartItemDiv.appendChild(deleteButton);
//         cartItemsContainer.appendChild(cartItemDiv);

//         totalPrice += item.price;
//     });

//     const finalPrice = totalPrice - discountAmount;
//     finalPriceElement.textContent = `$${finalPrice > 0 ? finalPrice.toFixed(2) : 0.00}`;
//     totalItemsElement.textContent = cartItems.length;
//     cartCountElement.textContent = cartItems.length;

//     // Save updated cart to localStorage
//     saveCartToLocalStorage();
// }

// // add item to cart
// function addToCart(productTitle, productImage, productPrice, selectedSize) {
//     cartItems.push({ title: productTitle, image: productImage, price: productPrice, size: selectedSize });
//     updateCartItems();
// }

// // Event listeners for "Add to Cart" buttons
// addToCartButtons.forEach(button => {
//     button.addEventListener('click', function () {
//         const productCard = this.closest('.product-card');
//         const productTitle = productCard.querySelector('.product-title').textContent;
//         const productImage = productCard.querySelector('img').src;
//         const productPrice = parseFloat(productCard.querySelector('.product-price').textContent.replace('$', ''));
//         const selectedSize = productCard.querySelector('.size-selector').value;

//         addToCart(productTitle, productImage, productPrice, selectedSize);
//         alert(`Added ${productTitle} (Size: ${selectedSize}) to your cart!`);
//     });
// });

// // Function to remove an item from the cart
// function removeItemFromCart(index) {
//     cartItems.splice(index, 1);
//     updateCartItems();
// }

// // Load cart from localStorage when page loads
// document.addEventListener('DOMContentLoaded', function () {
//     updateCartItems();
// });

// // Open cart modal
// document.getElementById('cart-icon').addEventListener('click', function () {
//     cartModal.style.display = 'block';
//     updateCartItems();
// });


// // Close cart modal
// closeModal.addEventListener('click', function () {
//     cartModal.style.display = 'none';
// });

// // Close cart when clicking outside
// window.onclick = function (event) {
//     if (event.target == cartModal) {
//         cartModal.style.display = 'none';
//     }
// };

// // Checkout button functionality
// document.getElementById('checkout-button').addEventListener('click', function () {
//     if (cartItems.length > 0) {
//         alert('Proceeding to checkout...');
//     } else {
//         alert('Your cart is empty!');
//     }
// });

// function filterProducts() {
//     const input = document.getElementById('searchInput').value.toLowerCase();
//     const productCards = document.querySelectorAll('.product-card');

//     productCards.forEach(card => {
//         const title = card.querySelector('.product-title').textContent.toLowerCase();
//         const price = card.querySelector('.product-price').textContent.replace('$', '').toLowerCase();

//         if (title.includes(input) || price.includes(input)) {
//             card.style.display = ""; // Show the card
//         } else {
//             card.style.display = "none"; // Hide the card
//         }
//     });
// }

// document.getElementById('filter-toggle').addEventListener('click', function() {
//     const filter = document.getElementById('filter');
//     filter.style.display = (filter.style.display === 'block') ? 'none' : 'block';
// });



















document.addEventListener('DOMContentLoaded', function () {
    // Category filtering
    const categoryButtons = document.querySelectorAll('.category-btn');
    const productCards = document.querySelectorAll('.product-card');

    categoryButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Remove active class from all buttons
            categoryButtons.forEach(btn => btn.classList.remove('active'));

            // Add active class to clicked button
            this.classList.add('active');

            const category = this.getAttribute('data-category');

            // Show/hide products based on category
            productCards.forEach(card => {
                if (category === 'all' || card.getAttribute('data-category') === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Shopping cart functionality
    const cartItems = [];
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    const cartModal = document.getElementById('cartModal');
    const closeModal = document.querySelector('.close');
    const cartItemsContainer = document.getElementById('cart-items');
    const totalItemsElement = document.getElementById('total-items');
    const finalPriceElement = document.getElementById('final-price');
    const checkoutButton = document.getElementById('checkout-button');

    // Add to cart button click event
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function () {
            const card = this.closest('.product-card');
            const productId = this.getAttribute('data-id');
            const barcode = this.getAttribute('data-barcode');
            const title = card.querySelector('.product-title').textContent;
            const price = card.querySelector('.product-price').textContent;
            const size = card.querySelector('.size-selector').value;

            // Check if product already exists in cart
            const existingItemIndex = cartItems.findIndex(item =>
                item.id === productId && item.size === size
            );

            if (existingItemIndex > -1) {
                // Increment quantity if product already in cart
                cartItems[existingItemIndex].quantity += 1;
            } else {
                // Add new item to cart
                cartItems.push({
                    id: productId,
                    barcode: barcode,
                    title: title,
                    price: price,
                    size: size,
                    quantity: 1
                });
            }

            // Update cart display
            updateCartDisplay();

            // Show cart modal
            cartModal.style.display = 'block';
        });
    });

    // Close modal when clicking on X
    closeModal.addEventListener('click', function () {
        cartModal.style.display = 'none';
    });

    // Close modal when clicking outside of it
    window.addEventListener('click', function (event) {
        if (event.target === cartModal) {
            cartModal.style.display = 'none';
        }
    });

    // Update cart display
    function updateCartDisplay() {
        cartItemsContainer.innerHTML = '';
        let totalItems = 0;
        let totalPrice = 0;

        cartItems.forEach((item, index) => {
            const itemElement = document.createElement('div');
            itemElement.classList.add('cart-item');

            // Extract numeric price value
            const priceValue = parseFloat(item.price.replace('$', ''));
            const itemTotal = priceValue * item.quantity;

            totalItems += item.quantity;
            totalPrice += itemTotal;

            itemElement.innerHTML = `
            <div class="item-details">
                <span class="item-title">${item.title}</span>
                <span class="item-size">Size: ${item.size}</span>
                <span class="item-price">${item.price} x ${item.quantity}</span>
                <span class="item-total">$${itemTotal.toFixed(2)}</span>
            </div>
            <div class="item-actions">
                <button class="decrease-quantity" data-index="${index}">-</button>
                <span class="item-quantity">${item.quantity}</span>
                <button class="increase-quantity" data-index="${index}">+</button>
                <button class="remove-item" data-index="${index}">×</button>
            </div>
        `;

            cartItemsContainer.appendChild(itemElement);
        });

        totalItemsElement.textContent = totalItems;
        finalPriceElement.textContent = `$${totalPrice.toFixed(2)}`;

        // Add event listeners for cart item buttons
        addCartItemEventListeners();
    }

    // Add event listeners to cart item buttons
    function addCartItemEventListeners() {
        // Increase quantity
        document.querySelectorAll('.increase-quantity').forEach(button => {
            button.addEventListener('click', function () {
                const index = parseInt(this.getAttribute('data-index'));
                cartItems[index].quantity += 1;
                updateCartDisplay();
            });
        });

        // Decrease quantity
        document.querySelectorAll('.decrease-quantity').forEach(button => {
            button.addEventListener('click', function () {
                const index = parseInt(this.getAttribute('data-index'));
                if (cartItems[index].quantity > 1) {
                    cartItems[index].quantity -= 1;
                } else {
                    cartItems.splice(index, 1);
                }
                updateCartDisplay();
            });
        });

        // Remove item
        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', function () {
                const index = parseInt(this.getAttribute('data-index'));
                cartItems.splice(index, 1);
                updateCartDisplay();
            });
        });
    }

    // Checkout button
    checkoutButton.addEventListener('click', function () {
        if (cartItems.length === 0) {
            alert('Your cart is empty!');
            return;
        }

        // Prepare cart data for submission
        const orderData = cartItems.map(item => ({
            barcode: item.barcode,
            quantity: item.quantity,
            size: item.size
        }));

        // Send order to server
        fetch('/order/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                items: orderData
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Order placed successfully!');
                    cartItems.length = 0; // Clear cart
                    updateCartDisplay();
                    cartModal.style.display = 'none';

                    // Redirect to checkout page
                    window.location.href = '/product/checkout';
                } else {
                    alert('Error: ' + (data.message || 'Failed to process order'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing your order.');
            });
    });
});


// search product
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.querySelector('.search-button');
    const productCards = document.querySelectorAll('.product-card');

    searchInput.addEventListener('input', function() {
        const searchTerm = searchInput.value.toLowerCase();

        productCards.forEach(card => {
            const productName = card.querySelector('.product-title').textContent.toLowerCase();
            if (productName.includes(searchTerm)) {
                card.style.display = ''; // Show the product card
            } else {
                card.style.display = 'none'; // Hide the product card
            }
        });
    });
});


// category filter
document.querySelectorAll('.category-btn').forEach(button => {
    button.addEventListener('click', (event) => {
        // Remove active class from all buttons
        document.querySelectorAll('.category-btn').forEach(btn => btn.classList.remove('active'));
        // Add active class to the clicked button
        button.classList.add('active');

        // Get selected category
        const selectedCategory = button.dataset.category;

        // Filter products based on selected category
        const products = document.querySelectorAll('.product-card');
        products.forEach(product => {
            const productCategory = product.dataset.category;
            if (selectedCategory === 'all' || productCategory === selectedCategory) {
                product.style.display = 'block'; // Show product
            } else {
                product.style.display = 'none'; // Hide product
            }
        });
    });
});

