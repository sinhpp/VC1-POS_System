<?php
define('BASE_PATH', __DIR__);
ob_start();
$this->view->render('order/comfirmation', $data);
$emailBody = ob_get_clean();
?>
