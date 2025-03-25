<?php

require_once 'Models/ProductCatheirModel.php';

class ProductCatheirController extends BaseController {
    // private $ProductCatheirModel;

    // public function __construct() {
    //     $this->ProductCatheirModel = new ProductCatheirModel();
    // }

    public function index() {
        // $categories = $this->ProductCatheirModel->getCategories();
        $this->view('/product_catheir/product');
    }
}