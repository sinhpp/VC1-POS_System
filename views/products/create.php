
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
    <title>POS Products</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <style>
         body {
            font-family: Arial, sans-serif;
            display: block;

            justify-content: center;
            align-items: center;
            height: 100vh;
         
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
        }
        .btn-warning { background-color: #ffc107; color: black; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn:hover { background-color: #495057; }

        .barcode-container {
            margin-top: 20px;
            text-align: center;
        }
        .barcode-error {
            color: red;
            display: none;
        }
        
    </style>


</head>
<body>
 

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

       
<div class="container">
<h4> Add New Product</h4>
    <main class="grid-container">
    <section class="general-info">
        <form action="/products/store" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= isset($product) ? htmlspecialchars($product['id']) : '' ?>">
            
        <h5>General Information</h5>
        <label>Name Product</label>
        <input type="text" placeholder="Enter product name" name="name" value="<?= isset($product) ? htmlspecialchars($product['name']) : '' ?>" required>

        <label>Base Pricing</label>
        <input type="number" placeholder="$0.00" name="price" required min="0" step="0.01">

        <label>Stock</label>
        <input type="number" placeholder="Enter stock quantity" name="stock" required min="0" step="1">

        
        <div class="size-gender">
        <div class="size">
            <label>Size</label>
            <div class="size-options">
                <button type="button" onclick="selectSize(this, 'S')">S</button>
                <button type="button" onclick="selectSize(this, 'M')">M</button>
                <button type="button" class="selected" onclick="selectSize(this, 'L')">L</button>
                <button type="button" onclick="selectSize(this, 'XL')">XL</button>
                <button type="button" onclick="selectSize(this, 'XXL')">XXL</button>
            </div>
            <input type="hidden" name="size" id="size" value="L"> <!-- Default value -->
        </div>
        <div class="gender">
            <label>Gender</label>
            <div class="gender-options">
                <button type="button" onclick="selectGender(this, 'Men')" class="selected">Men</button>
                <button type="button" onclick="selectGender(this, 'Women')">Women</button>
            </div>
            <input type="hidden" name="gender" id="gender" value="Men"> <!-- Default value -->
        </div>

        
    </div>
    

        </section>
        <script>
    function selectSize(button, size) {
        // Remove 'selected' class from all size buttons
        document.querySelectorAll('.size-options button').forEach(btn => {
            btn.classList.remove('selected');
        });

        // Add 'selected' class to the clicked button
        button.classList.add('selected');

        // Update the hidden input value
        document.getElementById('size').value = size;
    }

    function selectGender(button, gender) {
        // Remove 'selected' class from all gender buttons
        document.querySelectorAll('.gender-options button').forEach(btn => {
            btn.classList.remove('selected');
        });

        // Add 'selected' class to the clicked button
        button.classList.add('selected');

        // Update the hidden input value
        document.getElementById('gender').value = gender;
    }
</script>

<script>
    function selectSize(button, size) {
        // Remove 'selected' class from all size buttons
        document.querySelectorAll('.size-options button').forEach(btn => {
            btn.classList.remove('selected');
        });

        // Add 'selected' class to the clicked button
        button.classList.add('selected');

        // Update the hidden input value
        document.getElementById('size').value = size;
        console.log('Selected Size:', size); // Debugging
    }

    function selectGender(button, gender) {
        // Remove 'selected' class from all gender buttons
        document.querySelectorAll('.gender-options button').forEach(btn => {
            btn.classList.remove('selected');
        });

        // Add 'selected' class to the clicked button
        button.classList.add('selected');

        // Update the hidden input value
        document.getElementById('gender').value = gender;
        console.log('Selected Gender:', gender); // Debugging
    }
    
</script>


        <section class="pricing-stock">
    <h5>Pricing And Stocks</h5>
   

    

    <label>Discount</label>
    <input type="number" placeholder="Enter discount" name="discount" min="0" step="0.01">

    <label>Discount Type</label>
    <input type="text" placeholder="Enter discount type" name="discount_type">

    <section class="category">
    <label>Category</label>
    <select id="categorySelect" name="category" required>
        <!-- General Categories -->
        <div class="cat"></div>
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
    
    <label>Barcode:</label>
    <input id="productBarcode" type="text" class="form-control" name="barcode" value="<?= isset($product) ? htmlspecialchars($product['barcode']) : '' ?>"/>
        <button id="generateBarcodeButton" type="button" class="btn btn-warning mt-2">Generate Barcode</button>

        <div class="barcode-container">
            <canvas id="barcodeCanvas"></canvas>
            <div id="errorMessage" class="barcode-error">Invalid barcode! Please try again.</div>
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
  
</section>


<script>
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
</script>

    </section>

<!-- Category -->


    <!-- Additional Dropdown for Student Material (hidden by default) -->
    <select id="studentMaterialOptions" name="student_material" style="display: none;">
        <option value="Book" <?= isset($product) && $product['category'] == 'Book' ? 'selected' : '' ?>>Book</option>
        <option value="Pen" <?= isset($product) && $product['category'] == 'Pen' ? 'selected' : '' ?>>Pen</option>
        <option value="Ruler" <?= isset($product) && $product['category'] == 'Ruler' ? 'selected' : '' ?>>Ruler</option>
    </select>

    <!-- Input for "Other" Category (hidden by default) -->
    <div id="otherCategoryInput" style="display: none;">
        <label for="otherInput">Please specify:</label>
        <input type="text" id="otherInput" name="other_category_input" placeholder="Enter other category" value="<?= isset($product) && $product['category'] == 'Other' ? htmlspecialchars($product['other_category_input'] ?? '') : '' ?>">
    </div>

    
<section class="upload-img">
    <h5>Upload Image</h5>
    
    <!-- File Input -->
    <input type="file" id="fileUpload" name="image" accept="image/*" required>
    
    <!-- Image Preview -->
    <div class="image-preview" id="imagePreview">
        <img 
            src="" 
            alt="Product Image" 
            id="previewImg" 
            style="display: none; max-width: 150px;">
    </div>

    <script>
       // Toggle functionality for URL and file input
    const toggleUrl1 = document.getElementById('toggleUrl1');
    const toggleFile1 = document.getElementById('toggleFile1');
    const productImageUrl1 = document.getElementById('productImageUrl1');
    const imageInput1 = document.getElementById('imageInput1');
    const previewImg1 = document.getElementById('previewImg1');

    toggleUrl1.addEventListener('click', () => {
        productImageUrl1.classList.remove('hidden');
        imageInput1.classList.add('hidden');
        previewImg1.style.display = 'none'; // Hide preview when switching to URL
    });

    toggleFile1.addEventListener('click', () => {
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

    // Handle saving the product (ensure to use correct layoutNum)
    function saveProductHandler(layoutNum) {
        const imageUrl = document.getElementById(`productImageUrl${layoutNum}`).value;
        const imageFile = document.getElementById(`imageInput${layoutNum}`).files[0];
        let image;

        if (imageUrl && !imageFile) {
            image = imageUrl;
        } else if (imageFile && !imageUrl) {
            image = URL.createObjectURL(imageFile); // Temporary URL for display
        } else if (!imageUrl && !imageFile) {
            alert('Please provide either an image URL or upload an image.');
            return;
        } else {
            alert('Please choose only one image source (URL or file upload).');
            return;
        }

        const fields = {
            image,
            // ... other field extractions ...
        };

        // Save the product logic...
    }

    document.getElementById('saveProductButton1').addEventListener('click', () => saveProductHandler(1));

    function saveProductHandler(layoutNum) {
    const imageUrl = document.getElementById(`productImageUrl${layoutNum}`).value;
    const imageFile = document.getElementById(`imageInput${layoutNum}`).files[0];
    let image;

    if (imageUrl && !imageFile) {
        image = imageUrl;
    } else if (imageFile && !imageUrl) {
        image = URL.createObjectURL(imageFile); // Temporary URL for display
    } else if (!imageUrl && !imageFile) {
        alert('Please provide either an image URL or upload an image.');
        return;
    } else {
        alert('Please choose only one image source (URL or file upload).');
        return;
    }

    const fields = {
        image,
        name: document.getElementById(`productName${layoutNum}`).value,
        brand: document.getElementById(`productBrand${layoutNum}`).value,
        barcode: document.getElementById(`productBarcode${layoutNum}`).value,
        price: parseFloat(document.getElementById(`productPrice${layoutNum}`).value),
        stock: parseInt(document.getElementById(`productStock${layoutNum}`).value),
        category: document.getElementById(`productCategory${layoutNum}`).value,
        description: document.getElementById(`productDescription${layoutNum}`).value,
        discount: parseFloat(document.getElementById(`productDiscount${layoutNum}`).value) || 0,
        discountType: document.getElementById(`productDiscountType${layoutNum}`).value,
        size: document.getElementById(`productSize${layoutNum}`).value, // Check this line
        gender: document.getElementById(`productGender${layoutNum}`).value
    };

    if (fields.name && fields.brand && fields.barcode && fields.price && fields.stock >= 0 && fields.category && fields.size) {
        products.push(fields);
        console.log('Product saved:', fields); // For debugging; replace with actual save logic
        
        // Clear form logic...
    } else {
        alert('Please fill out all required fields correctly.');
    }
}
    </script>
</body>
</html>
<?php 
else: 
    $this->redirect("/"); 
endif;   
?>