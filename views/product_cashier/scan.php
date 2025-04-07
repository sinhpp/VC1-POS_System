<?php include('../layouts/main.php'); ?>

<div class="pos-container">
    <div class="barcode-scanner">
        <h2>Scan Products</h2>
        <input type="text" id="barcode-input" autofocus placeholder="Scan barcode...">
        <div id="product-result"></div>
    </div>
    
    <div class="cart-container">
        <?php include('cart.php'); ?>
    </div>
</div>

<script src="/assets/js/barcode.js"></script>