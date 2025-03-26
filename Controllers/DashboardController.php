<?php
require_once 'BaseController.php';

class DashboardController extends BaseController {
    public function show() {
        $this->view('dashboard/dashboard');
    }
}



