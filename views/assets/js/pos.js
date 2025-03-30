document.addEventListener('DOMContentLoaded', function() {
    const barcodeInput = document.getElementById('barcode-input');
    const cartItems = document.getElementById('cart-items');
    let cart = [];
    
    barcodeInput.addEventListener('change', function() {
        const barcode = this.value.trim();
        if (!barcode) return;
        
        fetch('/barcode.php?action=scan&code=' + encodeURIComponent(barcode))
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    addToCart(data.product);
                } else {
                    showMessage(data.message, 'error');
                }
                this.value = '';
                this.focus();
            });
    });
    
    function addToCart(product) {
        // Cart management logic
    }
    
    function updateCartDisplay() {
        // Update cart UI
    }
});