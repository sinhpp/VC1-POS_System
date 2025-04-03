<?php
class EmailService {
    private $fromEmail;
    private $adminEmail;
    
    public function __construct() {
        // Load configuration
        $this->fromEmail = 'noreply@yourstore.com';
        $this->adminEmail = 'admin@yourstore.com'; // This should come from config
    }
    
    public function sendLowStockAlert($products) {
        if (empty($products)) {
            return false;
        }
        
        $subject = 'Low Stock Alert - Action Required';
        
        // Build email body
        $body = "<html><body>";
        $body .= "<h2>Low Stock Alert</h2>";
        $body .= "<p>The following products are running low on stock and require your attention:</p>";
        $body .= "<table border='1' cellpadding='5'>";
        $body .= "<tr><th>Product</th><th>Current Stock</th></tr>";
        
        foreach ($products as $product) {
            $body .= "<tr>";
            $body .= "<td>" . htmlspecialchars($product['name']) . "</td>";
            $body .= "<td>" . htmlspecialchars($product['stock']) . "</td>";
            $body .= "</tr>";
        }
        
        $body .= "</table>";
        $body .= "<p>Please log in to your admin dashboard to manage inventory.</p>";
        $body .= "</body></html>";
        
        // Email headers
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: " . $this->fromEmail . "\r\n";
        
        // Send email
        return mail($this->adminEmail, $subject, $body, $headers);
    }
}
