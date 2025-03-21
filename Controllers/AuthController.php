<?php
session_start();
require_once __DIR__ . "/../Database/Database.php";
require_once __DIR__ . "/../Models/UserModel.php";

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    // Login Function
    public function login() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            // Validate empty fields
            if (empty($email) || empty($password)) {
                $_SESSION['error'] = "Email and password are required.";
                header("Location: /");
                exit();
            }

            // Fetch user data
            $user = $this->userModel->getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email']
                ];

                header("Location: /dashboard");
                exit();
            } else {
                $_SESSION['error'] = "Invalid email or password.";
                header("Location: /");
                exit();
            }
        }
    }

    // Logout Function
    public function logout() {
        session_unset();
        session_destroy();
        header("Location: / ");
        exit();
    }
}

// Handle Login Request
if (isset($_POST['login'])) {
    $auth = new AuthController();
    $auth->login();
}

// Handle Logout Request
if (isset($_GET['logout'])) {
    $auth = new AuthController();
    $auth->logout();
}
