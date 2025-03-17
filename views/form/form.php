
<div class="container">
    
    <div class="form-box">

        <h2 id="loginForm" class="form-container active">Form Login</h2>
        <h2 id="signupForm" class="form-container">Form Signup</h2>


        <div class="toggle-buttons">
            <button id="login-btn" class="active" onclick="showLogin('login')">Login</button>
            <button id="signup-btn" onclick="showSignup('signup')">Signup</button>
        </div>


        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

        <!-- Login Form -->
        <form id="login-form" action="/form/authenticate" method="post">
            <input type="email" name="email" placeholder="Email Address" required>
            <div class="password-container">
                <input type="password" name="password" id="login-password" placeholder="Password" required>
                <i class="fas fa-eye" id="toggle-login-password" onclick="togglePassword('login-password', 'toggle-login-password')"></i>
            </div>

            <a href="/views/form/forgotPassword.php" class="forgot-password">Forgot password?</a>
            <button class="submit-btn" type="submit" name="login">Login</button>
            <p>Not a member? <a href="#" onclick="showSignup()">Signup now</a></p>
        </form>


        <!-- Signup Form -->
        <form id="signup-form" action="/users/store" method="post" style="display: none;">
            <form id="signup-form" method="post" style="display: none;">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <div class="password-container">
                    <input type="password" name="password" id="signup-password" placeholder="Password" required>
                    <i class="fas fa-eye" id="toggle-signup-password" onclick="togglePassword('signup-password', 'toggle-signup-password')"></i>
                </div>
                <div class="role_system">
                    <label for="role" class="form-label"></label>
                    <select name="role" id="role" class="form-control">
                        <option value="" disabled selected>Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>
                <button class="submit-btn" type="submit" name="signup">Signup</button>
                <p>Already have an account? <a href="/" onclick="showLogin()">Login</a></p>
            </form>

    </div>
    <div class="image-form">
        <img class="imgForm" src="/views/assets/images/form_background.png" alt="">
    </div>

</div>
