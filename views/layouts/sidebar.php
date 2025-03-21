<?php
// session_start();
// $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
?>

<!-- Sidebar Start -->
<div class="dlabnav">
    <div class="dlabnav-scroll">
        <ul class="metismenu" id="menu">
            <li class="dropdown header-profile">
                <a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                    <img src="/views/assets/images/ion/man (1).png" width="20" alt=""/>
                    <div class="header-info ms-3">
                        <span class="font-w600" id="user-name">
                            <?php echo $user ? htmlspecialchars($user['name']) : "Guest"; ?>
                        </span>
                        <small class="text-end font-w400" id="user-email">
                            <?php echo $user ? htmlspecialchars($user['email']) : "guest@example.com"; ?>
                        </small>
                    </div>
                </a>
            </li>

            <li><a href="/dashboard" aria-expanded="false">
                <i class="flaticon-025-dashboard"></i>
                <span class="nav-text">Dashboard</span>
            </a></li>

            <li><a href="/users" aria-expanded="false">
                <i class="fa-solid fa-user"></i>
                <span class="nav-text">User</span>
            </a></li>

            <li><a href="/orders" aria-expanded="false">
                <i class="flaticon-041-graph"></i>
                <span class="nav-text">Order List</span>
            </a></li>

            <li><a href="/reports" aria-expanded="false">
                <i class="flaticon-050-info"></i>
                <span class="nav-text">Reports</span>
            </a></li>

            <li><a href="/products" aria-expanded="false">
                <i class="flaticon-045-heart"></i>
                <span class="nav-text">Products List</span>
            </a></li>

            <li><a href="/expenses" class="ai-icon" aria-expanded="false">
                <i class="fa-solid fa-window-maximize"></i>
                <span class="nav-text">Expenses</span>
            </a></li>

            <li><a href="/Controllers/AuthController.php?logout=1" aria-expanded="false">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span class="nav-text">Logout</span>
            </a></li>

            <li><a href="javascript:void(0)" aria-expanded="false">
                <i class="flaticon-043-menu"></i>
                <span class="nav-text">Table</span>
            </a></li>

            <li><a href="javascript:void(0)" aria-expanded="false">
                <i class="flaticon-022-copy"></i>
                <span class="nav-text">Pages</span>
            </a></li>
        </ul>
    </div>
</div>
<!-- Sidebar End -->

<script>
function updateUserProfile() {
    fetch('/Controllers/fetchUser.php')
        .then(response => response.json())
        .then(user => {
            if (user && user.name) {
                let nameElement = document.getElementById("user-name");
                let emailElement = document.getElementById("user-email");
                
                if (nameElement && emailElement) {
                    nameElement.innerText = user.name;
                    emailElement.innerText = user.email;
                }
            }
        })
        .catch(error => console.error("Error fetching user profile:", error));
}

// Refresh profile every 5 seconds
setInterval(updateUserProfile, 5000);
updateUserProfile();
</script>
