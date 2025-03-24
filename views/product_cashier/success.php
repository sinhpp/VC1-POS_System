<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Controllers\ReceiptController;
use Models\Order;

// Check if order ID is provided
if (empty($_GET['order_id'])) {
    header('Location: /products/index.php');
    exit;
}

$orderId = $_GET['order_id'];

// Get order details
$order = new Order();
$orderData = $order->findById($orderId);

if (!$orderData) {
    header('Location: /products/index.php');
    exit;
}

// Get receipt data
$receiptController = new ReceiptController();
$receiptData = $receiptController->getReceiptData($orderId);

// Include header
include __DIR__ . '/../layouts/header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <h1 class="card-title text-success mb-4">
                        <i class="bi bi-check-circle-fill"></i> Thank You for Your Purchase!
                    </h1>
                    
                    <p class="lead">Your order has been successfully processed.</p>
                    
                    <div class="alert alert-info mb-4">
                        <p class="mb-1"><strong>Order ID:</strong> <?php echo $orderId; ?></p>
                        <p class="mb-1"><strong>Transaction ID:</strong> <?php echo $receiptData['transaction_id']; ?></p>
                        <p class="mb-0"><strong>Total Amount:</strong> $<?php echo number_format($receiptData['total'], 2); ?></p>
                    </div>
                    
                    <?php if (!empty($receiptData['customer']['email'])): ?>
                    <p>A receipt has been sent to <strong><?php echo $receiptData['customer']['email']; ?></strong></p>
                    <?php endif; ?>
                    
                    <div class="mt-4">
                        <a href="/receipts/download.php?id=<?php echo $receiptData['receipt_id']; ?>" class="btn btn-primary me-2">
                            <i class="bi bi-download"></i> Download Receipt
                        </a>
                        <a href="/products/index.php" class="btn btn-outline-secondary">
                            <i class="bi bi-cart"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include __DIR__ . '/../layouts/footer.php';
?>

