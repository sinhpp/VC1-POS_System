<?php
require_once(__DIR__ . '/Router.php');
require_once "Controllers/BaseController.php";
require_once "Database/Database.php";
require_once "Controllers/FormController.php";
require_once "Controllers/ForgotPassword.php";
require_once "Controllers/DashboardController.php";
require_once "Controllers/UserController.php";
require_once "Controllers/ProductController.php";
require_once "Controllers/ProductCashierController.php";
// require_once 'Controllers/OrderController.php';

// Create an instance of Router
$route = new Router();

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

// Products

$router->get('/products', [ProductController::class, 'index']);
$router->get('/products/create', [ProductController::class, 'create']);
$router->post('/products/store', [ProductController::class, 'store']);
$router->get('/products/edit_pro/{id}', [ProductController::class, 'edit']);
$router->get('/products/product_detail/{id}', [ProductController::class, 'detail']);
$router->put('/products/update/{id}', [ProductController::class, 'update']);
$router->delete('/products/delete/{id}', [ProductController::class, 'delete']);
$router->post('/products/delete_all', [ProductController::class, 'deleteAllProducts']);

// Product Cashier
$route->get("/product_cashier/product", [ProductCashierController::class, 'index']);

// Call the route method to process the request
$route->route();