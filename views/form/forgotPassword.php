<?php
session_start();
require_once "../layout.php";

?>

    <div class="container-forgotpassword">
        <h2>Reset your password</h2>
        <form id="resetForm" method="post">
            <!-- <label class="new_pwd" for="password">New password</label> -->
            <div class="password-container">
                <input type="password" id="password" placeholder="Enter your new password" required>
                <i class="fas fa-eye" id="togglePassword"></i>
            </div>
            <!-- <label class="com_pwd" for="confirm_password">Confirm new password</label> -->
            <div class="password-container">
                <input type="password" id="confirm_password" placeholder="Confirm your password" required>
                <i class="fas fa-eye" id="toggleConfirmPassword"></i>
            </div>
            <div class="choice">
            <button class="comfirm" type="submit">Confirm</button>
            <button class="cancel" ><a href="/views/form/form.php"> Cancel</a></button>
        </div>
        </form>
    </div>
