<?php
require_once(__DIR__ . '/Router.php');
require_once "Controllers/BaseController.php";
require_once "Database/Database.php";
require_once "Controllers/FormController.php";
require_once "Controllers/ForgotPassword.php";
require_once "Controllers/DashboardController.php";
require_once "Controllers/UserController.php";
require_once "Controllers/ProductController.php";
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
$route->get("/users/order/{id}", [OrderController::class, 'show']);
// Products

$route->get("/products", [ProductController::class, 'index']);
$route->get("/products/create", [ProductController::class, 'create']);
$route->post("/products/store", [ProductController::class, 'store']);
$route->get("/products/edit_pro/{id}", [ProductController::class, 'edit']);
$route->put("/products/update/{id}", [ProductController::class, 'update']);
$route->delete("/products/delete/{id}", [ProductController::class, 'delete']);

// Corrected this line
$route->post("/products/delete_all", [ProductController::class, 'deleteAllProducts']);
$route->get("/products/search", [ProductController::class, 'search']);

// Order
$route->get("/orders", [OrderController::class, 'index']);
$route->get("/orders/create", [OrderController::class, 'create']);
$route->post("/orders/store", [OrderController::class, 'store']);
$route->get("/orders/edit/{id}", [OrderController::class, 'edit']);
$route->put("/orders/update/{id}", [OrderController::class, 'update']);
$route->get("/orders/delete/{id}", [OrderController::class, 'delete']);
$route->get("/orders/search", [OrderController::class, 'search']);


// Order confirmation email receiver
$route->post("/email/order_confirmation", [OrderController::class, 'sendOrderConfirmation']);
$route->get("/email/order_confirmation", [OrderController::class, 'orderConfirmation']);


// Call the route method to process the request
$route->route();