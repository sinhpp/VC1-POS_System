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
            margin-top:10%;
            display: flex;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(126, 54, 54, 0.1);
            max-width: 900px;
            width: 100%;
            position: relative;
            left:10%;
        }

        .upload-section {
            width: 30%;
            text-align: center;
            padding: 20px;
            border-right: 1px solid #ddd;
        }

        .upload-section h2 {
            margin-bottom: 20px;
            font-size: 1.5em;
            color: #6a11cb;
        }

        .profile-pic {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #6a11cb;
            margin-bottom: 10px;
            display: none; /* Initially hidden */
        }

        .detail {
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

        .btn-upload {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #6a11cb;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .btn-upload:hover {
            background-color: #2575fc;
            transform: scale(1.05);
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
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
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
        let imageSelected = false;

        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const img = document.getElementById("profile-pic");
                img.src = reader.result;
                img.style.display = 'block'; // Show the image after selection
                document.getElementById("upload-button").textContent = "Change Image"; // Change button text
                imageSelected = true; // Set flag to true
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

<form action="/users/storeuser" method="post" enctype="multipart/form-data" onsubmit="validateForm(event)">
    <div class="container">
        <!-- Left Column: Image Upload -->
        <div class="upload-section">
            <h2>Upload Profile Picture</h2>
            <img id="profile-pic" class="profile-pic" src="#" alt="Profile Picture" />
            <div class="input-group">
            <input id="file-input" type="file" name="image" accept="image/*" onchange="previewImage(event)">
                <button type="button" id="upload-button" class="btn-upload" onclick="document.getElementById('image-input').click();">Upload Image</button>
            </div>
        </div>

        <!-- Right Column: User Details Form -->
        <div class="detail">
            <!-- Name Field -->
            <div class="input-group">
                <label>Full Name</label>
                <input type="text" name="name" placeholder="Enter your full name" required>
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
                    <option value="cashier">Stock manager</option>
                </select>
            </div>
                        <!-- Phone Field -->
            <div class="input-group">
                <label>Phone</label>
                <input type="text" name="phone" placeholder="Enter your phone number" required>
            </div>

            <!-- Address Field -->
            <div class="input-group">
                <label>Address</label>
                <input type="text" name="address" placeholder="Enter your address" required>
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
        </div>
    </div>
</form>

</body>
<?php 
else: 
    $this->redirect("/"); 
endif;   
?>