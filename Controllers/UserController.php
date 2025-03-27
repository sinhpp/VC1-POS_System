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

    public function form() {
        $this->view("form/form");
    }
    public function store() {
        session_start(); // Start session to store the message
    
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $encrypted_password = password_hash($password, PASSWORD_DEFAULT);
        $role = htmlspecialchars($_POST['role']);
    
        // Check if user creation is successful
        if ($this->users->createUser($name, $email, $encrypted_password, $role)) {
            $_SESSION['signup_success'] = "Your signup was successful!";
            header("Location: /"); // Redirect to the login page
            exit();
        } else {
            $_SESSION['signup_error'] = "Error during signup. Please try again.";
            header("Location: /"); // Redirect back to signup form
            exit();
        }
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
            $this->redirect("/dashboard"); // Redirect to users list on success
        } else {
            // Return the form with an error message
            $this->view("form/form", ['error' => 'Invalid email or password']);
        }
    }
    public function delete($id) {
        $this->users->deleteUser($id);
        header("Location: /users");
    }
    
    public function logout() {
        session_start();
        
        // Unset all session variables
        session_unset();
        
        // Destroy the session
        session_destroy();

        // Redirect to homepage after logout
        header("Location: /");
    }

    public function createuser() {
        $this->view("users/create");  // This should point to 'views/users/create_user.php'
    }
    public function storeuser() {
        if (!isset($_POST['name'], $_POST['email'], $_POST['password'], $_POST['role'])) {
            die("Missing required fields.");
        }
    
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $encrypted_password = password_hash($password, PASSWORD_DEFAULT);
        $role = htmlspecialchars($_POST['role']);
        
        // Handle file upload
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "uploads/"; // Ensure this directory exists and is writable
            $imagePath = $targetDir . basename($_FILES['image']['name']);
            
            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                // File upload success
            } else {
                // Handle file upload error
                die("Failed to upload image.");
            }
        } else {
            // Handle case where no image is uploaded
            $imagePath = null; // Keep it NULL if no image was uploaded
        }
    
        // Call the usercreate method
        $this->users->usercreate($name, $email, $encrypted_password, $role, $imagePath);
        header("Location: /users");
    }

    public function edit($id) {
        $user = $this->users->getUserById($id); // Fetch user details from model
        if (!$user) {
            die("User not found");
        }
        $this->view("users/edit", ['user' => $user]); // Pass user data to view
    }
    
    public function update($id) {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $role = htmlspecialchars($_POST['role']);
        
        // Handle file upload
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "uploads/"; // Ensure this directory exists and is writable
            $imagePath = $targetDir . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        }
    
        $this->users->updateUser($id, $name, $email, $role, $imagePath);
        header("Location: /users");
    }
   
    public function detail($id) {
        $user = $this->users->user_detail($id);
        if ($user) {
            // Ensure this path matches the actual file structure
            $this->view("users/user_detail", ['user' => $user]);
        } else {
            echo "User not found.";
        }
    }
}
?>
