<?php
// Router.php (updated)

// Include necessary controller files (assuming they are in the '../Controllers/' directory)
require_once dirname(__DIR__) . '/Controllers/OrderController.php';
require_once dirname(__DIR__) . '/Controllers/DashboardController.php';
require_once dirname(__DIR__) . '/Controllers/FormController.php';
require_once dirname(__DIR__) . '/Controllers/UserController.php';
require_once dirname(__DIR__) . '/Controllers/ProductController.php';
require_once dirname(__DIR__) . '/Controllers/ProductScanController.php';
require_once dirname(__DIR__) . '/Controllers/ProductCashierController.php';

// Instantiate the Router class correctly
$route = new Router();

// Welcome Routes
$route->get("/dashboard", [DashboardController::class, 'show']);
$route->get("/", [FormController::class, 'form']); // Homepage

// User Routes
$route->post("/form/authenticate", [UserController::class, 'authenticate']);
$route->get("/users", [UserController::class, 'index']);
$route->get("/users/create", [UserController::class, 'create']);
$route->post("/users/store", [UserController::class, 'store']);
$route->delete("/users/delete/{id}", [UserController::class, 'delete']);
$route->get("/users/logout", [UserController::class, 'logout']);
$route->get("/users/edit/{id}", [UserController::class, 'edit']);
$route->post("/users/update/{id}", [UserController::class, 'update']);
$route->get("/users/view_user/{id}", [UserController::class, 'detail']);

// Product Routes
$route->get("/products", [ProductController::class, 'index']);
$route->get("/products/create", [ProductController::class, 'create']);
$route->post("/products/store", [ProductController::class, 'store']);
$route->get("/products/edit_pro/{id}", [ProductController::class, 'edit']);
$route->put("/products/update/{id}", [ProductController::class, 'update']);
$route->delete("/products/delete/{id}", [ProductController::class, 'delete']);
$route->get("/products/product_detail/{id}", [ProductController::class, 'detail']);
$route->post("/products/delete_all", [ProductController::class, 'deleteAllProducts']);

// Order Routes (Unified)
$route->get("/order", [ProductScanController::class, 'index']);
$route->post("/order/add", [ProductScanController::class, 'add']);
$route->get("/order/checkout", [OrderController::class, 'checkout']);
$route->post("/order/process-checkout", [ProductScanController::class, 'processCheckout']);
$route->post("/order/scan", [ProductScanController::class, 'scan']);
$route->post("/order/delete", [ProductScanController::class, 'delete']);
$route->post("/order/print-receipt", [ProductScanController::class, 'printReceipt']);

// Product Cashier Route
$route->get("/product_cashier/product", [ProductCashierController::class, 'index']);

// Execute the routing
$route->route();