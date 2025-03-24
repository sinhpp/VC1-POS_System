<?php

require_once 'Models/ProductCashierModel.php';

class ProductCashierController extends BaseController {
    // private $ProductCatheirModel;

    // public function __construct() {
    //     $this->ProductCatheirModel = new ProductCatheirModel();
    // }

    public function index() {
        // $categories = $this->ProductCatheirModel->getCategories();
        $this->view('/product_cashier/product');
    }
}