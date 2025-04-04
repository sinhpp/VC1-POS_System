 <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="waviy">
       <span style="--i:1">L</span>
       <span style="--i:2">o</span>
       <span style="--i:3">a</span>
       <span style="--i:4">d</span>
       <span style="--i:5">i</span>
       <span style="--i:6">n</span>
       <span style="--i:7">g</span>
       <span style="--i:8">.</span>
       <span style="--i:9">.</span>
       <span style="--i:10">.</span>
    </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->
<link rel="stylesheet" href="/views/assets/css/form.css">
<div class="container1">
    
    <div class="form-box">

        <h1 id="loginForm" class="form-container1 active">Welcome <br>to,</h1>
        <div class="asia-shop-logo">
            <img src="/views/assets/images/AsiaShop.png" alt="Asia Shop Logo">
        </div>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

        <!-- Login Form -->
        <form id="login-form" action="/form/authenticate" method="post">
            <input type="email" name="email" placeholder="Email Address" required>
            <div class="password-container1">
                <input type="password" name="password" id="login-password" placeholder="Password" required>
                <i class="fas fa-eye" id="toggle-login-password" onclick="togglePassword('login-password', 'toggle-login-password')"></i>
            </div>

            <button class="submit-btn" type="submit" name="login">Login</button>
            <p>Safe business <a href="#">Stock management</a></p>
        </form>


        <!-- Signup Form -->
        <form id="signup-form" action="/users/store" method="post" style="display: none;">
            <form id="signup-form" method="post" style="display: none;">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <div class="password-container1">
                    <input type="password" name="password" id="signup-password" placeholder="Password" required>
                    <i class="fas fa-eye" id="toggle-signup-password" onclick="togglePassword('signup-password', 'toggle-signup-password')"></i>
                </div>
                <div class="role_system">
                    <label for="role" class="form-label"></label>
                    <select name="role" id="role" class="form-control">
                        <option value="" disabled selected>Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="cashier">Cashier</option>
                        <option value="cashier">stock manager</option>
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
