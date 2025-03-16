<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clothing Shop</title>
    <link rel="stylesheet" href="/views/assets/css/payment.css">
    <script src="/views/assets/js/payment.js" defer></script>
</head>
<body>
    <div class="container">
        <div class="menu">
            <div class="item">
                <img src="/views/assets/images/pay_img/clothe.jpg" alt="Clothes" class="product-img" onclick="showImage('/views/assets/images/pay_img/clothe.jp')">
                <h3>Clothe</h3>
                <p>One of the most popular types of clothes.</p>
                <p class="price">$5.98</p>
                <button onclick="addToCart('Clothes', 5.98, '/views/assets/images/pay_img/clothe.jpg')">Add to cart</button>
            </div>
            <div class="item">
                <img src="/views/assets/images/pay_img/shoe.jpg" alt="Shoes" class="product-img" onclick="showImage('/views/assets/images/pay_img/shoe.jpg')">
                <h3>Shoe</h3>
                <p>One of the most popular types of shoes.</p>
                <p class="price">$5.98</p>
                <button onclick="addToCart('Shoe', 5.98, '/views/assets/images/pay_img/shoe.jpg')">Add to cart</button> <!-- Fixed image path -->
            </div>
            <div class="item">
                <img src="/views/assets/images/pay_img/bag.jpg" alt="Bag" class="product-img" onclick="showImage('/views/assets/images/pay_img/bag.jpg')">
                <h3>Bag</h3>
                <p>One of the most popular types of bag.</p>
                <p class="price">$5.98</p>
                <button onclick="addToCart('Bag', 5.98, '/views/assets/images/pay_img/bag.jpg')">Add to cart</button>
            </div>
        </div>
        <div class="cart">
            <h2>CART ORDER</h2>
            <div id="cart-items"></div> <!-- Cart items with images will be added here -->
            <div class="total">
                <span>Items:</span>
                <span id="item-total">$0.00</span>
            </div>
            <div class="total">
                <span>Discounts:</span>
                <span id="discounts">-$3.00</span>
            </div>
            <div class="total">
                <span>Total:</span>
                <span id="total-price">$0.00</span>
            </div>
            <button class="checkout" onclick="placeCheckout()">Go to checkout</button>
        </div>
    </div>

    <!-- Modal for viewing larger images -->
    <div id="image-modal" class="modal">
        <span class="close" onclick="closeImage()">&times;</span>
        <img class="modal-content" id="modal-image">
    </div>
</body>
</html>