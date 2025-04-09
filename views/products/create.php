<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) : 
    // Get the category from URL parameter
    $selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Add Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Base styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            width: 60%;
            margin: 0 auto;
            padding: 2rem;
            position: relative;
            top: 10%;
            left: 10%;
        }
        
        h2 {
            font-size: 1.8rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        /* Form container styling */
        .grid-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .general-info {
            margin-top:10%;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        /* Form styling */
        #addProductForm {
            padding: 1.5rem;
        }
        
        .form-flex-container {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
        }
        
        .form-section {
            flex: 1;
            min-width: 300px;
        }
        
        .section-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #4b5563;
        }
        
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        .form-input:focus {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        }
        
        /* Toggle buttons styling */
        .toggle-button {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .toggle-button.active {
            background-color: #3b82f6;
            color: white;
        }
        
        /* Image preview */
        .image-preview {
            margin-top: 1rem;
            text-align: center;
        }
        
        .image-preview img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        /* Barcode container */
        .barcode-container {
            margin-top: 1rem;
            text-align: center;
            padding: 1rem;
            background-color: #f9fafb;
            border-radius: 0.5rem;
        }
        
        /* Action buttons */
        .form-actions {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
        
        .form-actions button, .form-actions a {
            padding: 0.65rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* Field visibility */
        .field-hidden {
            display: none;
        }
        
        /* Section toggle buttons */
        .section-toggle {
            display: flex;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }       
        .section-toggle button {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            background-color: #f1f5f9;
            color: #64748b;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            width: 500px;
        }
        
        .section-toggle button.active {
            background-color: #3b82f6;
            color: white;
        }
        
        /* Weight field styling */
        .weight-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .weight-container input {
            flex: 1;
        }
        
        .weight-container select {
            width: 100px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .form-flex-container {
                flex-direction: column;
                gap: 1.5rem;
            }
            
            .form-section {
                width: 100%;
            }
            
            .section-toggle {
                overflow-x: auto;
                white-space: nowrap;
                padding-bottom: 0.5rem;
            }
            
            .section-toggle button {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
            
            .form-actions {
                flex-direction: column;
                gap: 0.75rem;
            }
            
            .form-actions button, .form-actions a {
                width: 100%;
                justify-content: center;
            }
        }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            body {
                background-color: #1a1a1a;
                color: #e0e0e0;
            }
            
            .general-info {
                background-color: #2a2a2a;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            }
            
            .section-title {
                color: #e0e0e0;
                border-bottom-color: #3a3a3a;
            }
            
            .form-label {
                color: #c0c0c0;
            }
            
            .form-input {
                background-color: #333;
                border-color: #444;
                color: #e0e0e0;
            }
            
            .form-input:focus {
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.4);
            }
            
            .section-toggle button {
                background-color: #333;
                color: #c0c0c0;
            }
            
            .barcode-container {
                background-color: #333;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Add New Product</h2>
    <main class="grid-container">
        <section class="general-info">
            <!-- Section Toggle Buttons -->
            <div class="section-toggle">
                <button type="button" id="showProductInfo" class="active">
                    <i class="fas fa-info-circle mr-2"></i> Product Information
                </button>
                <button type="button" id="showAdditionalDetails">
                    <i class="fas fa-cog mr-2"></i> Additional Details
                </button>
            </div>
            
            <form id="addProductForm" action="/products/store" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= isset($product) ? htmlspecialchars($product['id']) : '' ?>">
                <!-- Add hidden input for category if it was selected -->
                <?php if ($selectedCategory): ?>
                    <input type="hidden" name="selected_category" value="<?= htmlspecialchars($selectedCategory) ?>">
                <?php endif; ?>

                <div class="form-flex-container">
                    <!-- Product Information Section -->
                    <div id="productInfoSection" class="form-section">
                        <h4 class="section-title">Product Information</h4>

                        <div class="mb-4">
                            <label for="productName1" class="form-label">Product Name</label>
                            <input id="productName1" type="text" placeholder="Enter product name" class="form-input" name="name" value="<?= isset($product) ? htmlspecialchars($product['name']) : '' ?>" required>
                        </div>

                        <!-- Size field - will be hidden for certain categories -->
                        <div id="sizeField" class="mb-4 <?= in_array($selectedCategory, ['toys', 'student', 'jewelry', 'makeup', 'other']) ? 'field-hidden' : '' ?>">
                            <label for="productSize" class="form-label">Size</label>
                            <select name="size" id="productSize" class="form-input" <?= in_array($selectedCategory, ['toys', 'student', 'jewelry', 'makeup', 'other']) ? '' : 'required' ?>>
                                <option value="">Select Size</option>
                                <option value="XS">XS</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="productStock1" class="form-label">Stock Quantity</label>
                            <input id="productStock1" type="number" placeholder="Enter stock quantity" class="form-input" name="stock" required min="0" step="1">
                        </div>

                        <!-- Weight field - new addition -->
                        <div class="mb-4">
                            <label for="productWeight" class="form-label">Weight</label>
                            <div class="weight-container">
                                <input id="productWeight" type="number" step="0.01" placeholder="Enter weight" class="form-input" name="weight" min="0">
                                <select name="weight_unit" class="form-input">
                                    <option value="g">Grams (g)</option>
                                    <option value="kg">Kilograms (kg)</option>
                                    <option value="oz">Ounces (oz)</option>
                                    <option value="lb">Pounds (lb)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Brand field - will be hidden for certain categories -->
                        <div id="brandField" class="mb-4 <?= in_array($selectedCategory, ['toys', 'student', 'jewelry', 'makeup', 'other']) ? 'field-hidden' : '' ?>">
                            <label for="productBrand1" class="form-label">Brand</label>
                            <input id="productBrand1" type="text" placeholder="Enter brand name" class="form-input" name="brand">
                        </div>

                        <div class="mb-4">
                            <label for="productPrice1" class="form-label">Price</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">$</span>
                                <input id="productPrice1" type="number" step="0.01" placeholder="0.00" class="form-input pl-8" name="price" required min="0">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="productCategory1" class="form-label">Category</label>
                            <select id="productCategory1" class="form-input" name="category" required>
                                <option value="">Select Category</option>
                                <option value="clothes" <?= $selectedCategory == 'clothes' ? 'selected' : '' ?>>Clothes</option>
                                <option value="bag" <?= $selectedCategory == 'bag' ? 'selected' : '' ?>>Bag</option>
                                <option value="shoes" <?= $selectedCategory == 'shoes' ? 'selected' : '' ?>>Shoes</option>
                                <option value="toys" <?= $selectedCategory == 'toys' ? 'selected' : '' ?>>Toys</option>
                                <option value="student" <?= $selectedCategory == 'student' ? 'selected' : '' ?>>Student Material</option>
                                <option value="jewelry" <?= $selectedCategory == 'jewelry' ? 'selected' : '' ?>>Jewelry</option>
                                <option value="makeup" <?= $selectedCategory == 'makeup' ? 'selected' : '' ?>>Make Up</option>
                                <option value="other" <?= $selectedCategory == 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Product Image</label>
                            <div class="flex space-x-2 mb-2">
                                <button type="button" id="toggleUrl1" class="toggle-button active">
                                    <i class="fas fa-link mr-1"></i> URL
                                </button>
                                <button type="button" id="toggleFile1" class="toggle-button">
                                    <i class="fas fa-upload mr-1"></i> Upload
                                </button>
                            </div>
                            <input id="productImageUrl1" type="text" name="image_url" placeholder="Enter image URL" class="form-input">
                            <input type="file" name="image" accept="image/*" id="imageInput1" class="hidden">
                            <div class="image-preview" id="imagePreview">
                                <img src="" alt="Product Image" id="previewImg1" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <!-- Additional Details Section -->
                    <div id="additionalDetailsSection" class="form-section hidden">
                        <h4 class="section-title">Additional Details</h4>

                        <div class="mb-4">
                            <label for="gender" class="form-label">Gender</label>
                            <select name="gender" id="gender" class="form-input" required>
                                <option value="unisex">Unisex</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="productDescription1" class="form-label">Description</label>
                            <textarea id="productDescription1" placeholder="Enter product description" class="form-input h-24" name="descriptions" required><?= isset($product) ? htmlspecialchars($product['descriptions']) : '' ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="productDiscount1" class="form-label">Discount</label>
                            <input id="productDiscount1" type="number" step="0.01" placeholder="Enter discount amount" class="form-input" name="discount" min="0">
                        </div>

                        <div class="mb-4">
                            <label for="productDiscountType1" class="form-label">Discount Type</label>
                            <select id="productDiscountType1" class="form-input" name="discount_type">
                                <option value="">Select Discount Type</option>
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Fixed Amount ($)</option>
                                <option value="bogo">Buy One Get One</option>
                                <option value="clearance">Clearance</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="productBarcode1" class="form-label">Barcode</label>
                            <div class="relative">
                                <input id="productBarcode" type="text" class="form-input pr-32" name="barcode" value="<?= isset($product) ? htmlspecialchars($product['barcode']) : '' ?>"/>
                                <button id="generateBarcodeButton" type="button" class="absolute right-2 top-2 text-blue-500 hover:text-blue-700">
                                    Generate Barcode
                                </button>
                            </div>

                            <div class="barcode-container mt-2">
                                <canvas id="barcodeCanvas"></canvas>
                                <div id="errorMessage" class="text-red-600 text-sm mt-1" style="display: none;">
                                    Invalid barcode! Please try again.
                                </div>
                            </div>
                        </div>
                        
                        <!-- Color options -->
                        <div class="mb-4">
                            <label class="form-label">Available Colors</label>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <div class="color-option">
                                    <input type="checkbox" id="color-red" name="colors[]" value="red" class="hidden">
                                    <label for="color-red" class="inline-block w-8 h-8 rounded-full bg-red-500 cursor-pointer border-2 border-transparent hover:border-gray-400"></label>
                                </div>
                                <div class="color-option">
                                    <input type="checkbox" id="color-blue" name="colors[]" value="blue" class="hidden">
                                    <label for="color-blue" class="inline-block w-8 h-8 rounded-full bg-blue-500 cursor-pointer border-2 border-transparent hover:border-gray-400"></label>
                                </div>
                                <div class="color-option">
                                    <input type="checkbox" id="color-green" name="colors[]" value="green" class="hidden">
                                    <label for="color-green" class="inline-block w-8 h-8 rounded-full bg-green-500 cursor-pointer border-2 border-transparent hover:border-gray-400"></label>
                                </div>
                                <div class="color-option">
                                    <input type="checkbox" id="color-yellow" name="colors[]" value="yellow" class="hidden">
                                    <label for="color-yellow" class="inline-block w-8 h-8 rounded-full bg-yellow-500 cursor-pointer border-2 border-transparent hover:border-gray-400"></label>
                                </div>
                                <div class="color-option">
                                    <input type="checkbox" id="color-purple" name="colors[]" value="purple" class="hidden">
                                    <label for="color-purple" class="inline-block w-8 h-8 rounded-full bg-purple-500 cursor-pointer border-2 border-transparent hover:border-gray-400"></label>
                                </div>
                                <div class="color-option">
                                    <input type="checkbox" id="color-pink" name="colors[]" value="pink" class="hidden">
                                    <label for="color-pink" class="inline-block w-8 h-8 rounded-full bg-pink-500 cursor-pointer border-2 border-transparent hover:border-gray-400"></label>
                                </div>
                                <div class="color-option">
                                    <input type="checkbox" id="color-black" name="colors[]" value="black" class="hidden">
                                    <label for="color-black" class="inline-block w-8 h-8 rounded-full bg-black cursor-pointer border-2 border-transparent hover:border-gray-400"></label>
                                </div>
                                <div class="color-option">
                                    <input type="checkbox" id="color-white" name="colors[]" value="white" class="hidden">
                                    <label for="color-white" class="inline-block w-8 h-8 rounded-full bg-white cursor-pointer border-2 border-gray-400 hover:border-gray-600"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="form-actions">
                    <a href="/products" class="bg-gray-300 hover:bg-gray-400 text-gray-800">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" id="saveProductButton1" class="bg-blue-600 hover:bg-blue-700 text-white">
                        <i class="fas fa-save"></i> Next
                    </button>

                </div>
            </form>
        </section>
    </main>
</div>
<script>
    // Add this JavaScript code to handle the multi-step form
document.addEventListener('DOMContentLoaded', function() {
    // Get the Next button
    const nextButton = document.getElementById('saveProductButton1');
    
    // Get the form sections
    const productInfoSection = document.getElementById('productInfoSection');
    const additionalDetailsSection = document.getElementById('additionalDetailsSection');
    
    // Get the section toggle buttons
    const showProductInfoBtn = document.getElementById('showProductInfo');
    const showAdditionalDetailsBtn = document.getElementById('showAdditionalDetails');
    
    // Modify the Next button to navigate between sections instead of submitting
    nextButton.addEventListener('click', function(e) {
        // If we're on the first section, go to the second section
        if (!productInfoSection.classList.contains('hidden')) {
            e.preventDefault(); // Prevent form submission
            
            // Validate required fields in the first section
            const requiredFields = productInfoSection.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value) {
                    isValid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });
            
            if (isValid) {
                // Switch to the second section
                productInfoSection.classList.add('hidden');
                additionalDetailsSection.classList.remove('hidden');
                
                // Update the toggle buttons
                showProductInfoBtn.classList.remove('active');
                showAdditionalDetailsBtn.classList.add('active');
                
                // Change button text to "Save" on the last section
                nextButton.innerHTML = '<i class="fas fa-save"></i> Save Product';
            }
        }
        // If we're on the second section, let the form submit normally
    });
    
    // Update the section toggle buttons to work with the multi-step form
    showProductInfoBtn.addEventListener('click', function() {
        productInfoSection.classList.remove('hidden');
        additionalDetailsSection.classList.add('hidden');
        showProductInfoBtn.classList.add('active');
        showAdditionalDetailsBtn.classList.remove('active');
        nextButton.innerHTML = '<i class="fas fa-arrow-right"></i> Next';
    });

    showAdditionalDetailsBtn.addEventListener('click', function() {
        productInfoSection.classList.add('hidden');
        additionalDetailsSection.classList.remove('hidden');
        showProductInfoBtn.classList.remove('active');
        showAdditionalDetailsBtn.classList.add('active');
        nextButton.innerHTML = '<i class="fas fa-save"></i> Save Product';
    });
});


// JavaScript to handle category-specific field visibility
document.addEventListener('DOMContentLoaded', function() {
    // Get the category select element
    const categorySelect = document.getElementById('productCategory1');
    const sizeField = document.getElementById('sizeField');
    const brandField = document.getElementById('brandField');
    
    // Categories that don't need size and brand
    const categoriesWithoutSizeAndBrand = ['toys', 'student', 'jewelry', 'makeup', 'other'];
    
    // Function to toggle fields based on selected category
    function toggleFields() {
        const selectedCategory = categorySelect.value;
        
        if (categoriesWithoutSizeAndBrand.includes(selectedCategory)) {
            sizeField.classList.add('field-hidden');
            brandField.classList.add('field-hidden');
            // Remove required attribute from size field
            document.getElementById('productSize').removeAttribute('required');
        } else {
            sizeField.classList.remove('field-hidden');
            brandField.classList.remove('field-hidden');
            // Add required attribute back to size field
            document.getElementById('productSize').setAttribute('required', '');
        }
    }
    
    // Initial toggle based on pre-selected category (if any)
    toggleFields();
    
    // Add event listener for category changes
    categorySelect.addEventListener('change', toggleFields);
    
    // Section toggle functionality
    const showProductInfoBtn = document.getElementById('showProductInfo');
    const showAdditionalDetailsBtn = document.getElementById('showAdditionalDetails');
    const productInfoSection = document.getElementById('productInfoSection');
    const additionalDetailsSection = document.getElementById('additionalDetailsSection');

    showProductInfoBtn.addEventListener('click', function() {
        productInfoSection.classList.remove('hidden');
        additionalDetailsSection.classList.add('hidden');
        showProductInfoBtn.classList.add('active');
        showAdditionalDetailsBtn.classList.remove('active');
    });

    showAdditionalDetailsBtn.addEventListener('click', function() {
        productInfoSection.classList.add('hidden');
        additionalDetailsSection.classList.remove('hidden');
        showProductInfoBtn.classList.remove('active');
        showAdditionalDetailsBtn.classList.add('active');
    });

    // Toggle between URL and file upload
    const toggleUrlBtn = document.getElementById('toggleUrl1');
    const toggleFileBtn = document.getElementById('toggleFile1');
    const imageUrlInput = document.getElementById('productImageUrl1');
    const fileInput = document.getElementById('imageInput1');
    const previewImg = document.getElementById('previewImg1');

    toggleUrlBtn.addEventListener('click', function() {
        imageUrlInput.style.display = 'block';
        fileInput.style.display = 'none';
        toggleUrlBtn.classList.add('active');
        toggleFileBtn.classList.remove('active');
    });

    toggleFileBtn.addEventListener('click', function() {
        imageUrlInput.style.display = 'none';
        fileInput.style.display = 'block';
        fileInput.click();
        toggleUrlBtn.classList.remove('active');
        toggleFileBtn.classList.add('active');
    });

    // Preview image when URL is entered
    imageUrlInput.addEventListener('input', function() {
        if (this.value) {
            previewImg.src = this.value;
            previewImg.style.display = 'block';
        } else {
            previewImg.style.display = 'none';
        }
    });

    // Preview image when file is selected
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Barcode generation
    const generateBarcodeButton = document.getElementById('generateBarcodeButton');
    const barcodeInput = document.getElementById('productBarcode');
    const barcodeCanvas = document.getElementById('barcodeCanvas');
    const errorMessage = document.getElementById('errorMessage');

    generateBarcodeButton.addEventListener('click', function() {
        try {
            // Generate a random barcode if empty
            if (!barcodeInput.value) {
                barcodeInput.value = generateRandomBarcode();
            }
            
            // Generate the barcode
            JsBarcode(barcodeCanvas, barcodeInput.value, {
                format: "CODE128",
                lineColor: "#000",
                width: 2,
                height: 50,
                displayValue: true
            });
            
            errorMessage.style.display = 'none';
        } catch (e) {
            errorMessage.style.display = 'block';
            console.error(e);
        }
    });

    function generateRandomBarcode() {
        let result = '';
        const characters = '0123456789';
        const length = 12;
        for (let i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * characters.length));
        }
        return result;
    }
    
    // Color selection functionality
    const colorOptions = document.querySelectorAll('.color-option input');
    colorOptions.forEach(option => {
        option.addEventListener('change', function() {
            if (this.checked) {
                this.nextElementSibling.classList.remove('border-transparent', 'border-gray-400');
                this.nextElementSibling.classList.add('border-blue-500', 'border-2');
            } else {
                this.nextElementSibling.classList.remove('border-blue-500');
                this.nextElementSibling.classList.add('border-transparent');
            }
        });
    });
});
</script>

</body>
</html>
<?php else: $this->redirect("/"); endif; ?>

