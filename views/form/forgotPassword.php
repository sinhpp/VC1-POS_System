
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="/views/assets/css/form.forgot.password.css">
    <link rel="stylesheet" href="/views/assets/css/form.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div class="container-forgotpassword">
        <h2>Reset your password</h2>
        <form id="resetForm" method="post">
            <!-- <label class="new_pwd" for="password">New password</label> -->
            <div class="password-container">
                <input type="email" id="email" placeholder="Enter your new email" required>
                
            </div>
            <!-- <label class="com_pwd" for="confirm_password">Confirm new password</label> -->
            <div class="password-container">
                <input type="password" id="confirm_password" placeholder="Confirm your password" required>
                <i class="fas fa-eye" id="toggleConfirmPassword"></i>
            </div>
            <div class="choice">
            <button class="comfirm" type="submit">Confirm</button>
            <button class="cancel" ><a href="/"> Cancel</a></button>
        </div>
        </form>
    </div>

   <script src="../assets/js/form.js"></script>
</body>
</html>
