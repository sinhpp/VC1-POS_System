<?php
require_once "Router.php";
require_once "Controllers/BaseController.php";
require_once "Database/Database.php";
// require_once "Controllers/WelcomeController.php";
require_once "Controllers/FormController.php";
require_once "Controllers/ForgotPassword.php";
require_once "Controllers/DashboardController.php";
require_once "Controllers/UserController.php";
require_once "Controllers/ProductController.php";

// require_once "Controllers/ForgotController.php";

// Create an instance of Router
$route = new Router();

//welcome
$route->get("/dashboard", [DashboardController::class, 'show']);

// Define the routes
$route->get("/", [FormController::class, 'form']); // Set as homepage

$route->post("/form/authenticate", [UserController::class, 'authenticate']);


$route->get("/users", [UserController::class, 'index']);
$route->get("/users/create", [UserController::class, 'create']);
$route->post("/users/store", [UserController::class, 'store']);
$route->delete("/users/delete/{id}", [UserController::class, 'delete']);
$route->get("/users/logout", [UserController::class, 'logout']);
// $route->get('/forgot_password',[ForgotController:: class,'forgot_password'] );
//products

$route->get("/products", [ProductController::class, 'index']);
$route->get("/products/create", [ProductController::class, 'create']);
$route->post("/products/store", [ProductController::class, 'store']);
$route->get("/products/create/{id}", [ProductController::class, 'edit']);
$route->put("/products/update/{id}", [ProductController::class, 'update']);
$route->delete("/products/delete/{id}", [ProductController::class, 'delete']);




// Call the route method to process the request
$route->route();?>
<?php
// Router/Route.php

use Bramus\Router\Router;

$router = new Route();

$router->post('/place-order', 'Controllers\OrderController@placeOrder');
$router->run();
?>
