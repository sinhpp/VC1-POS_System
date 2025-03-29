<div class="container">
    <div class="filter">
        <button class="active">All</button>
        <button>Bag</button>
        <button>Clothe</button>
        <button>Shoe</button>
        <button>Uniform</button>
        <button>Sport</button>
        <button>Toy</button>
    </div>

    <div class="grid" id="product-grid">
        <div class="product-card">
            <div class="img">
                <img src="/views/assets/images/shop_img/clothe.jpg" alt="Clothe" data-title="Clothe" data-price="$10">
            </div>
            <div class="title">
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
            <!-- <button class="add-to-cart">Add to Cart</button> -->
        </div>
        <div class="product-card">
            <div class="img">
                <img src="/views/assets/images/shop_img/bag.jpg" alt="Bag" data-title="Bag" data-price="$13">
            </div>
            <div class="title">
                <div class="product-title">Bag</div>
                <div class="product-price">$14</div>
                <label for="size">Size:</label>
                <select class="size-selector">
                    <option value="S">S</option>
                    <option value="M">M</option>
                </select>
                <button class="add-to-cart">Add to Cart</button>
            </div>
        </div>
        <div class="product-card">
            <div class="img">
                <img src="/views/assets/images/shop_img/hoodie.jpg" alt="Hoodie" data-title="Hoodie" data-price="$16">
            </div>
            <div class="title">
                <div class="product-title">Hoodie</div>
                <div class="product-price">$16</div>
                <label for="size">Size:</label>
                <select class="size-selector">
                    <option value="S">S</option>
                    <option value="M">M</option>
                </select>
                <button class="add-to-cart">Add to Cart</button>
            </div>
        </div>
        <div class="product-card">
            <div class="img">
                <img src="/views/assets/images/shop_img/dress.jpg" alt="Dress" data-title="Dress" data-price="$18">
            </div>
            <div class="title">
                <div class="product-title">Dress</div>
                <div class="product-price">$18</div>
                <label for="size">Size:</label>
                <select class="size-selector">
                    <option value="S">S</option>
                    <option value="M">M</option>
                </select>
                <button class="add-to-cart">Add to Cart</button>
            </div>
        </div>
        <div class="product-card">
            <div class="img">
                <img src="/views/assets/images/shop_img/shoe.jpg" alt="Shoe" data-title="Shoe" data-price="$15">
            </div>
            <div class="title">
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
            <div class="total-summary final-price">
                <span>Total Price:</span>
                <span id="final-price">$0</span>
            </div>

            <button class="checkout-button" id="checkout-button">Checkout</button>
        </div>
    </div>
</div>