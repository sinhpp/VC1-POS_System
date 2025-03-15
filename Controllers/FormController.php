<?php
require_once(__DIR__ . '/BaseController.php'); // 

class FormController extends BaseController {
    public function form() {
        $this->view('form/form'); // Load the form view correctly
    }
}