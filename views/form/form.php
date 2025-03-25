<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Signup Form</title>
    <link rel="stylesheet" href="/views/assets/css/form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Success Alert Styling */
        .success-alert {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4CAF50; /* Green background */
            color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            display: none; /* Hidden by default */
            z-index: 1000; /* Ensure it's above other elements */
        }

        /* Error Message Styling */
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }

        /* Password Container */
        .password-container1 {
            position: relative;
        }

        .password-container1 i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Success Alert -->
    <div id="success-alert" class="success-alert">
        Login successful! Welcome!
    </div>

    <div class="container1">
        <div class="form-box">
            <h2 id="loginForm" class="form-container1 active">Form Login</h2>
            <h2 id="signupForm" class="form-container1">Form Signup</h2>

            <div class="toggle-buttons">
                <button id="login-btn" class="active" onclick="showLogin()">Login</button>
                <button id="signup-btn" onclick="showSignup()">Signup</button>
            </div>

            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

            <!-- Login Form -->
            <form id="login-form" action="/form/authenticate" method="post" onsubmit="validateLoginForm(event)">
                <input type="email" name="email" placeholder="Email Address" required>
                <div class="password-container1">
                    <input type="password" name="password" id="login-password" placeholder="Password" required>
                    <i class="fas fa-eye" id="toggle-login-password" onclick="togglePassword('login-password', 'toggle-login-password')"></i>
                </div>
                <a href="/views/form/forgotPassword.php" class="forgot-password">Forgot password?</a>
                <button class="submit-btn" type="submit" name="login">Login</button>
                <p>Not a member? <a href="#" onclick="showSignup()">Signup now</a></p>
            </form>

            <!-- Signup Form -->
            <form id="signup-form" action="/users/store" method="post" style="display: none;">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <div class="password-container1">
                    <input type="password" name="password" id="signup-password" placeholder="Password" required>
                    <i class="fas fa-eye" id="toggle-signup-password" onclick="togglePassword('signup-password', 'toggle-signup-password')"></i>
                </div>
                <div class="password-container1">
                    <input type="password" id="confirm-signup-password" placeholder="Confirm Password" required>
                    <i class="fas fa-eye" id="toggle-confirm-signup-password" onclick="togglePassword('confirm-signup-password', 'toggle-confirm-signup-password')"></i>
                </div>
                <div id="signup-password-error" class="error-message">Passwords do not match. Please try again.</div>
                <div class="role_system">
                    <label for="role" class="form-label"></label>
                    <select name="role" id="role" class="form-control">
                        <option value="" disabled selected>Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="cashier">Cashier</option>
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

    <script>
        // Function to toggle password visibility
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }

        // Function to show the success alert
        function showSuccessAlert() {
            const alert = document.getElementById("success-alert");
            alert.style.display = "block";
        }

        // Function to check for login success on page load
        function checkLoginSuccess() {
            if (localStorage.getItem('loginSuccess')) {
                showSuccessAlert();
                localStorage.removeItem('loginSuccess'); // Clear the flag
            }
        }

        // Function to validate the login form
        function validateLoginForm(event) {
            const email = document.querySelector("#login-form input[name='email']").value;
            const password = document.getElementById("login-password").value;

            // Simulate a successful login (replace with actual validation logic)
            if (email && password) {
                event.preventDefault(); // Prevent form submission for demonstration
                localStorage.setItem('loginSuccess', 'true'); // Set flag in localStorage
                window.location.href = "/dashboard"; // Redirect to the dashboard
            } else {
                alert("Please fill in all fields."); // Basic validation
                event.preventDefault();
            }
        }

        // Function to switch to the login form
        function showLogin() {
            document.getElementById("login-form").style.display = "block";
            document.getElementById("signup-form").style.display = "none";
            document.getElementById("login-btn").classList.add("active");
            document.getElementById("signup-btn").classList.remove("active");
        }

        // Function to switch to the signup form
        function showSignup() {
            document.getElementById("login-form").style.display = "none";
            document.getElementById("signup-form").style.display = "block";
            document.getElementById("signup-btn").classList.add("active");
            document.getElementById("login-btn").classList.remove("active");
        }

        // Check for login success when the page loads
        window.onload = checkLoginSuccess;
    </script>
</body>
</html>