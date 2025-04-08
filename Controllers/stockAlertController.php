<?php
require_once 'Models/ProductModel.php';
require_once 'Controllers/BaseController.php';

class StockAlertController extends BaseController
{
    private $productModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->productModel = new ProductModel();
    }
    
    public function index()
    {
        // Get products with low stock (for example, less than 10 items)
        $lowStockThreshold = 10;
        $lowStockProducts = $this->productModel->getLowStockProducts($lowStockThreshold);
        
        // Count of low stock products for notification badge
        $_SESSION['low_stock_count'] = count($lowStockProducts);
        
        // Render the low stock alerts view
        $this->render('products/lowStockAlert', [
            'products' => $lowStockProducts,
            'threshold' => $lowStockThreshold
        ]);
    }
}
