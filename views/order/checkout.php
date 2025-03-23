<?php
session_start();
require_once('../vendor/fpdf186/fpdf.php'); 
require_once('../layout.php');
require_once('../../Database/Database.php'); // Ensure DB connection

// If POST request, handle order processing
// If POST request, handle order processing
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['order'])) {
    $order = $_SESSION['order'];

    // Update stock in the database
    foreach ($order as $item) {
        $barcode = $item['barcode'];
        $quantitySold = $item['quantity'];

        // Update stock
        $updateStockQuery = "UPDATE products SET stock = stock - ? WHERE barcode = ?";
        if ($stmt = $conn->prepare($updateStockQuery)) {
            $stmt->bind_param("is", $quantitySold, $barcode);  // "is" for integer quantity and string barcode
            $stmt->execute();  // Execute the query
        }
    }

    // Clear the session order after processing
    unset($_SESSION['order']);

    // Redirect to success page after processing the order
    header("Location: /views/order/success.php");
    exit();
}


// Get order from session
$order = $_SESSION['order'] ?? [];
$totalPrice = 0;
foreach ($order as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

// Generate PDF if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    ob_start(); // Start output buffering

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Order Summary', 0, 1, 'C');
    $pdf->Ln(5);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(100, 10, 'Customer Name: ' . ($_POST['customerName'] ?? 'N/A'));
    $pdf->Ln();
    $pdf->Cell(100, 10, 'Shipping Address: ' . ($_POST['shippingAddress'] ?? 'N/A'));
    $pdf->Ln();
    $pdf->Cell(100, 10, 'Billing Address: ' . ($_POST['billingAddress'] ?? 'N/A'));
    $pdf->Ln();
    $pdf->Cell(100, 10, 'Contact Details: ' . ($_POST['contactDetails'] ?? 'N/A'));
    $pdf->Ln();
    $pdf->Cell(100, 10, 'Payment Method: ' . ($_POST['paymentMethod'] ?? 'N/A'));
    $pdf->Ln(10);

    // Order Details Table
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(90, 10, 'Item', 1);
    $pdf->Cell(30, 10, 'Quantity', 1);
    $pdf->Cell(30, 10, 'Price', 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    foreach ($order as $item) {
        $pdf->Cell(90, 10, $item['name'], 1);
        $pdf->Cell(30, 10, $item['quantity'], 1);
        $pdf->Cell(30, 10, '$' . number_format($item['price'] * $item['quantity'], 2), 1);
        $pdf->Ln();
    }

    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(100, 10, 'Total Price: $' . number_format($totalPrice, 2));

    ob_end_clean(); // Clean output buffer to prevent errors
    $pdf->Output('D', 'order_summary.pdf'); 

    unset($_SESSION['order']); // Clear session order
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="/views/assets/css/checkout.css">
</head>
<body>
    <div class="container-checkout">
        <div class="checkout">
            <div class="payment-form">
                <h2>Payment Method</h2>
                <form method="POST" action="checkout.php">
                    <input type="hidden" name="checkout" value="1">
                    
                    <label for="customerName">Customer Name:</label>
                    <input type="text" id="customerName" name="customerName" required>

                    <label for="shippingAddress">Shipping Address:</label>
                    <input type="text" id="shippingAddress" name="shippingAddress" required>

                    <label for="billingAddress">Billing Address:</label>
                    <input type="text" id="billingAddress" name="billingAddress" required>

                    <label for="contactDetails">Contact Details:</label>
                    <input type="text" id="contactDetails" name="contactDetails" required>

                    <label for="paymentMethod">Payment Method:</label>
                    <select id="paymentMethod" name="paymentMethod">
                        <option value="Mastercard">Mastercard</option>
                        <option value="Visa">Visa</option>
                    </select>

                    <button type="submit" class="btn">Order</button>
                </form>
            </div>

            <div class="order-summary">
                <h2>Order Summary</h2>
                <?php if (empty($order)): ?>
                    <p>No items in your order.</p>
                <?php else: ?>
                    <div class="order-items">
                        <?php foreach ($order as $item): ?>
                            <div class="order-item">
                                <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                                <div>
                                    <p><strong><?php echo $item['name']; ?></strong></p>
                                    <p>Quantity: <?php echo $item['quantity']; ?></p>
                                </div>
                                <span>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                            </div>
                        <?php endforeach; ?>
                        <hr>
                        <div class="totals">
                            <p>Product Total: <span>$<?php echo number_format($totalPrice, 2); ?></span></p>
                            <p>Discount (6%): <span>($12.25)</span></p>
                            <p>Delivery Fee: <span>Free</span></p>
                        </div>
                        <hr>
                        <div class="total">
                            <h3>Total: <span>$<?php echo number_format($totalPrice - 12.25, 2); ?></span></h3>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
 