
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) : ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: block;

            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .container {
            display: flex;
            margin-left:26%;
            margin-top:10%;
            
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(126, 54, 54, 0.1);
            max-width: 900px;
            width: 100%;
        }

        .profile-sidebar {
            width: 30%;
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
            object-fit: cover;
        }

        .upload-btn {
            background-color: #6a11cb;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .upload-btn:hover {
            background-color: #2575fc;
            transform: scale(1.05);
        }

        .profile-form {
            width: 70%;
            padding: 20px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .input-group input:focus,
        .input-group select:focus {
            border-color: #6a11cb;
            box-shadow: 0px 0px 8px rgba(106, 17, 203, 0.5);
        }

        .btn-primary {
            background-color: #6a11cb;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2575fc;
            transform: scale(1.05);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: scale(1.05);
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }
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
    </style>
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const img = document.getElementById("profile-pic");
                img.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        function validateForm(event) {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirm-password").value;
            const errorMessage = document.getElementById("password-error");

            if (password !== confirmPassword) {
                errorMessage.style.display = "block";
                event.preventDefault(); // Prevent form submission
            } else {
                errorMessage.style.display = "none";
                // Show success alert
                showSuccessAlert();
            }
        }

        function showSuccessAlert() {
            const alert = document.getElementById("success-alert");
            alert.style.display = "block";
            setTimeout(() => {
                alert.style.display = "none";
            }, 3000); // Hide the alert after 3 seconds
        }
    </script>
    
</head>
<body>
<div id="success-alert" class="success-alert">
    User created successfully!
</div>
    <div class="container">
        
        <!-- Left Column: Profile Picture Upload -->
         <div class="profile-sidebar">
        <img id="profile-pic" src="https://via.placeholder.com/100" alt="Profile Picture" class="profile-pic">
        <input type="file" id="file-input" name="image" accept="image/*" onchange="previewImage(event)" style="display: none;">
        <button type="button" class="upload-btn" onclick="document.getElementById('file-input').click()">Upload Photo</button>
    </div>

        <!-- Right Column: User Details Form -->
        <form action="/users/storeuser" method="post" onsubmit="validateForm(event)">
    <!-- Name Field -->
    <div class="input-group">
    <section class="upload-img">
    <h5>Upload Image</h5>
    
    <!-- File Input -->
    <input type="file" id="fileUpload" name="image" accept="image/*" required>
    
    <!-- Image Preview -->
    <div class="image-preview" id="imagePreview">
        <img 
            src="" 
            alt="Product Image" 
            id="previewImg" 
            style="display: none; max-width: 150px;">
    </div>
    <script>
document.getElementById('fileUpload').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const previewImg = document.getElementById('previewImg');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        previewImg.style.display = 'none';
    }
});
</script>
</section>
    </div>

    <!-- Email Field -->
    <div class="input-group">
        <label>Email Address</label>
        <input type="email" name="email" placeholder="Enter your email" required>
    </div>

    <!-- Role Field -->
    <div class="input-group">
        <label>Role</label>
        <select name="role" required>
            <option value="" disabled selected>Select Role</option>
            <option value="admin">Admin</option>
            <option value="cashier">Cashier</option>
        </select>
    </div>

    <!-- Password Field -->
    <div class="input-group">
        <label>Password</label>
        <input type="password" id="password" name="password" placeholder="Enter password" required>
    </div>

    <!-- Confirm Password Field -->
    <div class="input-group">
        <label>Confirm Password</label>
        <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm password" required>
        <div id="password-error" class="error-message">Passwords do not match. Please try again.</div>
    </div>

    <!-- Submit and Cancel Buttons -->
    <button type="submit" class="btn-primary">Create Account</button>
    <a href="/users" class="btn-secondary">Cancel</a>
</form>
        </div>
    </div>
</body>
<?php 
else: 
    $this->redirect("/"); 
endif;   
?>