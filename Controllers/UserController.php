<?php

require_once "Models/UserModel.php";

// ✅ Ensure session is only started once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class UserController extends BaseController {
    private $users;

    public function __construct() {
        $this->users = new UserModel();
    }

    public function store() {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $encrypted_password = password_hash($password, PASSWORD_DEFAULT);
        $role = htmlspecialchars($_POST['role']);

        // ✅ Fix: Pass NULL as a placeholder for $imagePath if not needed
        if ($this->users->createUser($name, $email, $encrypted_password, $role, NULL)) {
            $_SESSION['signup_success'] = "Your signup was successful!";
            header("Location: /");
            exit();
        } else {
            $_SESSION['signup_error'] = "Error during signup. Please try again.";
            header("Location: /");
            exit();
        }
    }
}
