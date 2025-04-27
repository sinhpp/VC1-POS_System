<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) : ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        .error {
            color: red;
            display: none;
        }
        body {
            font-family: Arial, sans-serif;
            display: block;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
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
            display: flex; /* Use flexbox */
            flex-direction: column; /* Stack items vertically */
            align-items: center; /* Center items horizontally */
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
    display: flex;
    flex-direction: column; /* Stack label and input vertically */
    margin-bottom: 15px;
}

.input-group label {
    font-weight: bold;
    margin-bottom: 5px;
    display: block; /* Make label full width */
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
            margin-top: 10px; /* Add margin for spacing */
        }

        .btn-upload:hover {
            background-color: #2575fc;
            transform: scale(1.05);
        }
        .input-group input,
.input-group select {
    width: 100%;
    padding: 10px; /* a little more padding for better look */
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}



        .input-group input:focus,
        .input-group select:focus {
            border-color: #6a11cb;
            box-shadow: 0px 0px 8px rgba(106, 17, 203, 0.5);
        }


    .btn-primary1,
    .btn-secondary1 {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 30%;
        text-align: center;
    }

    .btn-primary1 {
        background-color: #6a11cb;
        color: white;
    }

    .btn-secondary1 {
        background-color: #6c757d;
        color: white;
        text-decoration: none;
    }

    .submit {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
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
        background-color: #4CAF50;
        color: white;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        display: none;
        z-index: 1000;
    }

    /* ======= Responsive Styles ======= */

    
/* Tablet Devices (768px - 1024px) */
@media (max-width: 768px) {
    .container {
        flex-direction: column; /* Stack upload section and details vertically */
        margin-top: 5%;
        left: 0;
        max-width: 90%;
    }

    .upload-section, 
    .detail {
        width: 100%; /* Full width on mobile */
        padding: 10px;
        border: none; /* Remove border between sections */
    }

    .upload-section {
        border-bottom: 1px solid #ddd; /* Instead of side border */
        margin-bottom: 20px;
    }

    .input-group input,
    .input-group select {
        font-size: 14px;
        padding: 8px;
    }

    .btn-primary1,
    .btn-secondary1 {
        width: 100%; /* Full width buttons */
    }

    .submit {
        flex-direction: column; /* Stack buttons vertically */
        gap: 10px;
    }

    h2 {
        font-size: 1.2em;
    }
}

/* Mobile Devices (up to 767px) */
@media (max-width: 767px) {
    body {
        align-items: stretch;
    }

    .container {
        flex-direction: column;
        width: 90%;
        margin: 20px auto;
        padding: 15px;
    }

    .upload-section {
        margin-top: 16%;
        width: 100%;
        border-right: none;
        border-bottom: 1px solid #ddd;
    }

    .detail {
   
        width: 100%;
        padding: 15px 0;
    }

    .submit {
        flex-direction: column;
        align-items: stretch;
    }

    .btn-primary1,
    .btn-secondary1 {
        width: 100%;
        padding: 10px;
        font-size: 14px;
    }

    .success-alert {
        width: 90%;
        left: 5%;
        top: 10px;
        font-size: 14px;
    }
 
}

</style>
<script>
        let imageSelected = false;

        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const img = document.getElementById("profile-pic");
                img.src = reader.result;
                img.style.display = 'block';
                document.getElementById("upload-button").textContent = "Change Image";
                imageSelected = true;
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        function validateForm(event) {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirm-password").value;
            const errorMessage = document.getElementById("password-error");

            if (password !== confirmPassword) {
                errorMessage.style.display = "block";
                event.preventDefault();
                return false; // Prevent submission
            } else {
                errorMessage.style.display = "none";
            }
            return true; // Allow submission
        }

        function validatePhone(event) {
            const phoneInput = document.querySelector('input[name="phone"]');
            const errorMessage = document.getElementById("error-message");
            const phoneValue = phoneInput.value;

            // Clear previous error message
            errorMessage.style.display = 'none';

            // Check if the phone number is exactly 12 digits
            if (!/^\d{10}$/.test(phoneValue)) {
                errorMessage.style.display = 'block';
                event.preventDefault(); // Prevent form submission
                return false; // Indicate validation failure
            }
            return true; // Indicate validation success
        }

        function showSuccessAlert() {
            const alert = document.getElementById("success-alert");
            alert.style.display = "block";
            setTimeout(() => {
                alert.style.display = "none";
            }, 3000);
        }

        function validateAndSubmit(event) {
            const isFormValid = validateForm(event);
            const isPhoneValid = validatePhone(event);

            if (isFormValid && isPhoneValid) {
                showSuccessAlert(); // Show success alert if all validations pass
            }
        }
    </script>
    
</head>
<body>
<div id="success-alert" class="success-alert">
    User created successfully!
</div>

<form action="/users/storeuser" method="post" enctype="multipart/form-data" onsubmit="validateAndSubmit(event)">
    <div class="container">
        <div class="upload-section">
            <h2>Upload Profile Picture</h2>
            <img id="profile-pic" class="profile-pic" src="#" alt="Profile Picture" />
            <div class="input-group">
                <input id="file-input" type="file" name="image" accept="image/*" onchange="previewImage(event)" style="display: none;">
                <button type="button" class="btn-upload" onclick="document.getElementById('file-input').click();">Upload Image</button>
            </div>
        </div>

        <div class="detail">
            <div class="input-group">
                <label>Full Name</label>
                <input type="text" name="name" placeholder="Enter your full name" required>
            </div>

            <div class="input-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="input-group">
                <label>Role</label>
                <select name="role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="admin">Admin</option>
                    <option value="cashier">Cashier</option>
                    <option value="stock_manager">Stock Manager</option>
                </select>
            </div>

            <div class="input-group">
                <label>Phone</label>
                <input type="text" name="phone" placeholder="Enter your phone number" required>
                <div class="error" id="error-message">Please enter exactly 12 digits.</div>
            </div>

            <div class="input-group">
                <label>Address</label>
                <input type="text" name="address" placeholder="Enter your address" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required>
            </div>

            <div class="input-group">
                <label>Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm password" required>
                <div id="password-error" class="error-message">Passwords do not match. Please try again.</div>
            </div>

            <div class="submit">
                <a href="/users" class="btn-secondary1">Cancel</a>
                <button type="submit" class="btn-primary1">Create Account</button>
            </div>
        </div>
    </div>
</form>

</body>
<?php 
else: 
    $this->redirect("/"); 
endif;   
?>