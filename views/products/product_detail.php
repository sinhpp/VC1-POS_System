<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="image-section">
            <img src="<?= isset($product) && !empty($product['image']) ? '/' . htmlspecialchars($product['image']) : 'default-image.jpg' ?>" alt="Product Image">
        </div>
        <div class="form-section">
            <h2>PRODUCT DETAIL</h2>
            <p>Please review the details of the product</p>
            <form>
                <div class="input-group">
                    <label>Name:</label>
                    <input type="text" placeholder="Product Name" value="<?= isset($product) ? htmlspecialchars($product['name']) : '' ?>" readonly>
                </div>
                <div class="input-group">
                    <label>Barcode:</label>
                    <input type="text" placeholder="Product Barcode" value="<?= isset($product) ? htmlspecialchars($product['barcode']) : '' ?>" readonly>
                </div>
                <div class="input-group">
                    <label>Price:</label>
                    <input type="number" placeholder="Product Price" value="<?= isset($product) ? htmlspecialchars($product['price']) : '' ?>" readonly>
                </div>
                <div class="input-group">
                    <label>Stock:</label>
                    <input type="number" placeholder="Product Stock" value="<?= isset($product) ? htmlspecialchars($product['stock']) : '' ?>" readonly>
                </div>
                <div class="input-group">
                    <label>Discount:</label>
                    <input type="number" placeholder="Discount" value="<?= isset($product) ? htmlspecialchars($product['discount']) : '' ?>" readonly>
                </div>
                <div class="input-group">
                    <label>Description:</label>
                    <textarea placeholder="Product Description" readonly><?= isset($product) ? htmlspecialchars($product['description']) : '' ?></textarea>
                </div>
                <div class="input-group">
                    <label>Size:</label>
                    <input type="text" placeholder="Size" value="<?= isset($product) ? htmlspecialchars($product['size']) : '' ?>" readonly>
                </div>
                <div class="category-group">
                    <label>Category:</label>
                    <input type="text" placeholder="Category" value="<?= isset($product) ? htmlspecialchars($product['category']) : '' ?>" readonly>
                </div>
                <p class="disclaimer">This product is available while stocks last. Terms and conditions apply.</p>
            </form>
        </div>
    </div>

    <style>
       

        .container {
            position: relative;
            left:20%;
            top:200px;
            display: flex;
            gap:20px;
            width: 1200px;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .image-section {
            flex: 0.8;
            /* margin-top:-50%; */
            background: #000;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .image-section img {
            width: 65%;
            height: auto;
        }

        .form-section {
            flex: 1;
            padding: 30px;
            text-align: left;
            width: 20px;
        }

        h2 {
            text-align: center;
            font-size: 24px;
            letter-spacing: 1px;
        }

        p {
            text-align: center;
            color: #5b7a49;
            margin-bottom: 20px;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        .input-group label {
            font-weight: bold;
            margin-bottom: 5px;
            width: 50px;
        }

        .input-group input,
        .input-group textarea {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 12px;
            background-color: #f4f4f4;
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        .category-group {
            margin-top: 15px;
        }

        .category-group label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .category-group input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f4f4f4;
        }

        .disclaimer {
            font-size: 12px;
            color: #777;
            text-align: center;
            margin-top: 20px;
        }
        input{
            width: 150px;
        }

    </style>
</body>
</html>
