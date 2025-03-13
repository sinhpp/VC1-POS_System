<?php
require_once 'Models/WelcomeModel.php';
require_once 'BaseController.php';
class WelcomeController extends BaseController {
    private $model;
    function __construct()
    {
        $this->model = new WelcomeModel();
    }
    function index()
    {
        $welcome = $this->model->getWelcome();
        $this->views('welcome/welcome.php');
    }
}