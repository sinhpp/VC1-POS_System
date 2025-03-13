<?php
require_once "Router.php";
require_once "Controllers/BaseController.php";
require_once "Database/Database.php";
require_once "Controllers/WelcomeController.php";
require_once "Controllers/FormController.php";
require_once "Controllers/UserController.php";
require_once "Controllers/ProductController.php";
// require_once "Controllers/ForgotController.php";

// Create an instance of Router
$route = new Router();

// Define the routes
$route->get("/", [WelcomeController::class, 'welcome']);
$route->get("/", [FormController::class, 'form']); // Set as homepage

$route->post("/form/authenticate", [UserController::class, 'authenticate']);


$route->get("/users", [UserController::class, 'index']);
$route->get("/users/create", [UserController::class, 'create']);
$route->post("/users/store", [UserController::class, 'store']);

// $route->get('/forgot_password',[ForgotController:: class,'forgot_password'] );
//products

$route->get("/products", [ProductController::class, 'index']);
$route->get("/products/create", [ProductController::class, 'create']);
$route->post("/products/store", [ProductController::class, 'store']);
$route->delete("/products/delete/{id}", [ProductController::class, 'delete']);


// Call the route method to process the request
$route->route();