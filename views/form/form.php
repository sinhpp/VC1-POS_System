<link rel="stylesheet" href="/views/assets/css/form.css">

<!-- <script>
    // function showLogin(view) {
    //     if (view === 'login') {
    //         document.getElementById("login-form").style.display = "block";
    //     }
    // }
</script> -->

<div class="container1">
    
    <div class="form-box">

        <h2 id="loginForm" class="form-container1 active">Welcome to <br>Asia Shop</h2>

        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

        <!-- Login Form -->
        <form id="login-form" action="/Controllers/AuthController.php" method="post">
            <input type="email" name="email" placeholder="Email Address" required>
            <div class="password-container1">
                <input type="password" name="password" id="login-password" placeholder="Password" required autocomplete="current-password">
                <i class="fas fa-eye" id="toggle-login-password" onclick="togglePassword('login-password', 'toggle-login-password')"></i>
            </div>

            <a href="/views/form/forgotPassword.php" class="forgot-password">Forgot password?</a>
            <button class="submit-btn" type="submit" name="login">Login</button>
            <p>Not a member? <a href="#" onclick="showSignup()">Register now</a></p>
        </form>
    </div>
    <div class="image-form">
        <img class="imgForm" src="/views/assets/images/form_background.png" alt="">
    </div>

</div>
