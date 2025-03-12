<?php
require_once "Models/UserModel.php";

class UserController extends BaseController {
    private $users;

    public function __construct() {
        $this->users = new UserModel();
    }

    public function index() {
        $users = $this->users->getUsers();
        $this->view("users/user", ['users' => $users]); // Ensure 'users/user.php' exists
    }

    public function showUser($id) {
        $user = $this->users->getUserById($id);
        $this->view("users/user_details", ['user' => $user]);
    }
    public function create() {
        $this->view("users/create");  // This should point to 'views/users/create_user.php'
    }

    public function store() {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $encrypted_password = password_hash($password, PASSWORD_DEFAULT);
        $role = htmlspecialchars($_POST['role']);
        $this->users->createUser($name, $email, $encrypted_password, $role);
        header("Location: /users");
    }
    public function login() {
        $this->view("form/form");
    }
    public function authenticate() {
        session_start();
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $user = $this->users->getUserByEmail($email);
    
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['users'] = true;
            $this->redirect("/users"); // Redirect to users list on success
        } else {
            // Return the form with an error message
            $this->view("form/form", ['error' => 'Invalid email or password']);
        }
    }
    
    
}
?>
