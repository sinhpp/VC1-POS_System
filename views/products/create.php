<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) : ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Products</title>
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
            background-color: #f3f4f6;
        }
        
        /* Custom styles for Add Product forms and table */
        .form-container {
            margin: 0 auto;
            background: linear-gradient(135deg, #ffffff, #f9fafb);
            border: 1px solid #e5e7eb;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 1200px;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
        }
        
        .form-header {
            background: linear-gradient(90deg, #3b82f6, #2563eb);
            color: white;
            padding: 1.5rem;
            text-align: center;
            font-weight: 600;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }
        
        .form-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 80%);
            transform: rotate(30deg);
        }
        
        .form-input {
            transition: all 0.3s ease-in-out;
            border-color: #d1d5db;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        
        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
            transform: translateY(-2px);
        }
        
        .form-label {
            font-weight: 500;
            color: #4b5563;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.875rem;
        }
        
        .toggle-button.active {
            background: linear-gradient(90deg, #3b82f6, #2563eb) !important;
            color: white !important;
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);
        }
        
        .toggle-button {
            transition: all 0.3s ease-in-out;
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1rem;
        }
        
        .form-button {
            transition: all 0.3s ease-in-out;
            font-weight: 600;
            position: relative;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .form-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .form-button i {
            margin-right: 0.5rem;
        }
        
        .image-preview {
            margin-top: 1rem;
            display: flex;
            justify-content: center;
        }
        
        #previewImg1 {
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 200px;
            max-height: 200px;
            object-fit: contain;
        }
        
        /* Responsive styles */
        @media (max-width: 768px) {
            .form-container {
                width: 95%;
                margin: 0 auto;
            }
            
            .form-flex-container {
                flex-direction: column;
            }
            
            .form-section {
                width: 100% !important;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .form-actions .form-button,
            .form-actions .form-input {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            
            .form-actions {
                gap: 0.5rem;
            }
        }
        
        /* Animation effects */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        /* Custom select styling */
        select.form-input {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%234b5563'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1rem;
            padding-right: 2.5rem;
        }
        
        /* Form section styling */
        .form-section {
            padding: 1.5rem;
            border-radius: 12px;
            background-color: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .form-section:hover {
            background-color: rgba(255, 255, 255, 0.9);
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.05);
        }
        
        /* Section titles */
        .section-title {
            font-weight: 600;
            color: #3b82f6;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto my-8 p-4">
        <!-- Layout 1: Modern Split Card -->
        <div id="addProductForm1" class="flex items-center justify-center min-h-screen animate-fade-in">
            <div class="form-container">
                <div class="form-header">
                    <h3 class="text-2xl font-bold">Create New Product</h3>
                </div>

                <form action="/products/store" method="POST" enctype="multipart/form-data" class="p-6">
                    <input type="hidden" name="id" value="<?= isset($product) ? htmlspecialchars($product['id']) : '' ?>">

                    <div class="flex space-x-6 form-flex-container">
                        <!-- Left Section -->
                        <div class="w-1/2 space-y-5 form-section">
                            <h4 class="section-title">Product Information</h4>
                            
                            <div>
                                <label for="productName1" class="form-label">Product Name</label>
                                <input id="productName1" type="text" placeholder="Enter product name" class="w-full px-3 py-2 border rounded-lg form-input" name="name" value="<?= isset($product) ? htmlspecialchars($product['name']) : '' ?>" required>
                            </div>

                            <div>
                                <label for="productSize" class="form-label">Size</label>
                                <select name="size" id="productSize" class="w-full px-3 py-2 border rounded-lg form-input" required>
                                    <option value="">Select Size</option>
                                    <option value="XS">XS</option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                    <option value="XXL">XXL</option>
                                </select>
                            </div>

                            <div>
                                <label for="productStock1" class="form-label">Stock Quantity</label>
                                <input id="productStock1" type="number" placeholder="Enter stock quantity" class="w-full px-3 py-2 border rounded-lg form-input" name="stock" required min="0" step="1">
                            </div>
                            <div>
                                <label for="productBrand1" class="form-label">Brand</label>
                                <input id="productBrand1" type="text" placeholder="Enter brand name" class="w-full px-3 py-2 border rounded-lg form-input" name="brand">
                            </div>
                            
                            <div>
                                <label for="productPrice1" class="form-label">Price</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">$</span>
                                    <input id="productPrice1" type="number" step="0.01" placeholder="0.00" class="w-full pl-8 px-3 py-2 border rounded-lg form-input" name="price" required min="0" step="0.01">
                                </div>
                            </div>
                            
                            <div>
                                <label for="productCategory1" class="form-label">Category</label>
                                <select id="productCategory1" class="w-full px-3 py-2 border rounded-lg form-input" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="T-shirt">T-shirt</option>
                                    <option value="Uniform">Uniform</option>
                                    <option value="Bag">Bag</option>
                                    <option value="Pants">Pants</option>
                                    <option value="Shoes">Shoes</option>
                                    <option value="Jacket">Jacket</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Product Image</label>
                                <div class="flex space-x-2 mb-2">
                                    <button type="button" id="toggleUrl1" class="flex-1 bg-blue-500 text-white px-3 py-2 rounded toggle-button active">
                                        <i class="fas fa-link mr-1"></i> URL
                                    </button>
                                    <button type="button" id="toggleFile1" class="flex-1 bg-gray-300 text-gray-700 px-3 py-2 rounded toggle-button">
                                        <i class="fas fa-upload mr-1"></i> Upload
                                    </button>
                                </div>
                                <input id="productImageUrl1" type="text" name="image_url" placeholder="Enter image URL" class="w-full px-3 py-2 border rounded-lg form-input">

                                <!-- File Input -->
                                <input type="file" name="image" accept="image/*" id="imageInput1" class="hidden">

                                <!-- Image Preview -->
                                <div class="image-preview" id="imagePreview">
                                    <img src="" alt="Product Image" id="previewImg1" style="display: none;">
                                </div>
                            </div>
                            
                        </div>
                        
                        <!-- Right Section -->
                        <div class="w-1/2 space-y-5 form-section">
                            <h4 class="section-title">Additional Details</h4>
                            
                            <div>
                                <label for="gender" class="form-label">Gender</label>
                                <select name="gender" id="gender" class="w-full px-3 py-2 border rounded-lg form-input" required>
                                    <option value="unisex">Unisex</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="productDescription1" class="form-label">Description</label>
                                <textarea id="productDescription1" placeholder="Enter product description" class="w-full px-3 py-2 border rounded-lg form-input h-24" name="descriptions" required><?= isset($product) ? htmlspecialchars($product['descriptions']) : '' ?></textarea>
                            </div>
                            
                            <div>
                                <label for="productDiscount1" class="form-label">Discount</label>
                                <input id="productDiscount1" type="number" step="0.01" placeholder="Enter discount amount" class="w-full px-3 py-2 border rounded-lg form-input" name="discount" min="0" step="0.01">
                            </div>
                    <div>
                        <label for="productDiscountType1" class="form-label">Discount Type</label>
                        <select id="productDiscountType1" class="w-full px-3 py-2 border rounded-lg form-input" name="discount_type">
                            <option value="">Select Discount Type</option>
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Amount ($)</option> <!-- Fixed missing closing tag -->
                            <option value="bogo">Buy One Get One</option>
                            <option value="clearance">Clearance</option>
                        </select>
                    </div>

                    <div>
                        <label for="productBarcode1" class="relative">Barcode</label>
                        <div class="relative">
                     
                        <input id="productBarcode" type="text" class="w-full px-3 py-2 border rounded-lg form-input" name="barcode" value="<?= isset($product) ? htmlspecialchars($product['barcode']) : '' ?>"/>
                        <button id="generateBarcodeButton" type="button" class="absolute right-2 top-2 text-blue-500 hover:text-blue-700">Generate Barcode</button>

                        <div class="barcode-container">
                            <canvas id="barcodeCanvas"></canvas>
                            <div id="errorMessage" class="barcode-error" style="display: none;">Invalid barcode! Please try again.</div>
                        </div>
                       
                        </div>

                        <script>
                            const barcodeInput = document.getElementById('productBarcode');
                            const errorMessage = document.getElementById('errorMessage');
                            const generateBarcodeButton = document.getElementById('generateBarcodeButton');
                            const barcodeCanvas = document.getElementById('barcodeCanvas');
                            const addProductForm = document.getElementById('addProductForm');

                            // Generate Barcode Logic
                            generateBarcodeButton.addEventListener('click', function () {
                                const barcodeValue = barcodeInput.value.trim();
                                if (barcodeValue) {
                                    JsBarcode(barcodeCanvas, barcodeValue, {
                                        format: "CODE128",
                                        width: 2,
                                        height: 40,
                                        displayValue: true
                                    });
                                    errorMessage.style.display = 'none';
                                } else {
                                    errorMessage.style.display = 'block';
                                }
                            });

                            // Save Product Logic
                            addProductForm.addEventListener('submit', function (event) {
                                event.preventDefault(); // Prevent default form submission for AJAX handling

                                const formData = new FormData(addProductForm);

                                // Submit the form data using Fetch API
                                fetch('/products/store', {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(response => response.json()) // Assuming your server returns JSON
                                .then(data => {
                                    if (data.success) {
                                        // Handle successful product creation
                                        alert('Product created successfully!'); // Notify success
                                        addProductForm.reset();
                                        barcodeCanvas.innerHTML = ''; // Clear barcode
                                    } else {
                                        // Handle errors
                                        alert(data.message || 'Error creating product');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                });
                            });
                        </script>

                        
                    </div>

                    <div class="mt-8 flex space-x-4 form-actions justify-center">
                        <button type="submit" id="saveProductButton1" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg form-button">
                            <i class="fas fa-save"></i> Save Product
                        </button>
                        <a href="/products" id="cancelButton1" class="bg-gradient-to-r from-gray-300 to-gray-400 text-gray-700 px-6 py-3 rounded-lg form-button text-center">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>

                    <script>
                        // Toggle functionality for URL and file input
                        const toggleUrl1 = document.getElementById('toggleUrl1');
                        const toggleFile1 = document.getElementById('toggleFile1');
                        const productImageUrl1 = document.getElementById('productImageUrl1');
                        const imageInput1 = document.getElementById('imageInput1');
                        const previewImg1 = document.getElementById('previewImg1');

                        toggleUrl1.addEventListener('click', function(e) {
                            e.preventDefault();
                            toggleUrl1.classList.add('active');
                            toggleFile1.classList.remove('active');
                            productImageUrl1.classList.remove('hidden');
                            imageInput1.classList.add('hidden');
                            previewImg1.style.display = 'none'; // Hide preview when switching to URL
                        });

                        toggleFile1.addEventListener('click', function(e) {
                            e.preventDefault();
                            toggleFile1.classList.add('active');
                            toggleUrl1.classList.remove('active');
                            productImageUrl1.classList.add('hidden');
                            imageInput1.classList.remove('hidden');
                            previewImg1.style.display = 'none'; // Hide preview when switching to file upload
                        });

                        // Handle file selection for preview
                        imageInput1.addEventListener('change', function() {
                            const file = this.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    previewImg1.src = e.target.result;
                                    previewImg1.style.display = 'block'; // Show the image preview
                                };
                                reader.readAsDataURL(file); // Convert to base64 for direct display
                            }
                        });

                        // Generate random barcode
                        document.getElementById('generateBarcode').addEventListener('click', function() {
                            const barcodeInput = document.getElementById('productBarcode1');
                            // Generate a random 12-digit number for the barcode
                            const randomBarcode = Math.floor(100000000000 + Math.random() * 900000000000).toString();
                            barcodeInput.value = randomBarcode;
                        });

                        // Preview URL image when entered
                        productImageUrl1.addEventListener('blur', function() {
                            if (this.value) {
                                previewImg1.src = this.value;
                                previewImg1.style.display = 'block';
                                previewImg1.onerror = function() {
                                    // If image fails to load, hide the preview
                                    this.style.display = 'none';
                                    alert('Unable to load image from the provided URL. Please check the URL and try again.');
                                };
                            }
                        });

                        // Form validation
                        document.querySelector('form').addEventListener('submit', function(e) {
                            const requiredFields = this.querySelectorAll('[required]');
                            let isValid = true;

                            requiredFields.forEach(field => {
                                if (!field.value.trim()) {
                                    isValid = false;
                                    field.classList.add('border-red-500');
                                    
                                    // Add error message if it doesn't exist
                                    let errorMsg = field.nextElementSibling;
                                    if (!errorMsg || !errorMsg.classList.contains('error-message')) {
                                        errorMsg = document.createElement('p');
                                        errorMsg.classList.add('error-message', 'text-red-500', 'text-xs', 'mt-1');
                                        errorMsg.textContent = 'This field is required';
                                        field.parentNode.insertBefore(errorMsg, field.nextSibling);
                                    }
                                } else {
                                    field.classList.remove('border-red-500');
                                    
                                    // Remove error message if it exists
                                    let errorMsg = field.nextElementSibling;
                                    if (errorMsg && errorMsg.classList.contains('error-message')) {
                                        errorMsg.remove();
                                    }
                                }
                            });

                            if (!isValid) {
                                e.preventDefault();
                                // Scroll to the first error
                                const firstError = this.querySelector('.border-red-500');
                                if (firstError) {
                                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                    firstError.focus();
                                }
                            }
                        });

                        // Clear error styling on input
                        document.querySelectorAll('.form-input').forEach(input => {
                            input.addEventListener('input', function() {
                                this.classList.remove('border-red-500');
                                
                                // Remove error message if it exists
                                let errorMsg = this.nextElementSibling;
                                if (errorMsg && errorMsg.classList.contains('error-message')) {
                                    errorMsg.remove();
                                }
                            });
                        });

                        // Add animation to form sections on page load
                        document.addEventListener('DOMContentLoaded', function() {
                            const sections = document.querySelectorAll('.form-section');
                            sections.forEach((section, index) => {
                                section.style.opacity = '0';
                                section.style.transform = 'translateY(20px)';
                                
                                setTimeout(() => {
                                    section.style.transition = 'all 0.5s ease-out';
                                    section.style.opacity = '1';
                                    section.style.transform = 'translateY(0)';
                                }, 100 * (index + 1));
                            });
                        });
                    </script>

</body>
</html>

<?php else: 
    $this->redirect("/"); 
endif; ?>