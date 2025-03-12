<?php

class FormController extends BaseController {
    public function form() {
        $this->view('form/form');
    }
    public function store() {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $encrypted_password = password_hash($password, PASSWORD_DEFAULT);
        $role = htmlspecialchars($_POST['role']);
        $_SESSION['signup_success'] = 'Your signup was successful!';
        $this->users->createUser($name, $email, $encrypted_password, $role);
        
        // Redirect to login form instead of users list
        header("Location: /");
        exit();
    }
    
   
    
}