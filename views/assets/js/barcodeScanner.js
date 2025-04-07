// assets/js/barcodeScanner.js
class BarcodeScanner {
    constructor() {
        this.barcodeInput = document.getElementById('barcode-input');
        this.scanButton = document.getElementById('scan-button');
        this.cameraContainer = document.getElementById('camera-container');
        this.errorMessage = document.getElementById('error-message');
        this.scannerActive = false;
        
        this.init();
    }
    
    init() {
        // Keyboard scanning for desktop
        this.barcodeInput.addEventListener('keydown', this.handleBarcodeInput.bind(this));
        
        // Mobile camera scanning
        if (this.scanButton) {
            this.scanButton.addEventListener('click', this.toggleCamera.bind(this));
        }
    }
    
    handleBarcodeInput(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const barcode = this.barcodeInput.value.trim();
            
            if (barcode) {
                this.barcodeInput.value = '';
                this.processBarcode(barcode);
            }
        }
    }
    
    async toggleCamera() {
        if (!this.scannerActive) {
            try {
                this.scannerActive = true;
                this.scanButton.textContent = 'Stop Scanning';
                
                // Initialize barcode scanner
                const scanner = new Instascan.Scanner({
                    video: document.getElementById('camera-preview'),
                    mirror: false,
                    captureImage: false
                });
                
                scanner.addListener('scan', (barcode) => {
                    this.processBarcode(barcode);
                });
                
                const cameras = await Instascan.Camera.getCameras();
                if (cameras.length > 0) {
                    await scanner.start(cameras[0]);
                    this.currentScanner = scanner;
                    this.cameraContainer.style.display = 'block';
                } else {
                    throw new Error('No cameras found');
                }
            } catch (error) {
                this.errorMessage.textContent = error.message;
                this.errorMessage.style.display = 'block';
                this.scannerActive = false;
                this.scanButton.textContent = 'Scan Barcode';
            }
        } else {
            this.stopScanner();
        }
    }
    
    stopScanner() {
        if (this.currentScanner) {
            this.currentScanner.stop();
        }
        this.cameraContainer.style.display = 'none';
        this.scannerActive = false;
        this.scanButton.textContent = 'Scan Barcode';
    }
    
    async processBarcode(barcode) {
        try {
            // Stop scanner if active
            if (this.scannerActive) {
                this.stopScanner();
            }
            
            // Fetch product details
            const product = await this.fetchProductDetails(barcode);
            
            // Redirect to product page with barcode
            window.location.href = `/product.php?barcode=${encodeURIComponent(barcode)}`;
            
        } catch (error) {
            this.errorMessage.textContent = error.message || 'Invalid barcode. Please try again.';
            this.errorMessage.style.display = 'block';
            this.barcodeInput.focus();
        }
    }
    
    async fetchProductDetails(barcode) {
        const response = await fetch(`/Controllers/BarcodeController.php?barcode=${encodeURIComponent(barcode)}`);
        
        if (!response.ok) {
            throw new Error('Product not found');
        }
        
        return await response.json();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new BarcodeScanner();
});