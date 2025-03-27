
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

        <label>Discription</label>
        <input type="text" placeholder="Enter description" name="descriptions" value="<?= isset($product) ? htmlspecialchars($product['descriptions']) : '' ?>" required>

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
    <input type="file" name="image" accept="image/*">
    
    <!-- Image Preview -->
    <div class="image-preview" id="imagePreview">
        <img 
            src="" 
            alt="Product Image" 
            id="previewImg" 
            style="display: none; max-width: 150px;">
    </div>

</section>

<script>
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

    
</script>


</section>.  
    <div class="actions">
        <button type="submit" class="add"><?= isset($product) ? 'Update Product' : '➕ Add Product' ?></button>
    </div>
    
    </form>
</main>
<style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}

.container {
    max-width: 90%;
    height: auto;
    position: relative;
    margin-top:10%;
    border-radius: 8px;
 
 
}

h4 {
    font-size: 30px;
    margin-left:30%;
    color: #F868D4;
}

.actions button {
    padding: 8px 12px;
    border: none;
    cursor: pointer;
    margin-left: 10px;
    border-radius: 5px;
}

.save {
    background: gray;
    color: white;
}

.add {
    position: relative;
    width: 450px;
    margin-top:10px;
    margin-bottom:50px;

    background: green;
    color: white;
}

.grid-container {
  
    margin-left:20%;
    display: grid;
    grid-template-columns: 2fr 2fr;
    gap: 10px;
    height: 150vh;
}

section {

    padding: 10px;
    border-radius: 8px;
    box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;}



.h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
    
    color: #2087F7 !important; /* Force the color */
}
input, select, textarea {
    width: 100%;
    padding: 8px 20px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    
}

button {
    width: 100%;
    padding: 8px;
    background: green;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 5px;
}

button:hover {
    background: darkgreen;
}

.size-gender {
    display: flex;
    justify-content: space-between;
}

.size-options, .gender-options {
    display: flex;
    gap: 5px;
}

.size-options button, .gender-options button{
    padding: 5px;
    border: 1px solid #ccc;
    background: white;
    color: gray; /* Text color set to black */
    cursor: pointer;
    border-radius: 5px;
}
.size-options button {
    width: 45px;
}
.gender-options button{
    width: 70px;
}

.size-options .selected, .gender-options .selected {
    background: green;
    color: white; /* Keep selected text white for better contrast */
}

.upload-img input {
    display: block;
}

.image-preview {
    margin-top: 10px;
    width: 100%;
    height: 150px;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #eaeaea;
    border-radius: 5px;
    overflow: hidden;
}

.image-preview img {
    max-width: 100%;
    max-height: 100%;
}
</style>
<script>
        function selectGender(button) {
            document.querySelectorAll(".gender-options button").forEach(btn => btn.classList.remove("selected"));
            button.classList.add("selected");
        }

        function selectSize(button) {
            document.querySelectorAll(".size-options button").forEach(btn => btn.classList.remove("selected"));
            button.classList.add("selected");
        }

        document.querySelector("#fileUpload").addEventListener("change", function(event) {
            const file = event.target.files[0];
            const preview = document.querySelector("#previewImg");
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = "block";
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        });
    </script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<?php 
else: 
    $this->redirect("/"); 
endif;   
?>