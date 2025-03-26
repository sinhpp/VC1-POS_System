<?php

use Controllers\BaseController;

class WelcomeController extends BaseController {
    public function welcome() {
        $this->view('welcome/welcome');
    }
}