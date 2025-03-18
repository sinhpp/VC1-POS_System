<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Order #12345</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
        .receipt {
            border: 1px solid #000;
            padding: 20px;
            margin: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="receipt">
        
        <h3 class="text-center">Receipt</h3>
        <p><strong>Order ID:</strong> #12345</p>
        <p><strong>Date:</strong> 2025-03-18</p>
        
        <h4>Customer Details</h4>
        <p><strong>Name:</strong> John Doe</p>
        <p><strong>Email:</strong> johndoe@example.com</p>

        <h4>Product Details</h4>
        <p><strong>Product Name:</strong> Sample Product</p>
        <p><strong>Price:</strong> $50.00</p>
        <p><strong>Quantity:</strong> 2</p>
        <p><strong>Total Price:</strong> $100.00</p>

        <div class="text-center no-print">
            <button class="btn btn-primary" onclick="window.print()">Print Receipt</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
