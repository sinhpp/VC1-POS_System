<script>
  document.addEventListener("DOMContentLoaded", function() {
      // List of stylesheets to disable
      const stylesToDisable = [
          "/views/assets/css/form.css",
          "/views/assets/css/form.forgot.password.css",
          "https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap",
          "https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"
      ];

      // Disable the stylesheets
      document.querySelectorAll("link[rel='stylesheet']").forEach(link => {
          if (stylesToDisable.includes(link.getAttribute("href"))) {
              link.disabled = true; // Disable the stylesheet
          }
      });
  });
</script>

<?php
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }
if (isset($_SESSION['user_id'])) : ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
         body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            display: block;
            height: 100vh;
            position: relative;
         }   
        .table th, .table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .table-striped tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }
        .table-dark {
            background-color: #343a40;
            color: white;
        }
        .badge.bg-success { background-color: #28a745; color: white; }
        .badge.bg-danger { background-color: #dc3545; color: white; }
        .btn {
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-warning { background-color: #ffc107; color: black; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn:hover { 
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .barcode-container {
            margin-top: 20px;
            text-align: center;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .barcode-error {
            color: #dc3545;
            display: none;
            margin-top: 10px;
            font-size: 0.9rem;
        }
        
        /* Animation for page load */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Responsive styles */
        .grid-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-left:10%;
        }
        
        .card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .card-header {
            background: linear-gradient(135deg, #2087F7 0%, #1a6fc7 100%);
            color: white;
            font-weight: 600;
            padding: 15px;
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #F868D4 0%, #c44da6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
        }
        
        .actions button {
            height: 50px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }
        
        .actions button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        
        .add {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
            border: none;
            color: white;
            border-radius: 8px;
        }
        
        /* Media queries for responsiveness */
        @media (max-width: 992px) {
            .grid-container {
                grid-template-columns: 1fr;
            }
            
            .container {
                max-width: 95% !important;
                margin: 0 auto;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .breadcrumb {
                margin-top: 10px;
            }
        }
        
        @media (max-width: 768px) {
            .size-gender {
                flex-direction: column;
            }
            
            .size, .gender {
                width: 100%;
                margin-bottom: 15px;
            }
            
            .actions button {
                width: 100%;
            }
            
            .card-header {
                font-size: 1.1rem;
            }
        }
        
        @media (max-width: 576px) {
            body {
                font-size: 14px;
            }
            
            .container {
                padding: 10px;
            }
            
            .btn-group {
                flex-wrap: wrap;
            }
            
            .btn-group .btn {
                flex: 1 0 auto;
                margin-bottom: 5px;
            }
        }
    </style>


</head>
<body>
 
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

       
<div class="container fade-in">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-gradient"><i class="fas fa-edit me-2"></i>Edit Product</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="/products">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
    
    <main class="grid-container">
    <section class="general-info card">
        <form action="/products/store" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= isset($product) ? htmlspecialchars($product['id']) : '' ?>">
            
        <h5 class="card-header"><i class="fas fa-info-circle me-2"></i>General Information</h5>
        <div class="card-body">
            <div class="form-group mb-3">
                <label class="form-label">Product Name</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                    <input type="text" class="form-control" placeholder="Enter product name" name="name" value="<?= isset($product) ? htmlspecialchars($product['name']) : '' ?>" required>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Base Pricing</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                    <input type="number" class="form-control" placeholder="0.00" name="price" value="<?= htmlspecialchars($product['price']) ?>" required min="0" step="0.01">
                </div>
            </div>
            
            <div class="form-group mb-3">
                <label class="form-label">Stock</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-boxes"></i></span>
                    <input type="number" id="stockInput" class="form-control" placeholder="Enter stock quantity" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" required min="0" step="1">
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Description</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                    <input type="text" class="form-control" placeholder="Enter description" name="descriptions" value="<?= isset($product) ? htmlspecialchars($product['descriptions']) : '' ?>" required>
                </div>
            </div>

            <div class="size-gender row">
                <div class="size col-md-6 mb-3">
                    <label class="form-label">Size</label>
                    <div class="size-options btn-group w-100">
                        <button type="button" class="btn btn-outline-primary" onclick="selectSize(this, 'S')">S</button>
                        <button type="button" class="btn btn-outline-primary" onclick="selectSize(this, 'M')">M</button>
                        <button type="button" class="btn btn-outline-primary selected" onclick="selectSize(this, 'L')">L</button>
                        <button type="button" class="btn btn-outline-primary" onclick="selectSize(this, 'XL')">XL</button>
                        <button type="button" class="btn btn-outline-primary" onclick="selectSize(this, 'XXL')">XXL</button>
                    </div>
                    <input type="hidden" name="size" id="size" value="L"> <!-- Default value -->
                </div>
                <div class="gender col-md-6 mb-3">
                    <label class="form-label">Gender</label>
                    <div class="gender-options btn-group w-100">
                        <button type="button" class="btn btn-outline-primary selected" onclick="selectGender(this, 'Men')">Men</button>
                        <button type="button" class="btn btn-outline-primary" onclick="selectGender(this, 'Women')">Women</button>
                    </div>
                    <input type="hidden" name="gender" id="gender" value="Men"> <!-- Default value -->
                </div>
            </div>
            
            <h5 class="mt-4 mb-3"><i class="fas fa-image me-2"></i>Product Image</h5>
            <div class="image-preview card mb-3" id="imagePreview">
                <img src="<?= !empty($product['image']) ? '/' . htmlspecialchars($product['image']) : '' ?>" alt="Product Image" id="previewImg" class="card-img-top" style="max-height: 200px; object-fit: contain; <?= empty($product['image']) ? 'display: none;' : '' ?>">
            </div>
            
            <div class="upload-img mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-upload"></i></span>
                    <input type="file" class="form-control" id="fileUpload" name="image" accept="image/*">
                </div>
                <small class="text-muted">Recommended size: 800x800px, Max size: 2MB</small>
            </div>
        </div>
        </section>
        
        <script>
            function selectSize(button, size) {
                // Remove 'selected' class from all size buttons
                document.querySelectorAll('.size-options button').forEach(btn => {
                    btn.classList.remove('selected');
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline-primary');
                });

                // Add 'selected' class
                                    // Add 'selected' class to the clicked button
                button.classList.add('selected');
                button.classList.remove('btn-outline-primary');
                button.classList.add('btn-primary');

                // Update the hidden input value
                document.getElementById('size').value = size;
                console.log('Selected Size:', size); // Debugging
            }

            function selectGender(button, gender) {
                // Remove 'selected' class from all gender buttons
                document.querySelectorAll('.gender-options button').forEach(btn => {
                    btn.classList.remove('selected');
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline-primary');
                });

                // Add 'selected' class to the clicked button
                button.classList.add('selected');
                button.classList.remove('btn-outline-primary');
                button.classList.add('btn-primary');

                // Update the hidden input value
                document.getElementById('gender').value = gender;
                console.log('Selected Gender:', gender); // Debugging
            }
        </script>

        <section class="pricing-stock card">
            <h5 class="card-header"><i class="fas fa-tags me-2"></i>Pricing And Stocks</h5>
            <div class="card-body">
                <div class="form-group mb-3">
                    <label class="form-label">Discount</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-percent"></i></span>
                        <input type="number" class="form-control" placeholder="Enter discount" name="discount" value="<?= htmlspecialchars($product['discount'] ?? '') ?>" min="0" step="0.01">
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Discount Type</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                        <input type="text" class="form-control" placeholder="Enter discount type" name="discount_type" value="<?= htmlspecialchars($product['discount_type'] ?? '') ?>">
                    </div>
                </div>

                <div class="category mb-3">
                    <label class="form-label">Category</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-folder"></i></span>
                        <select id="categorySelect" class="form-select" name="category" required>
                            <!-- General Categories -->
                            <option value="Uniform" <?= isset($product) && $product['category'] == 'Uniform' ? 'selected' : '' ?>>Uniform</option>
                            <option value="T-shirt" <?= isset($product) && $product['category'] == 'T-shirt' ? 'selected' : '' ?>>T-shirt</option>
                            <option value="Sport Clothes" <?= isset($product) && $product['category'] == 'Sport Clothes' ? 'selected' : '' ?>>Sport Clothes</option>
                            <option value="Clothes" <?= isset($product) && $product['category'] == 'Clothes' ? 'selected' : '' ?>>Clothes</option>
                            <option value="Shoes" <?= isset($product) && $product['category'] == 'Shoes' ? 'selected' : '' ?>>Shoes</option>
                            <option value="Bag" <?= isset($product) && $product['category'] == 'Bag' ? 'selected' : '' ?>>Bag</option>
                            <option value="Shirt" <?= isset($product) && $product['category'] == 'Shirt' ? 'selected' : '' ?>>Shirt</option>
                            <option value="Nightwear" <?= isset($product) && $product['category'] == 'Nightwear' ? 'selected' : '' ?>>Nightwear</option>
                            
                            <!-- Student Material Option -->
                            <option value="Student Material" <?= isset($product) && $product['category'] == 'Student Material' ? 'selected' : '' ?>>Student Material</option>
                            
                            <!-- Other Category Option -->
                            <option value="Other" <?= isset($product) && $product['category'] == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group mb-3">
                    <label class="form-label">Barcode</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                        <input id="productBarcode" type="text" class="form-control" name="barcode" value="<?= isset($product) ? htmlspecialchars($product['barcode']) : '' ?>"/>
                        <button id="generateBarcodeButton" type="button" class="btn btn-warning">
                            <i class="fas fa-sync-alt me-1"></i> Generate
                        </button>
                    </div>
                    <small class="text-muted">Enter a barcode or generate a new one</small>
                </div>

                <div class="barcode-container">
                    <canvas id="barcodeCanvas"></canvas>
                    <div id="errorMessage" class="barcode-error">
                        <i class="fas fa-exclamation-triangle me-1"></i> Invalid barcode! Please try again.
                    </div>
                </div>

                <!-- Additional Dropdown for Student Material (hidden by default) -->
                <div id="studentMaterialOptions" class="form-group mb-3" style="display: none;">
                    <label class="form-label">Student Material Type</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-book"></i></span>
                        <select name="student_material" class="form-select">
                            <option value="Book" <?= isset($product) && $product['category'] == 'Book' ? 'selected' : '' ?>>Book</option>
                            <option value="Pen" <?= isset($product) && $product['category'] == 'Pen' ? 'selected' : '' ?>>Pen</option>
                            <option value="Ruler" <?= isset($product) && $product['category'] == 'Ruler' ? 'selected' : '' ?>>Ruler</option>
                        </select>
                    </div>
                </div>

                <!-- Input for "Other" Category (hidden by default) -->
                <div id="otherCategoryInput" class="form-group mb-3" style="display: none;">
                    <label class="form-label">Please specify:</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-edit"></i></span>
                        <input type="text" id="otherInput" class="form-control" name="other_category_input" placeholder="Enter other category" value="<?= isset($product) && $product['category'] == 'Other' ? htmlspecialchars($product['other_category_input'] ?? '') : '' ?>">
                    </div>
                </div>
            </div>
        </section>

        <script>
            const barcodeInput = document.getElementById('productBarcode');
            const errorMessage = document.getElementById('errorMessage');
            const generateBarcodeButton = document.getElementById('generateBarcodeButton');
            const barcodeCanvas = document.getElementById('barcodeCanvas');

            // Generate Barcode Logic
            generateBarcodeButton.addEventListener('click', function () {
                const barcodeValue = barcodeInput.value.trim();
                if (barcodeValue) {
                    try {
                        JsBarcode(barcodeCanvas, barcodeValue, {
                            format: "CODE128",
                            width: 2,
                            height: 40,
                            displayValue: true
                        });
                        errorMessage.style.display = 'none';
                        // Add animation effect
                        barcodeCanvas.classList.add('fade-in');
                        setTimeout(() => barcodeCanvas.classList.remove('fade-in'), 500);
                    } catch (e) {
                        errorMessage.style.display = 'block';
                        barcodeCanvas.innerHTML = '';
                    }
                } else {
                    // Generate random barcode if empty
                    const randomBarcode = Math.floor(Math.random() * 9000000000000) + 1000000000000;
                    barcodeInput.value = randomBarcode;
                    JsBarcode(barcodeCanvas, randomBarcode.toString(), {
                        format: "CODE128",
                        width: 2,
                        height: 40,
                        displayValue: true
                    });
                    errorMessage.style.display = 'none';
                    // Add animation effect
                    barcodeCanvas.classList.add('fade-in');
                    setTimeout(() => barcodeCanvas.classList.remove('fade-in'), 500);
                }
            });

            // When the category dropdown value changes
            document.getElementById("categorySelect").addEventListener("change", function() {
                var selectedCategory = this.value;
                var studentMaterialOptions = document.getElementById("studentMaterialOptions");
                var otherCategoryInput = document.getElementById("otherCategoryInput");

                // Show or hide options based on selected category
                if (selectedCategory === "Student Material") {
                    studentMaterialOptions.style.display = "block"; // Show the sub-options
                    otherCategoryInput.style.display = "none"; // Hide the "Other" input field
                } else if (selectedCategory === "Other") {
                    studentMaterialOptions.style.display = "none"; // Hide the sub-options for student material
                    otherCategoryInput.style.display = "block"; // Show the input field for "Other"
                } else {
                    studentMaterialOptions.style.display = "none"; // Hide the sub-options
                    otherCategoryInput.style.display = "none"; // Hide the "Other" input field
                }
            });

            // Trigger the change event on page load to handle the preselected category
            document.getElementById("categorySelect").dispatchEvent(new Event("change"));

            // File upload preview
            document.getElementById('fileUpload').addEventListener('change', function(event) {
                const file = event.target.files[0];
                const previewImg = document.getElementById('previewImg');
                
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        previewImg.style.display = 'block';
                        // Add animation effect
                        previewImg.classList.add('fade-in');
                        setTimeout(() => previewImg.classList.remove('fade-in'), 500);
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewImg.style.display = 'none';
                }
            });

            // Initialize barcode if value exists
            document.addEventListener('DOMContentLoaded', function() {
                const barcodeValue = barcodeInput.value.trim();
                if (barcodeValue) {
                    try {
                        JsBarcode(barcodeCanvas, barcodeValue, {
                            format: "CODE128",
                            width: 2,
                            height: 40,
                            displayValue: true
                        });
                    } catch (e) {
                        // Silent fail
                    }
                }
            });
        </script>

        <div class="actions mt-4">
            <button type="submit" class="add btn btn-lg w-100">
                <i class="fas <?= isset($product) ? 'fa-save' : 'fa-plus-circle' ?> me-2"></i>
                <?= isset($product) ? 'Update Product' : 'Add Product' ?>
            </button>
        </div>
    </form>
</main>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom script for form validation -->
<script>
    // Form validation
    document.querySelector('form').addEventListener('submit', function(event) {
        let isValid = true;
        const requiredFields = this.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
                
                // Create error message if it doesn't exist
                if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('invalid-feedback')) {
                    const feedback = document.createElement('div');
                    feedback.classList.add('invalid-feedback');
                    feedback.textContent = 'This field is required';
                    field.parentNode.appendChild(feedback);
                }
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            event.preventDefault();
            // Scroll to the first invalid field
            const firstInvalid = this.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
    
    // Clear validation on input
    document.querySelectorAll('input, select, textarea').forEach(element => {
        element.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
</script>
</div>

<?php 
else: 
    $this->redirect("/"); 
endif;   
?>
