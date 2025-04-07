<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Dashboard</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
    <style>
        /* Sidebar container */
        .dlabnav {
            height: 100vh; /* Full viewport height */
            position: fixed; /* Fixed to the left */
            display: flex;
            flex-direction: column;
            z-index: 999; /* Ensure sidebar is above other elements */
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
    </style>
</head>
<body>

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
            <li><a href="javascript:void(0);" aria-expanded="false">
                <i class="flaticon-041-graph"></i>
                <span class="nav-text">Order List</span>
            </a></li>
            <li><a href="/product/low_stock_alert" aria-expanded="false">
                <i class="flaticon-050-info"></i>
                <span class="nav-text">LowStock</span>
            </a></li>
            <li><a href="/order" class="ai-icon" aria-expanded="false">
                <i class="fa-solid fa-barcode"></i>
                <span class="nav-text">Order Scan</span>
            </a></li>
            <li class="dropdown">
                <a href="javascript:void(0);" aria-expanded="false" class="products-list" onclick="toggleDropdown()">
                    <i class="flaticon-045-heart"></i>
                    <span class="nav-text">Products List</span>
                </a>
                <ul class="dropdown-menu" id="dropdownMenu">
                    <li><a href="/products">Product list</a></li>
                    <li><a href="/products/category" id="openCategory">Category</a></li>
                </ul>
            </li>
            <li><a href="/product_cashier/product" class="ai-icon" aria-expanded="false">
                <i class="material-symbols-outlined"></i>
                <span class="nav-text">Order</span>
            </a></li>
            <li><a href="/" aria-expanded="false">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span class="nav-text">Logout</span>
            </a></li>
        </ul>
        <!-- Adding dummy content for scrolling -->
        <div style="height: 2000px;"></div>
    </div>
</div>
<!--**********************************
            Sidebar end
***********************************-->