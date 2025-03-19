<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/views/assets/css/productCatheir.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="/views/assets/js/productCatheir.js" defer></script>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Products</h1>
            <div class="search-container">
                <input type="text" id="search" placeholder="Search by name or price">
                <i class="fas fa-search search-icon"></i>
                <i class="fas fa-shopping-cart cart-icon" title="View Cart" id="cart-icon">
                    <span class="cart-count" id="cart-count">0</span>
                </i>
            </div>
        </div>

        <div class="filter">
            <button class="active">All Products</button>
            <button>Shirt</button>
            <button>T-Shirt</button>
            <button>Outer</button>
            <button>Plants</button>
            <button>Accessories</button>
            <button>Footwear</button>
        </div>

        <div class="grid" id="product-grid">
            <div class="product-card">
                <img src="/views/assets/images/shop_img/clothe.jpg" alt="Basic Longsleeve" data-title="Clothe" data-price="$10">
                <div class="product-title">Clothe</div>
                <div class="product-price">$10</div>
                <label for="size">Size:</label>
                <select class="size-selector">
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                </select>
                <button class="add-to-cart">Add to Cart</button>
            </div>
            <div class="product-card">
                <img src="/views/assets/images/shop_img/bag.jpg" alt="Basic Checkered Flannel" data-title="Bag" data-price="$13">
                <div class="product-title">Bag</div>
                <div class="product-price">$14</div>
                <label for="size">Size:</label>
                <select class="size-selector">
                    <option value="S">S</option>
                    <option value="M">M</option>
                </select>
                <button class="add-to-cart">Add to Cart</button>
            </div>
            <div class="product-card">
                <img src="/views/assets/images/shop_img/hoodie.jpg" alt="Basic Checkered Flannel" data-title="Bag" data-price="$13">
                <div class="product-title">Hoodie</div>
                <div class="product-price">$16</div>
                <label for="size">Size:</label>
                <select class="size-selector">
                    <option value="S">S</option>
                    <option value="M">M</option>
                </select>
                <button class="add-to-cart">Add to Cart</button>
            </div>
            <div class="product-card">
                <img src="/views/assets/images/shop_img/dress.jpg" alt="Basic Checkered Flannel" data-title="Bag" data-price="$13">
                <div class="product-title">Dress</div>
                <div class="product-price">$18</div>
                <label for="size">Size:</label>
                <select class="size-selector">
                    <option value="S">S</option>
                    <option value="M">M</option>
                </select>
                <button class="add-to-cart">Add to Cart</button>
            </div>
            <div class="product-card">
                <img src="/views/assets/images/shop_img/shoe.jpg" alt="Basic Wool Shirt" data-title="Shoe" data-price="$15">
                <div class="product-title">Shoe</div>
                <div class="product-price">$15</div>
                <label for="size">Size:</label>
                <select class="size-selector">
                    <option value="38">38</option>
                    <option value="39">39</option>
                    <option value="40">40</option>
                    <option value="41">41</option>
                    <option value="42">42</option>
                    <option value="43">43</option>
                    <option value="44">44</option>
                    <option value="45">45</option>
                    <option value="46">46</option>
                </select>
                <button class="add-to-cart">Add to Cart</button>
            </div>
        </div>
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
            <div class="total-summary">
                <span>Total Price:</span>
                <span id="total-price">$0</span>
            </div>
            <div class="total-summary">
                <span>Discount:</span>
                <span class="discount">-$2.00</span>
            </div>
            <div class="total-summary final-price">
                <span>Final Price:</span>
                <span id="final-price">$0</span>
            </div>

            <button class="checkout-button" id="checkout-button">Checkout</button>
        </div>
    </div>
</body>

</html>