<div class="container">
    <div class="div-container">
        <div class="info">
            <h2>Oders</h2>
            <p>Experience a seamless purchasing experience with our intuitive cashier interface</p>
            <div class="search-info">
                <h5 class="title">List Product</h5>
                <div class="buttons">
                    <input type="text" class="button search-button" placeholder="Search...">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <button class="button scan-button">Scan</button>
                    <i class="fa-solid fa-qrcode"></i>
                </div>
            </div>
        </div>
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
        margin-bottom: 80px;
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
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .product-card img {
        width: 94%;
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
        width: 90px;
        padding: 8px;
        margin-bottom: 10px;
        border-radius: 4px;
        border: 1px solid #ddd;
        height: 36px;
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
        background-color: rgba(0, 0, 0, 0.5);
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