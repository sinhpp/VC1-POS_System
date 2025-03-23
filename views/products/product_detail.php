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
        <!-- Left Section: Image -->
        <div class="image-section">
            <img src="<?= isset($product) && !empty($product['image']) ? '/' . htmlspecialchars($product['image']) : 'default-image.jpg' ?>" alt="Product Image">
        </div>

        <!-- Right Section: Details -->
        <div class="details-section">
            <h2>PRODUCT DETAIL</h2>
            <p>Please review the details of the product</p>

            <!-- Two-column layout for details -->
            <div class="details-columns">
                <!-- Left Column -->
                <div class="left-column">
                    <div class="detail-group">
                        <label>Name:</label>
                        <p><?= isset($product) ? htmlspecialchars($product['name']) : 'N/A' ?></p>
                    </div>
                    <div class="detail-group">
                        <label>Size:</label>
                        <p><?= isset($product) ? htmlspecialchars($product['size']) : 'N/A' ?></p>
                    </div>
                    <div class="detail-group">
                        <label>Price:</label>
                        <p><?= isset($product) ? htmlspecialchars($product['price']) : 'N/A' ?></p>
                    </div>
                    <div class="detail-group">
                        <label>Barcode:</label>
                        <p><?= isset($product) ? htmlspecialchars($product['barcode']) : 'N/A' ?></p>
                    </div>
                    <div class="detail-group">
                        <label>Category:</label>
                        <p><?= isset($product) ? htmlspecialchars($product['category']) : 'N/A' ?></p>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="right-column">
                    <div class="detail-group">
                        <label>Discount:</label>
                        <p><?= isset($product) ? htmlspecialchars($product['discount']) : 'N/A' ?></p>
                    </div>
                    <div class="detail-group">
                        <label>Stock:</label>
                        <p><?= isset($product) ? htmlspecialchars($product['stock']) : 'N/A' ?></p>
                    </div>
                    <div class="detail-group">
                        <label>Description:</label>
                        <p><?= isset($product) ? htmlspecialchars($product['description']) : 'N/A' ?></p>
                    </div>
                </div>
            </div>

            <p class="disclaimer">This product is available while stocks last. Terms and conditions apply.</p>
        </div>
    </div>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            display: flex;
            border-radius: 15px;
            /* box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); */
            overflow: hidden;
            width: 90%;
            max-width: 1200px;
            margin: 20px;
        }

        .image-section {
            flex: 2;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            padding: 20px;
            border-radius: 15px 0, 15px, 0;
        }

        .image-section img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            /* box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); */
        }

        .details-section {
            flex: 2;
            padding: 40px;
            background: white;
        }

        h2 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }

        p {
            color: #777;
            text-align: center;
            margin-bottom: 30px;
      
            
        }

        .details-columns {
            display: flex;
            gap: 20px;
        }

        .left-column, .right-column {
            flex: 1;
        }

        .detail-group {
            
            display: flex;
            flex-direction: row;
            
            margin-bottom: 20px;
        }

        .detail-group label {
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
        }

        .detail-group p {
            margin: 0;
            padding: 10px;
         
          position: relative;
            top:-10px;
            border-radius: 8px;
            font-size: 14px;
            
            color: #333;
        }

        .disclaimer {
            font-size: 12px;
            color: #999;
            text-align: center;
            margin-top: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .image-section {
                padding: 20px 0;
            }

            .details-section {
                padding: 20px;
            }

            .details-columns {
                flex-direction: column;
            }
        }
    </style>
</body>
</html>