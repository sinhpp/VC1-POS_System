<!-- views/scan.php -->
<div class="scanner-container">
    <h1>Product Scanner</h1>
    
    <div class="scanner-options">
        <!-- Manual input -->
        <input type="text" id="barcode-input" placeholder="Enter barcode manually" autofocus>
        
        <!-- Camera scanning -->
        <button id="scan-button" class="btn btn-primary">Scan Barcode</button>
    </div>
    
    <!-- Camera preview -->
    <div id="camera-container" style="display: none;">
        <video id="camera-preview" playsinline></video>
        <p class="scan-help">Point camera at barcode to scan</p>
    </div>
    
    <!-- Error message -->
    <div id="error-message" class="alert alert-danger" style="display: none;"></div>
</div>

<!-- Include Instascan library for camera scanning -->
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script src="/assets/js/barcodeScanner.js"></script>