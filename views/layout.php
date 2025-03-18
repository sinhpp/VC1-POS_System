<?php 
require_once('layouts/header.php'); 

// Get the current route
$currentRoute = $_SERVER['REQUEST_URI'];

// Define routes where navbar, sidebar, styles, and scripts should NOT be loaded
$hiddenRoutes = ['/', '/form', '/views/form/form.php', '/form/authenticate', 'authenticate'];

?>

<?php 
// Only include navbar and sidebar if NOT in the hidden routes
if (!in_array($currentRoute, $hiddenRoutes)) {
    require_once('layouts/navbar.php'); 
    require_once('layouts/sidebar.php'); 
} 
?>

<?= $content; ?>

<?php require_once('layouts/footer.php'); ?>

</body>
</html>
