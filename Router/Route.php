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

$route->get("/users", [UserController::class, 'index']);
$route->get("/users/create", [UserController::class, 'create']);
$route->post("/users/store", [UserController::class, 'store']);
$route->delete("/users/delete/{id}", [UserController::class, 'delete']);
$route->get("/users/logout", [UserController::class, 'logout']);
$route->get("/users/create", [UserController::class, 'createuser']);
$route->post("/users/storeuser", [UserController::class, 'storeuser']);
$route->get("/users/edit/{id}", [UserController::class, 'edit']);
$route->post("/users/update/{id}", [UserController::class, 'update']); 
$route->get("users/view_user/{id}", [UserController::class, 'detail']);

// Products

$route->get("/products", [ProductController::class, 'index']);
$route->get("/products/create", [ProductController::class, 'create']);
$route->post("/products/store", [ProductController::class, 'store']);
$route->get("/products/edit_pro/{id}", [ProductController::class, 'edit']);
$route->get("products/product_detail/{id}", [ProductController::class, 'detail']);

$route->put("/products/update/{id}", [ProductController::class, 'update']);
$route->delete("/products/delete/{id}", [ProductController::class, 'delete']);

// Corrected this line
$route->post("/products/delete_all", [ProductController::class, 'deleteAllProducts']);

// Add Receipt Routes
$router->post('/receipt/process', [ReceiptController::class, 'processPurchase']);
$router->get('/receipt/download/{id}', [ReceiptController::class, 'downloadReceipt']);

try {
    $router->route();
} catch (Exception $e) {
    http_response_code(500);
    echo "Internal Server Error: " . $e->getMessage();
}