<?php
require_once __DIR__.'/BaseController.php';
require_once __DIR__.'/../Models/ProductsModel.php';

class PosController extends BaseController {
    private $productModel;
    
    public function __construct() {
        $db = new Database();
        $this->productModel = new Product($db);
    }
    
    public function index() {
        // Load POS interface view
        require_once __DIR__ . '/../views/pos.php';
    }
}