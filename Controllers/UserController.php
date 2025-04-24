<?php
require_once "Models/UserModel.php";
require_once "Middleware/AuthMiddleware.php";

class UserController extends BaseController {
    private $users;

    public function __construct() {
        $this->users = new UserModel();
    }

    public function index() {
        // Only admin can access users list
        AuthMiddleware::checkAccess(['admin']);
        
        $users = $this->users->getUsers();
        $this->view("users/user", ['users' => $users]); // Ensure 'users/user.php' exists
    }

    public function showUser($id) {
        // Only admin can access user details
        AuthMiddleware::checkAccess(['admin']);
        
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
              if (session_status() == PHP_SESSION_NONE) {
                  session_start();
              }
              $email = htmlspecialchars($_POST['email']);
              $password = htmlspecialchars($_POST['password']);
              $user = $this->users->getUserByEmail($email);

              if ($user && password_verify($password, $user['password'])) {
                  $_SESSION['user_name'] = $user['name'];
                  $_SESSION['user_id'] = $user['id'];
                  $_SESSION['user_role'] = $user['role'];
                  $_SESSION['user_email'] = $user['email']; // Store the email
                  $_SESSION['user_image'] = $user['image']; // Store the image path
                  $_SESSION['users'] = true;
        
                  // Redirect based on role
                  switch ($user['role']) {
                      case 'admin':
                          $this->redirect("/dashboard");
                          break;
                      case 'stock_manager':
                          $this->redirect("/product/low_stock_alert");
                          break;
                        case 'cashier':
                            $this->redirect("/order");
                            break;
                      default:
                          $this->redirect("/dashboard");
                  }
              } else {
                  // Return the form with an error message
                  $this->view("form/form", ['error' => 'Invalid email or password']);
              }
          }
    public function delete($id) {
        AuthMiddleware::checkAccess(['admin']);
        
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
        AuthMiddleware::checkAccess(['admin']);
        $this->view("users/create");  // This should point to 'views/users/create_user.php'
    }

    public function storeuser() {
        AuthMiddleware::checkAccess(['admin']);
        
        if (!isset($_POST['name'], $_POST['email'], $_POST['password'], $_POST['role'], $_POST['phone'], $_POST['address'])) {
            die("Missing required fields.");
        }
    
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $encrypted_password = password_hash($password, PASSWORD_DEFAULT);
        $role = htmlspecialchars($_POST['role']);
        $phone = htmlspecialchars($_POST['phone']);
        $address = htmlspecialchars($_POST['address']);
        

        // Handle file upload
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "uploads/"; // Ensure this directory exists and is writable
            $newImageName = time() . "_" . basename($_FILES['image']['name']); // Unique filename
            $imagePath = $targetDir . $newImageName;
    
            // Move the uploaded file to the target directory
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                die("Failed to upload image.");
            }
        } else {
            $imagePath = "uploads/default.png"; // Set a default image if none uploaded
        }
    
        // Call the usercreate method
        $this->users->usercreate($name, $email, $encrypted_password, $role, $phone, $address, $imagePath);
        header("Location: /users");
        exit;
    }

    public function edit($id) {
        AuthMiddleware::checkAccess(['admin']);
        
        $user = $this->users->getUserById($id); // Fetch user details from model
        if (!$user) {
            die("User not found");
        }
        $this->view("users/edit", ['user' => $user]); // Pass user data to view
    }

    public function update($id) {
        AuthMiddleware::checkAccess(['admin']);
        
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $role = htmlspecialchars($_POST['role']);
        $phone = htmlspecialchars($_POST['phone']);
        $address = htmlspecialchars($_POST['address']);
        
        // Fetch existing user data
        $user = $this->users->getUserById($id);
        if (!$user) {
            die("User not found");
        }
        
        // Handle file upload
        $imagePath = $user['image']; // Keep the old image by default
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "uploads/"; // Ensure this directory exists and is writable
            $newImageName = time() . "_" . basename($_FILES['image']['name']); // Unique filename
            $newImagePath = $targetDir . $newImageName;

            // Move the new image to the target directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $newImagePath)) {
                // Delete old image if it exists (and is not the default image)
                if (!empty($user['image']) && file_exists($user['image']) && $user['image'] !== "uploads/default.png") {
                    unlink($user['image']); // Delete the old image
                }
                $imagePath = $newImagePath; // Update to the new image path
            }
        }

        // Update user with the new or existing image
        $this->users->updateUser($id, $name, $email, $role, $phone, $address, $imagePath);
        
        // Update session data if the current user is updating their own profile
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = $role;
            $_SESSION['user_image'] = $imagePath;
            // You can add more session variables as needed
        }
        
        // Redirect back to the user list
        header("Location: /users");
        exit;
    }
    
    public function detail($id) {
        AuthMiddleware::checkAccess(['admin']);
        
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