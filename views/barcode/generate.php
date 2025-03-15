<?php
if (isset($_POST['generate'])) {
    $code = trim($_POST['barcode']);
    
    if (!empty($code)) {
        echo "<center><img alt='Barcode' src='barcode.php?codetype=Code39&size=50&text=" . urlencode($code) . "&print=true'/></center>";
    } else {
        echo "<p>Please enter a valid barcode value.</p>";
    }
} else {
    echo "<p>No barcode value submitted.</p>";
}
?>