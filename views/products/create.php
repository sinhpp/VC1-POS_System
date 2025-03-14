<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product</title>
    <link rel="stylesheet" href="/views/assets/css/addpro.css">
</head>
<body>
    <div class="container">
        <h2>Create New Product</h2>
        <?php
        session_start();
        if (isset($_SESSION['product_error'])) {
            echo "<p class='error'>{$_SESSION['product_error']}</p>";
            unset($_SESSION['product_error']);
        }
        ?>
        <form action="/products/store" method="POST">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="barcode">Barcode:</label>
            <input type="text" id="barcode" name="barcode" required>

            <label for="price">Price:</label>
            <input type="number" step="0.01" id="price" name="price" required>

            <label for="stock">Stock Quantity:</label>
            <input type="number" id="stock" name="stock" required>

            <button type="submit">Add Product</button>
        </form>
    </div>
</body>
</html>
