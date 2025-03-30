<?php
require_once __DIR__.'/Database/Database.php';
require_once __DIR__.'/Controllers/BarcodeController.php';

$db = new Database(); // Your existing database class
$controller = new BarcodeController($db);

$action = $_GET['action'] ?? '';

header('Content-Type: application/json');

switch ($action) {
    case 'scan':
        $barcode = $_GET['code'] ?? '';
        echo json_encode($controller->scan($barcode));
        break;
        
    case 'checkout':
        $items = json_decode($_POST['items'], true);
        $paymentType = $_POST['payment_type'] ?? '';
        echo json_encode($controller->checkout($items, $paymentType));
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>