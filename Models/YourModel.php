require_once '/../Database/Database.php';

$db = Database::getInstance(); 
$stmt = $db->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll();
print_r($users);
    