<?php
require_once __DIR__ . '/../Database/Database.php'; // Corrected path
require_once __DIR__ . '/Router/Router.php';
require_once __DIR__ . '/Controllers/FormController.php';
require_once __DIR__ . '/Controllers/DashboardController.php';
require_once __DIR__ . '/Controllers/UserController.php';
require_once __DIR__ . '/Controllers/ProductController.php';
require_once __DIR__ . '/Controllers/ReceiptController.php'; // Added

$router = new Router();

$router->get('/dashboard', [DashboardController::class, 'show']);
$router->get('/', [FormController::class, 'form']);
$router->post('/form/authenticate', [UserController::class, 'authenticate']);

$router->get('/users', [UserController::class, 'index']);
$router->get('/users/create', [UserController::class, 'create']);
$router->post('/users/store', [UserController::class, 'store']);
$router->delete('/users/delete/{id}', [UserController::class, 'delete']);
$router->get('/users/logout', [UserController::class, 'logout']);
$router->get('/users/edit/{id}', [UserController::class, 'edit']);
$router->post('/users/update/{id}', [UserController::class, 'update']);

$router->get('/products', [ProductController::class, 'index']);
$router->get('/products/create', [ProductController::class, 'create']);
$router->post('/products/store', [ProductController::class, 'store']);
$router->get('/products/edit_pro/{id}', [ProductController::class, 'edit']);
$router->get('/products/product_detail/{id}', [ProductController::class, 'detail']);
$router->put('/products/update/{id}', [ProductController::class, 'update']);
$router->delete('/products/delete/{id}', [ProductController::class, 'delete']);
$router->post('/products/delete_all', [ProductController::class, 'deleteAllProducts']);

// Add Receipt Routes
$router->post('/receipt/process', [ReceiptController::class, 'processPurchase']);
$router->get('/receipt/download/{id}', [ReceiptController::class, 'downloadReceipt']);

try {
    $router->route();
} catch (Exception $e) {
    http_response_code(500);
    echo "Internal Server Error: " . $e->getMessage();
}