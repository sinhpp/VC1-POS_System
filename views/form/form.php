<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $sql = "SELECT id, password, role FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            header("Location: welcome.php");
            exit();
        } else {
            $error = "Invalid login credentials!";
        }
    }

    if (isset($_POST['signup'])) {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role = $_POST['role'];
        
        $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":password", $password, PDO::PARAM_STR);
        $stmt->bindParam(":role", $role, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $success = "Registration successful! You can now log in.";
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}

?>


    <div class="container">
        <div class="form-box">

            <h2>Login Form</h2>

            <div class="toggle-buttons">
                <button id="login-btn" class="active" onclick="showLogin()">Login</button>
                <button id="signup-btn" onclick="showSignup()">Signup</button>
            </div>


            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

            <!-- Login Form -->
            <form id="login-form" method="post">
                <input type="email" name="email" placeholder="Email Address" required>
                <div class="password-container">
                    <input type="password" name="password" id="login-password" placeholder="Password" required>
                    <i class="fas fa-eye" id="toggle-login-password" onclick="togglePassword('login-password', 'toggle-login-password')"></i>
                </div>
                <a href="/views/form/forgot_password.php" class="forgot-password">Forgot password?</a>

                
                <button class="submit-btn" type="submit" name="login">Login</button>
                <p>Not a member? <a href="#" onclick="showSignup()">Signup now</a></p>
            </form>

            <!-- Signup Form -->
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

    </div>
 