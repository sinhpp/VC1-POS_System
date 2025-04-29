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

<?php if (isset($_SESSION['user_id'])) : ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        .container {
            max-width: 88%;
            padding: 15px;
        }
        .grid-container {
            margin-top: 10%;
            margin-left: 24%;
            display: grid;
            grid-template-columns: 1fr 1fr;
         
        }
        .card {
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 15px;
            transition: transform 0.2s ease;
        }
        .card:hover {
            transform: translateY(-3px);
        }
        .card-header {
            background: linear-gradient(135deg, #2087F7 0%, #1a6fc7 100%);
            color: white;
            font-weight: 600;
            padding: 10px 15px;
            font-size: 0.9rem;
            border-radius: 8px 8px 0 0;
        }
        .card-body {
            padding: 15px;
        }
        .form-group {
            margin-bottom: 12px;
        }
        .form-label {
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 3px;
        }
        .input-group {
            margin-bottom: 8px;
        }
        .input-group-text {
            padding: 0.3rem 0.5rem;
        }
        .form-control, .form-select {
            padding: 0.3rem 0.5rem;
            font-size: 0.85rem;
        }
        .btn {
            padding: 0.3rem 0.7rem;
            font-size: 0.85rem;
        }
        .btn-group .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
        .text-muted {
            font-size: 0.75rem;
        }
        .barcode-container {
            margin-top: 10px;
            text-align: center;
            padding: 10px;
            background-color: #fff;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .actions button {
            height: 40px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .add {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
            border: none;
            color: white;
            border-radius: 6px;
        }
        .breadcrumb {
            font-size: 0.8rem;
            padding: 5px 0;
        }
        .page-header {
            margin-bottom: 15px;
        }
        .text-gradient {
            font-size: 1.2rem;
        }
        
        @media (max-width: 768px) {
            .grid-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
       
<div class="container">
    <div class="page-header d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-gradient"><i class="fas fa-edit me-2"></i>Edit Product</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="/products">Products</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
    
    <main class="grid-container">
    <section class="general-info card">
        <form action="/products/store" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= isset($product) ? htmlspecialchars($product['id']) : '' ?>">
            
        <h5 class="card-header"><i class="fas fa-info-circle me-2"></i>General Information</h5>
        <div class="card-body">
            <div class="form-group">
                <label class="form-label">Product Name</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                    <input type="text" class="form-control" placeholder="Enter product name" name="name" value="<?= isset($product) ? htmlspecialchars($product['name']) : '' ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Base Pricing</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input type="number" class="form-control" placeholder="0.00" name="price" value="<?= htmlspecialchars($product['price']) ?>" required min="0" step="0.01">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Stock</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-boxes"></i></span>
                            <input type="number" id="stockInput" class="form-control" placeholder="Enter stock" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" required min="0" step="1">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                    <input type="text" class="form-control" placeholder="Enter description" name="descriptions" value="<?= isset($product) ? htmlspecialchars($product['descriptions']) : '' ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Size</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="size" id="size-s" value="S" <?= isset($product) && $product['size'] == 'S' ? 'checked' : '' ?>>
                        <label class="btn btn-outline-primary" for="size-s">S</label>
                        
                        <input type="radio" class="btn-check" name="size" id="size-m" value="M" <?= isset($product) && $product['size'] == 'M' ? 'checked' : '' ?>>
                        <label class="btn btn-outline-primary" for="size-m">M</label>
                        
                        <input type="radio" class="btn-check" name="size" id="size-l" value="L" <?= isset($product) && $product['size'] == 'L' ? 'checked' : '' ?>>
                        <label class="btn btn-outline-primary" for="size-l">L</label>
                        
                        <input type="radio" class="btn-check" name="size" id="size-xl" value="XL" <?= isset($product) && $product['size'] == 'XL' ? 'checked' : '' ?>>
                        <label class="btn btn-outline-primary" for="size-xl">XL</label>
                        
                        <input type="radio" class="btn-check" name="size" id="size-xxl" value="XXL" <?= isset($product) && $product['size'] == 'XXL' ? 'checked' : '' ?>>
                        <label class="btn btn-outline-primary" for="size-xxl">XXL</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Gender</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="gender" id="gender-men" value="Men" <?= isset($product) && $product['gender'] == 'Men' ? 'checked' : '' ?>>
                        <label class="btn btn-outline-primary" for="gender-men">Men</label>
                        
                        <input type="radio" class="btn-check" name="gender" id="gender-women" value="Women" <?= isset($product) && $product['gender'] == 'Women' ? 'checked' : '' ?>>
                        <label class="btn btn-outline-primary" for="gender-women">Women</label>
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <label class="form-label">Product Image</label>
                <div class="image-preview card mb-2" id="imagePreview">
                    <img src="<?= !empty($product['image']) ? '/' . htmlspecialchars($product['image']) : '' ?>" alt="Product Image" id="previewImg" class="card-img-top" style="max-height: 150px; object-fit: contain; <?= empty($product['image']) ? 'display: none;' : '' ?>">
                </div>
                
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-upload"></i></span>
                    <input type="file" class="form-control" id="fileUpload" name="image" accept="image/*">
                </div>
                <small class="text-muted">Recommended: 800x800px, Max: 2MB</small>
            </div>
        </div>
        </section>
        
        <section class="pricing-stock card">
            <h5 class="card-header"><i class="fas fa-tags me-2"></i>Pricing And Stocks</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Discount</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                <input type="number" class="form-control" placeholder="Enter discount" name="discount" value="<?= htmlspecialchars($product['discount'] ?? '') ?>" min="0" step="0.01">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Discount Type</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                <input type="text" class="form-control" placeholder="Enter discount type" name="discount_type" value="<?= htmlspecialchars($product['discount_type'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-folder"></i></span>
                        <select id="categorySelect" class="form-select" name="category" required>
                            <option value="Uniform" <?= isset($product) && $product['category'] == 'Uniform' ? 'selected' : '' ?>>Uniform</option>
                            <option value="T-shirt" <?= isset($product) && $product['category'] == 'T-shirt' ? 'selected' : '' ?>>T-shirt</option>
                            <option value="Sport Clothes" <?= isset($product) && $product['category'] == 'Sport Clothes' ? 'selected' : '' ?>>Sport Clothes</option>
                            <option value="Clothes" <?= isset($product) && $product['category'] == 'Clothes' ? 'selected' : '' ?>>Clothes</option>
                            <option value="Shoes" <?= isset($product) && $product['category'] == 'Shoes' ? 'selected' : '' ?>>Shoes</option>
                            <option value="Bag" <?= isset($product) && $product['category'] == 'Bag' ? 'selected' : '' ?>>Bag</option>
                            <option value="Shirt" <?= isset($product) && $product['category'] == 'Shirt' ? 'selected' : '' ?>>Shirt
                            <option value="Shirt" <?= isset($product) && $product['category'] == 'Shirt' ? 'selected' : '' ?>>Shirt</option>
                            <option value="Nightwear" <?= isset($product) && $product['category'] == 'Nightwear' ? 'selected' : '' ?>>Nightwear</option>
                            
                            <!-- Student Material Option -->
                            <option value="Student Material" <?= isset($product) && $product['category'] == 'Student Material' ? 'selected' : '' ?>>Student Material</option>
                            
                            <!-- Other Category Option -->
                            <option value="Other" <?= isset($product) && $product['category'] == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Barcode</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                        <input id="productBarcode" type="text" class="form-control" name="barcode" value="<?= isset($product) ? htmlspecialchars($product['barcode']) : '' ?>"/>
                        <button id="generateBarcodeButton" type="button" class="btn btn-warning btn-sm">
                            <i class="fas fa-sync-alt"></i> Generate
                        </button>
                    </div>
                    <small class="text-muted">Enter a barcode or generate a new one</small>
                </div>

                <div class="barcode-container">
                    <canvas id="barcodeCanvas"></canvas>
                    <div id="errorMessage" class="barcode-error text-danger" style="display: none; font-size: 0.8rem;">
                        <i class="fas fa-exclamation-triangle me-1"></i> Invalid barcode! Please try again.
                    </div>
                </div>

                <!-- Additional Dropdown for Student Material (hidden by default) -->
                <div id="studentMaterialOptions" class="form-group" style="display: none;">
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
                <div id="otherCategoryInput" class="form-group" style="display: none;">
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
                            width: 1.5,
                            height: 30,
                            displayValue: true,
                            fontSize: 10
                        });
                        errorMessage.style.display = 'none';
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
                        width: 1.5,
                        height: 30,
                        displayValue: true,
                        fontSize: 10
                    });
                    errorMessage.style.display = 'none';
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
                    studentMaterialOptions.style.display = "block";
                    otherCategoryInput.style.display = "none";
                } else if (selectedCategory === "Other") {
                    studentMaterialOptions.style.display = "none";
                    otherCategoryInput.style.display = "block";
                } else {
                    studentMaterialOptions.style.display = "none";
                    otherCategoryInput.style.display = "none";
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
                            width: 1.5,
                            height: 30,
                            displayValue: true,
                            fontSize: 10
                        });
                    } catch (e) {
                        // Silent fail
                    }
                }
            });
        </script>

        <div class="actions mt-3">
            <button type="submit" class="add btn w-100">
                <i class="fas <?= isset($product) ? 'fa-save' : 'fa-plus-circle' ?> me-2"></i>
                <?= isset($product) ? 'Update Product' : 'Add Product' ?>
            </button>
        </div>
    </form>
</main>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Form validation script -->
<script>
    document.querySelector('form').addEventListener('submit', function(event) {
        let isValid = true;
        const requiredFields = this.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
                
                if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('invalid-feedback')) {
                    const feedback = document.createElement('div');
                    feedback.classList.add('invalid-feedback');
                    feedback.textContent = 'Required';
                    field.parentNode.appendChild(feedback);
                }
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            event.preventDefault();
            const firstInvalid = this.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
    
    document.querySelectorAll('input, select, textarea').forEach(element => {
        element.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
</script>
</div>

<?php else: $this->redirect("/"); endif; ?>
