<?php
class Product {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function findByBarcode($barcode) {
        $stmt = $this->db->prepare("SELECT * FROM pos_products WHERE barcode = ?");
        $stmt->execute([$barcode]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updateStock($barcode, $quantity) {
        $stmt = $this->db->prepare("UPDATE pos_products SET stock = stock - ? WHERE barcode = ?");
        return $stmt->execute([$quantity, $barcode]);
    }
}
?>