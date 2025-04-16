<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
<style>
    /* Sidebar container */
.dlabnav {
    height: 80%vh; /* Full viewport height */
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
.dlabnav-scroll::-webkit-scrollbar {
    display: none; 
} 

/* Better spacing for menu items */
.metismenu li {
    padding: 5px 10px;
    list-style: none;
}
.metismenu li a {
    display: flex;
    align-items: center;
    padding: 10px;
    color: #6c757d;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

/* Hover effect for main menu items */
.metismenu li a:hover {
    background-color: #e9ecef;
}

/* Dropdown-specific styles */
.dropdown ul {
    display: none;
    position: relative;
    padding: 0;
    margin: 0;
    background-color: #f8f9fa;
}

/* Show dropdown on hover */
.dropdown:hover > ul {
    display: block;
}

/* Modal styles */
.modal {
    z-index: 1050; /* Ensure modal is above sidebar */
}

.modal-content {
    padding: 20px;
    border-radius: 8px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    font-weight: bold;
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

/* Additional styles for dropdown */
.sub-menu {
    padding-left: 20px; /* Indentation for sub-items */
    display: none; /* Initially hide sub-menu */
}
.sub-menu.show {
    display: block; /* Show sub-menu when active */
}
</style>
<!--**********************************
    Sidebar start
***********************************-->
<div class="dlabnav">
    <div class="dlabnav-scroll">
        <ul class="metismenu" id="menu">
            <li class="dropdown header-profile">
                <a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                    <img src="/views/assets/images/ion/man (1).png" class="user-profile-img" alt="Default Profile" />
                    <div class="header-info ms-3">
                        <span class="font-w600 ">Guest User</span>
                        <small class="text-end font-w400">Not logged in</small>
                    </div>
                </a>
            </li>
            <li class="dropdown">
                <a href="javascript:void(0);" class="products-list" onclick="toggleDropdown()">
                    <i class="flaticon-045-heart"></i>
                    <span class="nav-text">Product</span>
                    <i class="fa-solid fa-chevron-down ms-auto"></i> <!-- Arrow icon -->
                </a>
                <ul class="sub-menu" id="productDropdown">
                    <li><a href="/products" onclick="keepDropdownOpen()">Product list</a></li>
                    <li><a href="/products/category" onclick="keepDropdownOpen()">Category</a></li>
                </ul>
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
            <li><a href="/views/order/order_list.php" aria-expanded="false">
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
        </ul>
    </div>
</div>
<!--**********************************
    Sidebar end
***********************************-->

<script>
let isDropdownOpen = false; // Track dropdown state

function toggleDropdown() {
    const dropdown = document.getElementById('productDropdown');
    isDropdownOpen = !isDropdownOpen; // Toggle state
    
    if (isDropdownOpen) {
        dropdown.classList.add('show');
    } else {
        dropdown.classList.remove('show');
    }
}

function keepDropdownOpen() {
    const dropdown = document.getElementById('productDropdown');
    dropdown.classList.add('show'); // Keep dropdown open when sub-item is clicked
}
</script>
