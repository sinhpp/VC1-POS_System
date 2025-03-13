<?php
require_once "Router.php";
require_once "Controllers/BaseController.php";
require_once "Database/Database.php";
require_once "Controllers/FormController.php";
require_once "Controllers/ForgotPassword.php";
require_once "Controllers/DashboardController.php";

// Create an instance of Router
$route = new Router();

//welcome
$route->get("/dashboard", [DashboardController::class, 'show']);

// Define the routes
$route->get("/", [FormController::class, 'form']);
// $route->get('/forgot_password',[ForgotController:: class,'forgot_password'] );



// Call the route method to process the request
$route->route();