document.addEventListener('DOMContentLoaded', function() {
    const barcodeInput = document.getElementById('barcode-input');
    let barcodeBuffer = '';
    let lastKeyTime = Date.now();
    
    // Handle barcode scanner input (rapid keypresses + Enter)
    barcodeInput.addEventListener('keypress', function(e) {
        const now = Date.now();
        const timeDiff = now - lastKeyTime;
        
        if (timeDiff > 100) { // New scan started
            barcodeBuffer = '';
        }
        
        if (e.key === 'Enter') {
            processBarcode(barcodeBuffer);
            barcodeBuffer = '';
        } else {
            barcodeBuffer += e.key;
        }
        
        lastKeyTime = now;
    });
    
    function processBarcode(barcode) {
        fetch('/barcode/scan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ barcode: barcode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                addToCart(data.product);
            } else {
                showError(data.message);
            }
        });
    }
    
    function addToCart(product) {
        // Implement cart addition logic
    }
    
    function showError(message) {
        // Display error to user
    }
});