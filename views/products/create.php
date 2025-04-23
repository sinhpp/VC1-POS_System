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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../views/assets/css/create_product.css">
    <style>
       .field-hidden {
            display: none;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Add New Product</h2>
    <main class="grid-container">
        <section class="general-info">
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
                <?php if ($selectedCategory): ?>
                    <input type="hidden" name="selected_category" value="<?= htmlspecialchars($selectedCategory) ?>">
                <?php endif; ?>

                <div class="form-flex-container">
                    <div id="productInfoSection" class="form-section">
                        <h4 class="section-title">Product Information</h4>

                        <div class="mb-4">
                            <label for="productName1" class="form-label">Product Name</label>
                            <input id="productName1" type="text" placeholder="Enter product name" class="form-input" name="name" value="<?= isset($product) ? htmlspecialchars($product['name']) : '' ?>" required>
                        </div>

                        <!-- Size field - hidden for bags and toys -->
                        <div id="sizeField" class="mb-4 <?= in_array($selectedCategory, ['bag', 'toys']) ? 'field-hidden' : '' ?>">
                            <label for="productSize" class="form-label">Size</label>
                            <select name="size" id="productSize" class="form-input" <?= in_array($selectedCategory, ['bag', 'toys']) ? '' : 'required' ?>>
                                <option value="">Select Size</option>
                                <?php if ($selectedCategory == 'shoes'): ?>
                                    <option value="25">25</option>
                                    <option value="26">26</option>
                                    <option value="27">27</option>
                                    <option value="28">28</option>
                                <?php else: ?>
                                    <option value="XS">XS</option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                    <option value="XXL">XXL</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="productStock1" class="form-label">Stock Quantity</label>
                            <input id="productStock1" type="number" placeholder="Enter stock quantity" class="form-input" name="stock" required min="0" step="1">
                        </div>

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

                        <!-- Subcategory field -->
                        <div id="subcategoryField" class="mb-4 <?= $selectedCategory == 'clothes' ? '' : 'field-hidden' ?>">
                            <label for="productSubcategory" class="form-label">Category</label>
                            <select name="subcategory" id="productSubcategory" class="form-input" <?= $selectedCategory == 'clothes' ? 'required' : 'disabled' ?>>
                                <option value="">Category</option>
                                <option value="uniform">Uniform</option>
                                <option value="tshirt">T-Shirt</option>
                                <option value="sport_clothes">Sport Clothes</option>
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

                    <div id="additionalDetailsSection" class="form-section hidden">
                        <h4 class="section-title">Additional Details</h4>
                        <div class="mb-4" id="genderField">  <!-- Correct ID -->
                            <label for="gender" class="form-label">Gender</label>
                            <select name="gender" id="gender" class="form-input">
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
                            <input id="productDiscountType1" type="text" placeholder="Enter discount type" class="form-input" name="discount_type" value="<?= isset($product) ? htmlspecialchars($product['discount_type']) : '' ?>" required>
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
                    </div>
                </div>

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
function toggleFields() {
    const categorySelect = document.getElementById('productCategory1');
    const sizeField = document.getElementById('sizeField');
    const genderField = document.getElementById('genderField');
    const subcategoryField = document.getElementById('subcategoryField');

    if (categorySelect.value === 'bag') {
        sizeField.classList.add('field-hidden');
        document.getElementById('productSize').removeAttribute('required');
        
        subcategoryField.classList.add('field-hidden');
        genderField.classList.add('field-hidden');  // Hide gender field
        genderField.querySelector('select').removeAttribute('required');  // Remove required attribute
    } else if (categorySelect.value === 'clothes') {
        sizeField.classList.remove('field-hidden');
        document.getElementById('productSize').setAttribute('required', '');
        subcategoryField.classList.remove('field-hidden');
        genderField.classList.remove('field-hidden'); // Show gender field
        genderField.querySelector('select').setAttribute('required', ''); // Enable required for gender
    } else if (categorySelect.value === 'shoes') {
        sizeField.classList.remove('field-hidden');
        document.getElementById('productSize').setAttribute('required', '');
        document.getElementById('productSize').innerHTML = `
            <option value="25">25</option>
            <option value="26">26</option>
            <option value="27">27</option>
            <option value="28">28</option>
        `;
        subcategoryField.classList.add('field-hidden');
        genderField.classList.remove('field-hidden'); // Show gender field
        genderField.querySelector('select').setAttribute('required', ''); // Enable required for gender
    } else if (categorySelect.value === 'toys') {
        sizeField.classList.add('field-hidden'); // Hide size field
        document.getElementById('productSize').removeAttribute('required');
        genderField.classList.add('field-hidden'); // Hide gender field
        genderField.querySelector('select').removeAttribute('required'); // Remove required attribute for gender
    } else {
        sizeField.classList.remove('field-hidden');
        document.getElementById('productSize').setAttribute('required', '');
        subcategoryField.classList.add('field-hidden');
        genderField.classList.remove('field-hidden'); // Show gender field
        genderField.querySelector('select').setAttribute('required', ''); // Enable required for gender
    }
}
    document.addEventListener('DOMContentLoaded', function() {
        toggleFields(); // Initial call to set visibility based on pre-selected category
    });

    // Multi-step form handling
    document.addEventListener('DOMContentLoaded', function() {
        const nextButton = document.getElementById('saveProductButton1');
        const productInfoSection = document.getElementById('productInfoSection');
        const additionalDetailsSection = document.getElementById('additionalDetailsSection');
        const showProductInfoBtn = document.getElementById('showProductInfo');
        const showAdditionalDetailsBtn = document.getElementById('showAdditionalDetails');

        nextButton.addEventListener('click', function(e) {
            if (!productInfoSection.classList.contains('hidden')) {
                e.preventDefault();
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
                    productInfoSection.classList.add('hidden');
                    additionalDetailsSection.classList.remove('hidden');
                    showProductInfoBtn.classList.remove('active');
                    showAdditionalDetailsBtn.classList.add('active');
                    nextButton.innerHTML = '<i class="fas fa-save"></i> Save Product';
                }
            }
        });

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
</script>

</body>
</html>
<?php else: $this->redirect("/"); endif; ?>