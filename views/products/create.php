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
    <style>
        /* Custom styles for Add Product forms and table */
        .form-container {
            margin-left:20%;
            background: linear-gradient(135deg, #ffffff, #f9fafb);
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
            width: 70%;
        }
        
        .form-input {
            transition: all 0.2s ease-in-out;
            border-color: #d1d5db;
        }
        
        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }
        
        .toggle-button.active {
            background-color: #2563eb !important;
            color: white !important;
        }
        
        .toggle-button {
            transition: all 0.2s ease-in-out;
        }
        
        .form-button {
            transition: all 0.2s ease-in-out;
            font-weight: 500;
            position: relative;
        }
        
        .form-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .slide-in {
            width: 100%; /* 70% of original width */
            max-width: 840px; /* 70% of original 1200px */
        }
        
        .custom-table-container {
            position: relative;
            left: 40%;
            margin-right: auto;
            background: linear-gradient(135deg, #ffffff, #f9fafb);
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .custom-table th,
        .custom-table td {
            transition: background-color 0.2s ease-in-out;
        }
        
        .custom-table thead {
            background: #f3f4f6;
        }
        
        .custom-table tr:hover {
            background-color: #f1f5f9;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto my-8 p-4">

        <!-- Layout 1: Modern Split Card -->
        <div id="addProductForm1" class="flex items-center justify-center min-h-screen">
            <div class="form-container p-6 rounded-xl card-form">
                <h3 class="text-xl font-bold mb-6 text-gray-800 text-center">Create New Product</h3>

                <form action="/products/store" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= isset($product) ? htmlspecialchars($product['id']) : '' ?>">

                    <div class="flex space-x-6">
                        <!-- Left Section -->
                        <div class="w-1/2 space-y-4">
                            <div>
                                <div class="flex space-x-2 mb-2">
                                    <button type="button" id="toggleUrl1" class="flex-1 bg-blue-500 text-white px-2 py-1 rounded toggle-button active">URL</button>
                                    <button type="button" id="toggleFile1" class="flex-1 bg-gray-300 text-gray-700 px-2 py-1 rounded toggle-button">Upload</button>
                                </div>
                                <input id="productImageUrl1" type="text" name="image_url" placeholder="Image URL" class="w-full px-3 py-2 border rounded-lg form-input">

                                <!-- File Input -->
                                <input type="file" name="image" accept="image/*" id="imageInput1" class="hidden">

                                <!-- Image Preview -->
                                <div class="image-preview" id="imagePreview">
                                    <img src="" alt="Product Image" id="previewImg1" style="display: none; max-width: 150px;">
                                </div>
                            </div>
                            <input id="productName1" type="text" placeholder="Product Name" class="w-full px-3 py-2 border rounded-lg form-input" name="name" value="<?= isset($product) ? htmlspecialchars($product['name']) : '' ?>" required>
                            <input id="productBrand1" type="text" placeholder="Brand" class="w-full px-3 py-2 border rounded-lg form-input" name="brand">
                            <input id="productPrice1" type="number" step="0.01" placeholder="Price" class="w-full px-3 py-2 border rounded-lg form-input" name="price" required min="0" step="0.01">
                            <select id="productCategory1" class="w-full px-3 py-2 border rounded-lg form-input" name="category" required>
                                <option value="">Select Category</option>
                                <option value="T-shirt">T-shirt</option>
                                <option value="Uniform">Uniform</option>
                                <option value="Bag">Bag</option>
                                <option value="Pants">Pants</option>
                                <option value="Shoes">Shoes</option>
                                <option value="Jacket">Jacket</option>
                            </select>
                            <input id="productStock1" type="number" placeholder="Stock" class="w-full px-3 py-2 border rounded-lg form-input" name="stock" required min="0" step="1">
                        </div>
                        <!-- Right Section -->
                        <div class="w-1/2 space-y-4">
                            <select name="size" id="productSize" class="w-full px-3 py-2 border rounded-lg form-input" required>
                                <option value="">Select Size</option>
                                <option value="XS">XS</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                            </select>
                            <select name="gender" id="gender" class="w-full px-3 py-2 border rounded-lg form-input" required>
                                <option value="unisex">Unisex</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                            <textarea id="productDescription1" placeholder="Description" class="w-full px-3 py-2 border rounded-lg form-input h-24" name="descriptions" required><?= isset($product) ? htmlspecialchars($product['descriptions']) : '' ?></textarea>
                            <input id="productDiscount1" type="number" step="0.01" placeholder="Discount" class="w-full px-3 py-2 border rounded-lg form-input" name="discount" min="0" step="0.01">
                            <input id="productDiscountType1" type="text" placeholder="Type of Discount" class="w-full px-3 py-2 border rounded-lg form-input" name="discount_type">
                        </div>
                    </div>
                    <div class="mt-6 flex space-x-4">
                        <input id="productBarcode1" type="text" placeholder="Barcode" class="flex-1 px-3 py-2 border rounded-lg form-input" name="barcode" value="<?= isset($product) ? htmlspecialchars($product['barcode']) : '' ?>"/>
                        <button type="submit" id="saveProductButton1" class="bg-blue-600 text-white px-6 py-2 rounded-lg form-button">Save</button>
                        <a href="/products" id="cancelButton1" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg form-button text-center">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
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
    </script>
</body>
</html>
<?php else: 
    $this->redirect("/"); 
endif; ?>
