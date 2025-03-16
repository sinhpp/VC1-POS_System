let cart = [];
let total = 0;
const discount = 3.00;

function addToCart(name, price, imgSrc) {
    const item = { name, price, quantity: 1, imgSrc };
    const existingItem = cart.find(i => i.name === name);

    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push(item);
    }

    total += price;
    updateCart();
}

function updateCart() {
    const cartItemsContainer = document.getElementById('cart-items');
    cartItemsContainer.innerHTML = '';

    cart.forEach(item => {
        const div = document.createElement('div');
        div.className = 'cart-item';
        div.innerHTML = `
            <img src="${item.imgSrc}" alt="${item.name}" style="width: 50px; border-radius: 5px;">
            <span>${item.name}</span>
            <span>$${item.price.toFixed(2)}</span>
            <div class="quantity-controls">
                <button onclick="changeQuantity('${item.name}', -1)">-</button>
                <span>${item.quantity}</span>
                <button onclick="changeQuantity('${item.name}', 1)">+</button>
            </div>
        `;
        cartItemsContainer.appendChild(div);
    });

    const itemTotal = total.toFixed(2);
    document.getElementById('item-total').textContent = `$${itemTotal}`;
    document.getElementById('total-price').textContent = `$${(total - discount).toFixed(2)}`;
}

function changeQuantity(name, change) {
    const item = cart.find(i => i.name === name);
    if (item) {
        item.quantity += change;
        if (item.quantity <= 0) {
            cart = cart.filter(i => i.name !== name);
        }
        total += (change * item.price);
        updateCart();
    }
}

function placeCheckout() {
    alert("Checkout placed!");
    cart = [];
    total = 0;
    updateCart();
}

// Modal functions for viewing larger images
function showImage(imgSrc) {
    const modal = document.getElementById('image-modal');
    const modalImg = document.getElementById('modal-image');
    modal.style.display = 'block';
    modalImg.src = imgSrc;
}

function closeImage() {
    const modal = document.getElementById('image-modal');
    modal.style.display = 'none';
}

// Close modal when clicking outside the image
window.onclick = function(event) {
    const modal = document.getElementById('image-modal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}