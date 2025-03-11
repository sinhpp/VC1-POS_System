<?php
class Database {
    private static $instance = null; // Singleton instance
    private $conn;
    
    // Database Configuration
    private $host = "localhost";  // Change if needed
    private $user = "root";       // Change if needed
    private $pass = "";           // Change if needed
    private $dbname = "pos_system"; // Ensure this matches your MySQL database

    // Private constructor to prevent direct object creation
    private function __construct() {
        try {
            // Create a new PDO connection
            $this->conn = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname;charset=utf8mb4",
                $this->user,
                $this->pass
            );
            
            // Set error mode to Exception for debugging
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Set default fetch mode to associative arrays
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            // If the connection fails, show an error message
            die("Database Connection Failed: " . $e->getMessage());
        }
    }

    // Get the single instance of the Database
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }

    // Prevent object cloning
    private function __clone() {}

    // Prevent object unserialization
    public function __wakeup() {}
}
?>
