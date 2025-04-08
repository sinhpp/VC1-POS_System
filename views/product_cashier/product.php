<div class="container">
    <div class="filter">
        <button class="category-btn active" data-category="all">All Products</button>
        <?php if (isset($categories) && !empty($categories)): ?>
            <?php foreach ($categories as $category): ?>
                <button class="category-btn" data-category="<?= htmlspecialchars($category['name']) ?>"><?= htmlspecialchars($category['name']) ?></button>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="grid" id="product-grid">
        <?php if (isset($products) && !empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card" data-category="<?= htmlspecialchars($product['category']) ?>">
                    <img src="/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" 
                         data-title="<?= htmlspecialchars($product['name']) ?>" 
                         data-price="$<?= number_format($product['price'], 2) ?>">
                    <div class="product-title"><?= htmlspecialchars($product['name']) ?></div>
                    <div class="product-price">$<?= number_format($product['price'], 2) ?></div>
                    
                    <?php if ($product['stock'] > 0): ?>
                        <label for="size">Size:</label>
                        <select class="size-selector">
                            <?php if (!empty($product['size'])): ?>
                                <option value="<?= htmlspecialchars($product['size']) ?>"><?= htmlspecialchars($product['size']) ?></option>
                            <?php else: ?>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                            <?php endif; ?>
                        </select>
                        <button class="add-to-cart" data-id="<?= $product['id'] ?>" data-barcode="<?= $product['barcode'] ?>">Add to Cart</button>
                    <?php else: ?>
                        <div class="out-of-stock">Out of Stock</div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-products">No products available</div>
        <?php endif; ?>
    </div>

    <!-- Modal for Cart -->
    <div id="cartModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Shopping Cart</h2>
            <div id="cart-items"></div>

            <div class="total-summary">
                <span>Total Items:</span>
                <span id="total-items">0</span>
            </div>
            <div class="total-summary final-price">
                <span>Total Price:</span>
                <span id="final-price">$0</span>
            </div>

            <button class="checkout-button" id="checkout-button">Checkout</button>
        </div>
    </div>
</div>

<style>
/* Styles for the product cashier view */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.filter {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
}

.filter button {
    padding: 8px 16px;
    background-color: #f0f0f0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.filter button.active {
    background-color: #007bff;
    color: white;
}

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

.product-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    transition: transform 0.3s;
    background-color: white;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.product-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 4px;
    margin-bottom: 10px;
}

.product-title {
    font-weight: bold;
    margin-bottom: 5px;
}

.product-price {
    color: #007bff;
    margin-bottom: 10px;
    font-weight: bold;
}

.size-selector {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border-radius: 4px;
    border: 1px solid #ddd;
}

.add-to-cart {
    width: 100%;
    padding: 8px 0;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.add-to-cart:hover {
    background-color: #218838;
}

.out-of-stock {
    color: #dc3545;
    font-weight: bold;
    margin-top: 10px;
}

/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: white;
    margin: 10% auto;
    padding: 20px;
    border-radius: 8px;
    width: 80%;
    max-width: 600px;
    position: relative;
}

.close {
    position: absolute;
    right: 20px;
    top: 10px;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.cart-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.item-details {
    display: flex;
    flex-direction: column;
}

.item-title {
    font-weight: bold;
}

.item-actions {
    display: flex;
    align-items: center;
    gap: 5px;
}

.item-actions button {
    width: 25px;
    height: 25px;
    border-radius: 50%;
    border: 1px solid #ddd;
    background-color: white;
    cursor: pointer;
}

.remove-item {
    color: #dc3545;
}

.total-summary {
    display: flex;
    justify-content: space-between;
    margin-top: 15px;
    font-weight: bold;
}

.final-price {
    font-size: 1.2em;
    color: #007bff;
}

.checkout-button {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 20px;
    transition: background-color 0.3s;
}

.checkout-button:hover {
    background-color: #0056b3;
}

.no-products {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px;
    background-color: #f8f9fa;
    border-radius: 8px;
    font-size: 18px;
    color: #6c757d;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }
    
    .modal-content {
        width: 95%;
        margin: 5% auto;
    }
}

@media (max-width: 480px) {
    .grid {
        grid-template-columns: 1fr;
    }
    
    .filter {
        flex-direction: column;
    }
    
    .filter button {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category filtering
    const categoryButtons = document.querySelectorAll('.category-btn');
    const productCards = document.querySelectorAll('.product-card');
    
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
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
        button.addEventListener('click', function() {
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
    closeModal.addEventListener('click', function() {
        cartModal.style.display = 'none';
    });
    
    // Close modal when clicking outside of it
    window.addEventListener('click', function(event) {
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
                button.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    cartItems[index].quantity += 1;
                    updateCartDisplay();
                });
            });
        
            // Decrease quantity
            document.querySelectorAll('.decrease-quantity').forEach(button => {
                button.addEventListener('click', function() {
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
                button.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    cartItems.splice(index, 1);
                    updateCartDisplay();
                });
            });
        }
    
        // Checkout button
        checkoutButton.addEventListener('click', function() {
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
                body: JSON.stringify({ items: orderData })
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
</script>