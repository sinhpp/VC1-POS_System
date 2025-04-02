<style>
    /* Sidebar container */
    .dlabnav {
        height: 100vh; /* Full viewport height */
        position: fixed; /* Fixed to the left */
        display: flex;
        flex-direction: column;
        width: 250px;
        background-color: #f8f9fa;
        z-index: 1000;
        transition: all 0.3s ease;
        left: 0;
        top: 0;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        overflow: hidden; /* Prevent content overflow */
    }

    /* Scrollable section */
    .dlabnav-scroll {
        flex: 1; /* Allows content to expand */
        overflow-y: auto; /* Enables vertical scrolling */
        padding: 15px 10px; /* Increased padding for better spacing */
        
        /* Hide scrollbar */
        -ms-overflow-style: none; /* IE and Edge */
        scrollbar-width: none; /* Firefox */
        -webkit-overflow-scrolling: touch; /* Momentum scrolling on mobile */
        scroll-behavior: smooth; /* Smooth scrolling effect */
        overscroll-behavior: contain; /* Prevents bounce effect */
    }

    /* Hide scrollbar for Chrome, Safari, Edge */
    .dlabnav-scroll::-webkit-scrollbar {
        display: none;
    }

    /* Menu container */
    .metismenu {
        padding: 0;
        margin: 0;
    }

    /* Better spacing for menu items */
    .metismenu li {
        padding: 3px 5px;
        list-style: none;
        margin-bottom: 5px;
    }

    .metismenu li a {
        display: flex;
        align-items: center;
        padding: 12px;
        color: #333;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.3s;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .metismenu li a:hover, 
    .metismenu li a.active {
        background-color: #4E36E2;
        color: white;
    }

    .metismenu li a i {
        margin-right: 12px;
        width: 20px;
        text-align: center;
        font-size: 16px;
    }

    /* Profile section styling */
    .header-profile .nav-link {
        padding: 15px 10px;
        border-radius: 8px;
        margin-bottom: 10px;
        background-color: rgba(78, 54, 226, 0.05);
    }

    .header-profile img {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
    }

    .header-info {
        display: flex;
        flex-direction: column;
    }

    .font-w600 {
        font-weight: 600;
        font-size: 14px;
    }

    .text-end {
        font-size: 12px;
        opacity: 0.8;
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
            transform: translateX(-100%); /* Hide by default on mobile */
            box-shadow: none;
        }
        
        body.sidebar-active .dlabnav {
            transform: translateX(0); /* Show when active */
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        /* Overlay when sidebar is active on mobile */
        body.sidebar-active:before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        
        .dlabnav {
            z-index: 1001; /* Above the overlay */
        }
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
                    <img src="/views/assets/images/ion/man (1).png" width="20" alt="" />
                    <div class="header-info ms-3">
                        <span class="font-w600">Sinh Ern</span>
                        <small class="text-end font-w400">sinh.ern@gamil.com</small>
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
            <li><a href="javascript:void()" aria-expanded="false">
                <i class="flaticon-041-graph"></i>
                <span class="nav-text">Order List</span>
            </a></li>
            <li><a href="/order" class="ai-icon" aria-expanded="false">
                <i class="fa-solid fa-barcode"></i>
                <span class="nav-text">Order Scan</span>
            </a></li>
            <li><a href="/products" aria-expanded="false">
                <i class="flaticon-045-heart"></i>
                <span class="nav-text">Products List</span>
            </a></li>
            <li><a href="widget-basic.html" class="ai-icon" aria-expanded="false">
                <i class="fa-solid fa-window-maximize"></i>
                <span class="nav-text">Expenses</span>
            </a></li>
            <li><a href="/product_cashier/product" class="ai-icon" aria-expanded="false">
                <i class="material-symbols-outlined"></i>
                <span class="nav-text">Order</span>
            </a></li>
            <li><a href="/product/low_stock_alert" aria-expanded="false">
                <i class="flaticon-043-menu"></i>
                <span class="nav-text">Low Stocks Product</span>
            </a></li>
            <li><a href="/" aria-expanded="false">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span class="nav-text">Logout</span>
            </a></li>
        </ul>
    </div>
</div>
<!--**********************************
      Sidebar end
***********************************-->

<!-- Add this script to the bottom of your file or in a separate JS file -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get toggle button if it exists
        const toggleBtn = document.getElementById('toggleSidebar');
        
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                document.body.classList.toggle('sidebar-active');
            });
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768 && 
                document.body.classList.contains('sidebar-active') && 
                !e.target.closest('.dlabnav') && 
                !e.target.closest('#toggleSidebar')) {
                document.body.classList.remove('sidebar-active');
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                document.body.classList.remove('sidebar-active');
            }
        });
    });
</script>
