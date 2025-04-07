<?php
// views/product.php
require_once '../Models/ProductsModel.php';
require_once '../Database/db_connection.php';

$barcode = $_GET['barcode'] ?? '';

if (empty($barcode)) {
    header('Location: /scan.php');
    exit;
}

$productModel = new ProductModel($conn);
$product = $productModel->getProductByBarcode($barcode);

if (!$product) {
    header('Location: /scan.php?error=Product+not+found');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
    <div class="product-container">
        <h1><?= htmlspecialchars($product['name']) ?></h1>
        
        <div class="product-image">
            <img src="/uploads/products/<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        </div>
        
        <div class="product-details">
            <p><strong>Price:</strong> $<?= number_format($product['price'], 2) ?></p>
            <p><strong>Barcode:</strong> <?= htmlspecialchars($product['barcode']) ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($product['description']) ?></p>
        </div>
        
        <a href="/scan.php" class="btn btn-primary">Scan Another Product</a>
    </div>
</body>
</html>