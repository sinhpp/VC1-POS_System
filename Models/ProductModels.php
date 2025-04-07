<?php
class Product {
    protected $table = 'products';
    
    // Fields: id, name, barcode, price, stock, category, image_path, created_at
    
    public static function findByBarcode($barcode) {
        // Database query to find product by barcode
        return DB::table('products')->where('barcode', $barcode)->first();
    }
    
    public static function updateStock($productId, $quantity) {
        // Update inventory after sale
        return DB::table('products')
            ->where('id', $productId)
            ->decrement('stock', $quantity);
    }
}
?>