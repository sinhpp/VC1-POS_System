<?php
require_once "Database/Database.php";

class UserModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance(); // Get PDO instance
    }

    public function getUsers() {
        $stmt = $this->db->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
///////////////////////////////////////////////////////////////////////////////////////
    public function createUser($name, $email, $password, $role, $image) {
        // Check if the email already exists
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);

        if ($stmt->fetchColumn() > 0) {
            return "Email already exists.";
        }
         // Ensure the uploads directory exists
         $uploadDir = __DIR__ . '/../uploads/';
         if (!is_dir($uploadDir)) {
             mkdir($uploadDir, 0777, true);
         }
     
         // Handle image upload
         $imagePath = null;
         if (isset($image) && $image['error'] === UPLOAD_ERR_OK) {
             $imageTmpPath = $image['tmp_name'];
             $imageName = basename($image['name']);
             $imagePath = $uploadDir . $imageName;
     
             // Move file and check for errors
             if (!move_uploaded_file($imageTmpPath, $imagePath)) {
                 error_log("Error moving file: " . print_r(error_get_last(), true));
                 return false;
             }
         }
     
       // Store relative path in database
       $dbImagePath = 'uploads/' . $imageName;
 
/////////////////////////////////////////////////////////////////////////
        // Insert new user
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)");
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $password,
            ':role' => $role
        ]);
    }
    public function deleteUser($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    public function usercreate($name, $email, $password, $role, $image) {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role, image) 
                                    VALUES (:name, :email, :password, :role, :image)");
    
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $password,
            ':role' => $role,
            ':image' => $image  // This can be NULL if no image is uploaded
        ]);
    
        return $stmt->rowCount();
    }
    
    
    
    public function updateUser($id, $name, $email, $role, $imagePath = null) {
        if ($imagePath) {
            $stmt = $this->db->prepare("UPDATE users SET name = :name, email = :email, role = :role, image = :image WHERE id = :id");
            $stmt->execute([
                ':id' => $id,
                ':name' => $name,
                ':email' => $email,
                ':role' => $role,
                ':image' => $imagePath
            ]);
        } else {
            $stmt = $this->db->prepare("UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id");
            $stmt->execute([
                ':id' => $id,
                ':name' => $name,
                ':email' => $email,
                ':role' => $role
            ]);
        }
        return $stmt->rowCount();
    }
    ///////////////////////////////
    public function view($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //////////////////////////////////////////
    
}
?>
