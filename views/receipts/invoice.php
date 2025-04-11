<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?php echo htmlspecialchars($order->id); ?></title>
    <style>
        /* Similar styles as email, adjusted for print */
        body { font-family: Arial, sans-serif; color: #333; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; }
    </style>
</head>
<body>
    <h1>Invoice #<?php echo htmlspecialchars($order->id); ?></h1>
    <!-- Similar content as confirmation.php, without the PDF link -->
</body>
</html>