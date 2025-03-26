function showLogin() {
    document.getElementById("login-form").style.display = "block";
    document.getElementById("signup-form").style.display = "none";
    document.getElementById("loginForm").style.display = "block";
    document.getElementById("signupForm").style.display = "none";
    document.getElementById("login-btn").classList.add("active");
    document.getElementById("signup-btn").classList.remove("active");
    document.getElementById("login-btn").classList.add("active");
    document.getElementById("signup-btn").classList.remove("active");                   
   
    if (type === 'login') {
        document.getElementById('loginForm').classList.add('active');
    } else {
        document.getElementById('signupForm').classList.add('none');
    }
   
}

function showSignup() {
    document.getElementById("login-form").style.display = "none";
    document.getElementById("signup-form").style.display = "block";
    document.getElementById("loginForm").style.display = "none";
    document.getElementById("signupForm").style.display = "block";
    document.getElementById("signup-btn").classList.add("active");
    document.getElementById("login-btn").classList.remove("active");

   
}

function togglePassword(inputId, iconId) {
const passwordField = document.getElementById(inputId);
const toggleIcon = document.getElementById(iconId);

if (passwordField.type === "password") {
    passwordField.type = "text";
    toggleIcon.classList.remove("fa-eye");
    toggleIcon.classList.add("fa-eye-slash");
} else {
    passwordField.type = "password";
    toggleIcon.classList.remove("fa-eye-slash");
    toggleIcon.classList.add("fa-eye");
}
}


// formforgotPassword

document.addEventListener("DOMContentLoaded", function () {
    const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");
    const confirmPasswordInput = document.getElementById("confirm_password");
    const resetForm = document.getElementById("resetForm");

    // Toggle password visibility
    toggleConfirmPassword.addEventListener("click", function () {
        if (confirmPasswordInput.type === "password") {
            confirmPasswordInput.type = "text";
            toggleConfirmPassword.classList.remove("fa-eye");
            toggleConfirmPassword.classList.add("fa-eye-slash");
        } else {
            confirmPasswordInput.type = "password";
            toggleConfirmPassword.classList.remove("fa-eye-slash");
            toggleConfirmPassword.classList.add("fa-eye");
        }
    });

    // Form validation before submission
    resetForm.addEventListener("submit", function (event) {
        const emailInput = document.getElementById("email").value.trim();
        const confirmPassword = confirmPasswordInput.value.trim();

        // Email validation
        if (!validateEmail(emailInput)) {
            alert("Please enter a valid email address.");
            event.preventDefault();
            return;
        }

        // Check if password is empty
        if (confirmPassword === "") {
            alert("Please enter your password.");
            event.preventDefault();
            return;
        }
    });

    // Function to validate email format
    function validateEmail(email) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
    }
});
