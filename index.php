<?php
// File: index.php
require_once 'Database.php'; // Include Database class
require_once 'Router/Route.php'; // Include routing logic

// Initialize database connection
$db = Database::getInstance();

// Test the connection (optional)
try {
    $db->query("SELECT 1"); // Simple test query
    echo "Database connected successfully!<br>";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Continue with your routing logic
require 'Router/Route.php';
?>