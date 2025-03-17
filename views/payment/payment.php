<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="/views/assets/css/payment.css">
    <script src="/views/assets/js/payment.js" defer></script>
</head>

<body>
    <div class="container">
        <main>
            <section class="shopping-cart">
                <h2>Shopping Cart</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Size</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <img src="/views/assets/images/pay_img/clothe.jpg" alt="Clothe"> Clothe
                            </td>
                            <td>
                                <select class="size-select">
                                    <option value="S">S</option>
                                    <option value="M" selected>M</option>
                                    <option value="L">L</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" value="1" min="1">
                            </td>
                            <td>$42.99</td>
                            <td class="remove-cart">Cancel</td>
                        </tr>
                        <tr>
                            <td>
                                <img src="/views/assets/images/pay_img/bag.jpg" alt="Bag"> Bag
                            </td>
                            <td>
                                <select class="size-select">
                                    <option value="S">S</option>
                                    <option value="M" selected>M</option>
                                    <option value="L">L</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" value="1" min="1">
                            </td>
                            <td>$32.99</td>
                            <td class="remove-cart">Cancel</td>
                        </tr>
                        <tr>
                            <td>
                                <img src="/views/assets/images/pay_img/shoe.jpg" alt="Shoe"> Shoe
                            </td>
                            <td>
                                <select class="size-select">
                                    <option value="S">S</option>
                                    <option value="M" selected>M</option>
                                    <option value="L">L</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" value="1" min="1">
                            </td>
                            <td>$29.99</td>
                            <td class="remove-cart">Cancel</td>
                        </tr>
                        <tr>
                            <td>
                                <img src="/views/assets/images/pay_img/clothe.jpg" alt="Clothe"> Clothe
                            </td>
                            <td>
                                <select class="size-select">
                                    <option value="S">S</option>
                                    <option value="M" selected>M</option>
                                    <option value="L">L</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" value="1" min="1">
                            </td>
                            <td>$42.99</td>
                            <td class="remove-cart">Cancel</td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </main>
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