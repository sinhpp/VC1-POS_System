<?php
require_once(__DIR__ . '/Router.php');
require_once "Controllers/BaseController.php";
require_once "Database/Database.php";
require_once "Controllers/FormController.php";
require_once "Controllers/ForgotPassword.php";
require_once "Controllers/DashboardController.php";
require_once "Controllers/UserController.php";
require_once "Controllers/ProductController.php";
require_once 'Controllers/ProductScanController.php';
require_once "Controllers/ProductCashierController.php";
// require_once 'Controllers/OrderController.php';

// Create an instance of Router
$route = new Router();

// Welcome
$route->get("/dashboard", [DashboardController::class, 'show']);

// Define the routes
$route->get("/", [FormController::class, 'form']); // Set as homepage
$route->post("/form/authenticate", [UserController::class, 'authenticate']);

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
// Product Scanning Routes
$route->get("/order", [ProductScanController::class, 'index']);
$route->post("/order/add", [ProductScanController::class, 'add']);
$route->get("/product/checkout", [ProductScanController::class, 'checkout']);
$route->post("/product/process-checkout", [ProductScanController::class, 'processCheckout']);
$route->post("/productDetails", [ProductScanController::class, 'scan']); // For scanning
$route->post("/order/add", [ProductScanController::class, 'add']); // Already correct
$route->post("/product/delete", [ProductScanController::class, 'delete']); // Already correct
$route->get("/order/print-receipt", [ProductScanController::class, 'printReceipt']);

// Product Cashier
$route->get("/product_cashier/product", [ProductCashierController::class, 'index']);


// Call the route method to process the request
$route->route();