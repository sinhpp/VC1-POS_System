<?php
require_once __DIR__ . '/../Controllers/BaseController.php';
require_once __DIR__ . '/Route.php';

// Define routes
Route::add('GET', '/', 'WelcomeController', 'index');
Route::add('GET', 'login', 'AuthController', 'showLoginForm');
Route::add('POST', 'login', 'AuthController', 'login');
Route::add('GET', 'register', 'AuthController', 'showRegisterForm');
Route::add('POST', 'register', 'AuthController', 'register');
Route::add('GET', 'dashboard', 'DashboardController', 'index');

// Dispatch the request
Route::dispatch();
?>
