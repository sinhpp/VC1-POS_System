<?php

require_once './Database/Database.php';
require_once './Router/Route.php'; 


$db = Database::getInstance();


try {
    $db->query("SELECT 1"); 
    echo "Database connected successfully!<br>";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

require 'Router/Route.php';
?>