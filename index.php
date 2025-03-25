    <?php

    require_once __DIR__ . '/Router/Route.php'; // Ensure this line is present
    require_once './Database/Database.php';

    $db = Database::getInstance();

    try {
        $db->query("SELECT 1"); 
        echo "Database connected successfully!<br>";
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    ?>