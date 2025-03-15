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
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background: #343a40;
            color: white;
            padding: 10px;
        }
        .sidebar .nav-link {
            color: white;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .sidebar .nav-link:hover {
            background: #495057;
            border-radius: 5px;
        }
        .material-icons {
            font-size: 20px;
        }
        /* Table Styles */
        .table {
            width: 100%;
            margin-left: 70px;
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
<body>
<title>Add New Product</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            margin-left: 200px;
        }
        .container {
            max-width: 90%;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        header input[type="search"] {
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        header button {
            background: green;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
        }
        main {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        section {
            background: #fafafa;
            padding: 15px;
            border-radius: 8px;
        }
        h3 {
            margin-top: 0;
        }
        input, textarea, button {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .size-gender button {
            width: auto;
            display: inline-block;
            margin-right: 5px;
            background: #ddd;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .upload-img img {
            max-width: 100px;
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h2>Overview</h2>
            <input type="search" placeholder="Search">
            <button>Add Product</button>
        </header>
        
        <main>
            <section class="product-info">
                <h3>General Information</h3>
                <label>Name Product</label>
                <input type="text" placeholder="Puffer Jacket With Pocket Detail">
                <label>Description Product</label>
                <textarea>...</textarea>
                
                <div class="size-gender">
                    <div>
                        <h4>Size</h4>
                        <button>XS</button><button>S</button><button>M</button>
                        <button>L</button><button>XL</button><button>XXL</button>
                    </div>
                    <div>
                        <h4>Gender</h4>
                        <button>Men</button><button>Women</button><button>Unisex</button>
                    </div>
                </div>
            </section>
            
            <section class="pricing-stock">
                <h3>Pricing And Stock</h3>
                <label>Base Pricing</label>
                <input type="text" value="$47.15">
                <label>Stock</label>
                <input type="number" value="77">
                <label>Discount</label>
                <input type="text" value="10%">
                <label>Discount Type</label>
                <input type="text" value="Chinese New Year Discount">
            </section>
            
            <section class="upload-img">
                <h3>Upload Img</h3>
                <input type="file" id="imageUpload">
                <img id="imagePreview" src="jacket.png" alt="Jacket Preview">
            </section>
            
  
    
    <section class="category">
    <h3>Category</h3>
    
    <!-- Category Dropdown -->
    <select id="categorySelect">
        <option value="Uniform">Uniform</option>
        <option value="T-shirt">T-shirt</option>
        <option value="Sport clothes">Sport clothes</option>
        <option value="Dresses">Dresses</option>
        <option value="Other">Other</option>
    </select>

    <!-- Hidden input for custom category -->
    <input type="text" id="customCategory" placeholder="Enter category" style="display: none;">

    <button id="addCategory">Add Category</button>
</section>

<style>
    .category {
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 250px;
    }
    select, input, button {
        padding: 8px;
        font-size: 16px;
    }
    input {
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    button {
        background-color: #28a745;
        color: white;
        border: none;
        cursor: pointer;
    }
    button:hover {
        background-color: #218838;
    }
</style>

<script>
    document.getElementById('categorySelect').addEventListener('change', function () {
        let customCategoryInput = document.getElementById('customCategory');
        if (this.value === 'Other') {
            customCategoryInput.style.display = 'block';
            customCategoryInput.focus();
        } else {
            customCategoryInput.style.display = 'none';
            customCategoryInput.value = ''; // Clear input if another category is selected
        }
    });

    document.getElementById('addCategory').addEventListener('click', function () {
        let selectedCategory = document.getElementById('categorySelect').value;
        let customCategory = document.getElementById('customCategory').value;

        if (selectedCategory === 'Other' && customCategory.trim() === '') {
            alert('Please enter a category name!');
        } else {
            let categoryToAdd = selectedCategory === 'Other' ? customCategory : selectedCategory;
            alert('Category added: ' + categoryToAdd);
            // Here you can send the categoryToAdd value to your backend if needed
        }
    });
    </script>

        </main>
    </div>

    <script>
        document.getElementById('imageUpload').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
<?php 
else: 
    $this->redirect("/"); 
endif;   
?>