<style>
    /* Sidebar container */
.dlabnav {
    height: 100vh; /* Full viewport height */
    position: fixed; /* Fixed to the left */
    display: flex;
    flex-direction: column;
}

        /* Scrollable section */
        .dlabnav-scroll {
            flex: 1; /* Allows content to expand */
            overflow-y: auto; /* Enables vertical scrolling */
            padding: 10px 8px; /* Add some padding for better spacing */
        }

        /* Remove hide scrollbar */
        /* .dlabnav-scroll::-webkit-scrollbar {
            display: none; 
        } */

        /* Better spacing for menu items */
        .metismenu li {
            padding: 5px 10px;
            list-style: none;
        }

/* Improve touch scrolling on mobile */
@media (hover: none) and (pointer: coarse) {
    .dlabnav-scroll {
        scroll-behavior: auto; /* Prevents scrolling bugs */
    }
}

/* Responsive improvements */
@media (max-width: 768px) {
    .dlabnav {
        width: 220px; /* Slightly smaller sidebar for mobile */
    }
}

/* User profile image styling */
.user-profile-img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

/* Profile dropdown menu */
.profile-dropdown {
    position: absolute;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    padding: 10px 0;
    min-width: 200px;
    z-index: 1000;
    display: none;
}

.profile-dropdown.show {
    display: block;
}

.profile-dropdown a {
    display: block;
    padding: 8px 15px;
    color: #333;
    text-decoration: none;
    transition: background-color 0.2s;
}

.profile-dropdown a:hover {
    background-color: #f5f5f5;
}

.profile-dropdown .divider {
    height: 1px;
    background-color: #e9ecef;
    margin: 5px 0;
}
</style>

<?php
// Ensure session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// If user is logged in, refresh user data from database to ensure it's up-to-date
if (isset($_SESSION['user_id'])) {
    // This would require access to the UserModel, so we'll need to include it
    require_once "Models/UserModel.php";
    $userModel = new UserModel();
    $freshUserData = $userModel->getUserById($_SESSION['user_id']);
    
    if ($freshUserData) {
        // Update session with fresh data
        $_SESSION['user_name'] = $freshUserData['name'];
        $_SESSION['user_email'] = $freshUserData['email'];
        $_SESSION['user_role'] = $freshUserData['role'];
        $_SESSION['user_image'] = $freshUserData['image'];
    }
}
?>

<!--**********************************
    Sidebar start
***********************************-->
<div class="dlabnav">
    <div class="dlabnav-scroll">
        <ul class="metismenu" id="menu">
            <li class="dropdown header-profile">
                <a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                    <?php if(isset($_SESSION['user_image']) && !empty($_SESSION['user_image']) && file_exists($_SESSION['user_image'])): ?>
                        <img src="/<?php echo htmlspecialchars($_SESSION['user_image']); ?>" class="user-profile-img" alt="User Profile" />
                    <?php else: ?>
                        <img src="/views/assets/images/ion/man (1).png" class="user-profile-img" alt="Default Profile" />
                    <?php endif; ?>
                    <div class="header-info ms-3">
                        <?php if(isset($_SESSION['user_name'])): ?>
                            <span class="font-w600 "><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                            <small class="text-end font-w400"><?php echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : 'No email available'; ?></small>
                        <?php else: ?>
                            <span class="font-w600 ">Guest User</span>
                            <small class="text-end font-w400">Not logged in</small>
                        <?php endif; ?>
                    </div>
                </a>
                <!-- Profile dropdown menu -->
                <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="/users/edit/<?php echo $_SESSION['user_id']; ?>" class="dropdown-item ai-icon">
                            <i class="fa-solid fa-user-pen"></i>
                            <span class="ms-2">Edit Profile</span>
                        </a>
                        <a href="/users/detail/<?php echo $_SESSION['user_id']; ?>" class="dropdown-item ai-icon">
                            <i class="fa-solid fa-address-card"></i>
                            <span class="ms-2">View Profile</span>
                        </a>
                        <div class="dropdown-divider"></div>
                    <?php endif; ?>
                    <a href="/logout" class="dropdown-item ai-icon">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span class="ms-2">Logout</span>
                    </a>
                </div>
            </li>
            <li><a href="/dashboard" aria-expanded="false">
                    <i class="flaticon-025-dashboard"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li><a href="/users" aria-expanded="false">
                    <i class="fa-solid fa-user"></i>
                    <span class="nav-text">User</span>
                </a>
            </li>
            <li><a href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-041-graph"></i>
                    <span class="nav-text">Order List</span>
                </a>
            </li>
            <li><a href="/product/low_stock_alert" aria-expanded="false">
                    <i class="flaticon-050-info"></i>
                    <span class="nav-text">Low Stocks</span>
                </a>
            </li>
            <li><a href="/order" class="ai-icon" aria-expanded="false">
                <i class="fa-solid fa-barcode"></i>
                    <span class="nav-text">Order Scan</span>
                </a>
            </li>
            <li><a href="/products" aria-expanded="false">
                    <i class="flaticon-045-heart"></i>
                    <span class="nav-text">Products List</span>
                </a>
            </li>
            
            <li><a href="widget-basic.html" class="ai-icon" aria-expanded="false">
                    <i class="fa-solid fa-window-maximize"></i>
                    <span class="nav-text">Expenses</span>
                </a>
            </li>
            <li><a href="/product_cashier/product" class="ai-icon" aria-expanded="false">
                    <i class="material-symbols-outlined"></i>
                    <span class="nav-text">Order</span>
                </a>
            </li>
            
            <li><a href="/" aria-expanded="false">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span class="nav-text">Logout</span>
                </a>
            </li>
            <li><a href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-043-menu"></i>
                    <span class="nav-text">Table</span>
                </a>
            </li>
            <li><a href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-022-copy"></i>
                    <span class="nav-text">Pages</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!--**********************************
    Sidebar end
***********************************-->

<script>
// Add this JavaScript to handle the profile dropdown
document.addEventListener('DOMContentLoaded', function() {
    const profileLink = document.querySelector('.header-profile .nav-link');
    const profileDropdown = document.querySelector('.profile-dropdown');
    
    if (profileLink && profileDropdown) {
        profileLink.addEventListener('click', function(e) {
            e.preventDefault();
            profileDropdown.classList.toggle('show');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!profileLink.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.remove('show');
            }
        });
    }
});
</script>
