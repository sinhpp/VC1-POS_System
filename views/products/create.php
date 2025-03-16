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
    <title>Admin Panel</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        /* Sidebar Styles */
        .sidebar {
            font-family: "Poppins", sans-serif;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background-color: #343a40;
            padding: 15px;
            color: white;
        }
        .sidebar .nav-link {
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
        }
        .sidebar .nav-link:hover {
            background-color: #495057;
            border-radius: 5px;
        }
        .material-icons {
            font-size: 20px;
        }
        /* Content area */
        .content {
            margin-left: 10px; /* Adjust based on sidebar width */
            padding: 50px;
        }
        /* Table Styles */
        .table {
            width: 80%;
            margin-left: 30px;
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
    </style>
</head>
<body>

    <!-- Sidebar -->
     
    <nav class="sidebar">
        <h3 class="text-center">Admin Panel</h3>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item"><a href="/dashboard" class="nav-link"><i class="material-icons">dashboard</i> Dashboard</a></li>
            <li><a href="/users" class="nav-link"><i class="material-icons">group</i> Users</a></li>
            <li><a href="/settings" class="nav-link"><i class="material-icons">settings</i> Settings</a></li>
            <li><a href="/" class="nav-link"><i class="material-icons">logout</i> Logout</a></li>
            <li><a href="/products" class="nav-link"><i class="material-icons">shopping_cart</i> Products</a></li>
        </ul>
    </nav>
<!-- end siderbar -->
 
<div class="container">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <header>
        <h2> Add New Product</h2>
    </header>

    <main class="grid-container">
        <section class="general-info">
            <form action="/products/store" method="POST" enctype="multipart/form-data">
                <h3>General Information</h3>
                <label>Name Product</label>
                <input type="text" placeholder="Enter product name" name="name" required>
                
                <label>Description Product</label>
                <textarea placeholder="Enter product description" name="description" required></textarea>
                
                <div class="size-gender">
                    <div class="size">
                        <label>Size</label>
                        <div class="size-options">
                            <button type="button" onclick="selectSize(this)">S</button>
                            <button type="button" onclick="selectSize(this)">M</button>
                            <button type="button" class="selected" onclick="selectSize(this)">L</button>
                            <button type="button" onclick="selectSize(this)">XL</button>
                            <button type="button" onclick="selectSize(this)">XXL</button>
                        </div>
                    </div>
                    <div class="gender">
                        <label>Gender</label>
                        <div class="gender-options">
                            <button type="button" onclick="selectGender(this)" class="selected">Men</button>
                            <button type="button" onclick="selectGender(this)">Women</button>
                          
                        </div>
                    </div>
                </div>
        </section>

     <section class="pricing-stock">
        <h3>Pricing And Stock</h3>
        <label>Base Pricing</label>
        <input type="number" placeholder="$0.00" name="price" required>
        
        <label>Stock</label>
        <input type="number" placeholder="Enter stock quantity" name="stock" required>

        <label>Discount</label>
        <input type="text" placeholder="Enter discount" name="discount">

        <label>Discount Type</label>
        <input type="text" placeholder="Enter discount type" name="discount_type">

        <label>Barcode:</label>
        <input type="text" class="form-control" name="barcode"/>
        <br />
        
    </section>
        <!-- Upload Image -->
        <section class="upload-img">
            <h3>Upload Img</h3>
            <input type="file" id="fileUpload" name="image" accept="image/*" required>
            <div class="image-preview" id="imagePreview">
                <img src="" alt="Product Image" id="previewImg" style="display:none;">
            </div>
        </section>

        <!-- Category -->
        <section class="category">
            <h3>Category</h3>
            <select id="categorySelect" name="category" required>
                <option value="Uniform">Uniform</option>
                <option value="T-shirt">T-shirt</option>
                <option value="Sport Clothes">Sport Clothes</option>
                <option value="Dresses">Dresses</option>
                <option value="Other">Other</option>
            </select>
            
        </section>
        <div class="actions">
            <button type="submit" class="add">➕ Add Product</button>
        </div>
        
        </form>
    </main>
</div>
<style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}

body {
    background: #f4f4f4;
  
}

.container {
    max-width: 80%;
    height: auto;
    margin-left: 400px; /* Sidebar adjustment */
    background: white;
    position: relative;
    left:-10%;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
   
}

header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
  
}

header h2 {
    font-size: 30px;
    margin-left:30%;
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
    left:600px;
    background: green;
    color: white;
}

.grid-container {
    display: grid;
    grid-template-columns: 2fr 1.5fr;
    gap: 10px;
}

section {
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;}

h3 {
    margin-bottom: 10px;
}

input, select, textarea {
    width: 100%;
    padding: 8px;
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


<?php 
else: 
    $this->redirect("/"); 
endif;   
?>