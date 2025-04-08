document.addEventListener('DOMContentLoaded', function() {
    const scanForm = document.getElementById('scanForm');
    const barcodeInput = document.getElementById('barcodeInput');
    let scanBuffer = '';
    let lastKeyTime = Date.now();

    // Listen for keypresses anywhere on the page
    document.addEventListener('keydown', function(event) {
        const currentTime = Date.now();
        const timeDiff = currentTime - lastKeyTime;

        // If keys are pressed quickly (scanner-like behavior, < 50ms between keys)
        if (timeDiff < 50 && event.key !== 'Enter') {
            scanBuffer += event.key;
        } else if (event.key === 'Enter' && scanBuffer.length > 0) {
            // Scanner typically ends with Enter key
            barcodeInput.value = scanBuffer; // Populate the input field
            scanBuffer = ''; // Clear the buffer

            // Prevent default Enter behavior if a form is focused elsewhere
            event.preventDefault();

            // Validate and submit the form
            if (barcodeInput.value.trim() === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Input',
                    text: 'Please scan a valid barcode!',
                    confirmButtonText: 'OK'
                });
            } else {
                scanForm.submit(); // Submit the form programmatically
            }
        } else {
            // Reset buffer if too much time has passed (not a scanner)
            scanBuffer = '';
        }

        lastKeyTime = currentTime;
    });

    // Optional: Keep input focused if you still want manual entry
    barcodeInput.focus();

    // Handle manual form submission
    scanForm.addEventListener('submit', function(event) {
        if (!barcodeInput.value.trim()) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Input',
                text: 'Please enter or scan a barcode!',
                confirmButtonText: 'OK'
            });
        }
    });

    
});