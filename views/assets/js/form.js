// Function to toggle between login and signup forms
function showLogin() {
    document.getElementById("login-form").style.display = "block";
    document.getElementById("signup-form").style.display = "none";
    document.getElementById("loginForm").classList.add("active");
    document.getElementById("signupForm").classList.remove("active");
    document.getElementById("login-btn").classList.add("active");
    document.getElementById("signup-btn").classList.remove("active");
}

function showSignup() {
    document.getElementById("login-form").style.display = "none";
    document.getElementById("signup-form").style.display = "block";
    document.getElementById("signupForm").classList.add("active");
    document.getElementById("loginForm").classList.remove("active");
    document.getElementById("signup-btn").classList.add("active");
    document.getElementById("login-btn").classList.remove("active");
}

// Function to toggle password visibility
function togglePassword(inputId, iconId) {
    const passwordField = document.getElementById(inputId);
    const toggleIcon = document.getElementById(iconId);

    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleIcon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        passwordField.type = "password";
        toggleIcon.classList.replace("fa-eye-slash", "fa-eye");
    }
}

// Forgot Password Form Validation
document.addEventListener("DOMContentLoaded", function () {
    const resetForm = document.getElementById("resetForm");

    if (resetForm) { // Ensure the form exists before adding event listeners
        const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");
        const confirmPasswordInput = document.getElementById("confirm_password");

        // Toggle password visibility
        toggleConfirmPassword.addEventListener("click", function () {
            togglePassword("confirm_password", "toggleConfirmPassword");
        });

        // Form validation before submission
        resetForm.addEventListener("submit", function (event) {
            const emailInput = document.getElementById("email").value.trim();
            const confirmPassword = confirmPasswordInput.value.trim();

            if (!validateEmail(emailInput)) {
                alert("Please enter a valid email address.");
                event.preventDefault();
                return;
            }

            if (confirmPassword === "") {
                alert("Please enter your password.");
                event.preventDefault();
            }
        });

        // Function to validate email format
        function validateEmail(email) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailPattern.test(email);
        }
    }
});
