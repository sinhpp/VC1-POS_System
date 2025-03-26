<?php

class ForgotController extends BaseController {
    public function forgotPassword() {
        $this->view('forgotPassword');
    }
}