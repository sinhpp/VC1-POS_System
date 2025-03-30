<?php include __DIR__.'/../layouts/pos_layout.php'; ?>

<div class="pos-container">
    <div class="scanner-area">
        <h2>Scan Products</h2>
        <input type="text" id="barcode-input" placeholder="Scan barcode" autofocus>
        <div id="scan-message"></div>
    </div>
    
    <div class="cart-container">
        <div id="cart-items"></div>
        <div id="cart-totals"></div>
        <button id="checkout-btn" class="btn btn-primary">Checkout</button>
    </div>
</div>

<script src="/assets/js/pos.js"></script>