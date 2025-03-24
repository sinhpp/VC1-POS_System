<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Controllers\ReceiptController;

// Check if receipt ID is provided
if (empty($_GET['id'])) {
    header('Location: /dashboard/index.php');
    exit;
}

$receiptId = $_GET['id'];

// Download receipt
$receiptController = new ReceiptController();
$receiptController->downloadReceipt($receiptId);

