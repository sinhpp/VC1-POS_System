

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
                        <span class="font-w600 ">Sinh Ern</span>
                        <small class="text-end font-w400">sinh.ern@gamil.com</small>
                    </div>
                </a>

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
                    <span class="nav-text">LowStock</span>
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
        </ul>
    </div>
</div>
<!--**********************************
            Sidebar end
        ***********************************-->
<!-- Category Modal -->
<div id="categoryModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2>Create Category</h2>
        <form id="categoryForm">
            <div class="form-group">
                <label for="categoryName">Category Name:</label>
                <input type="text" id="categoryName" name="categoryName" placeholder="Enter category name" required>
            </div>
            <button type="submit" class="submit-btn">Create</button>
        </form>
    </div>
</div>

	
<!-- Required Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/metismenu/3.0.7/metismenu.min.js"></script>
<script>
    $(document).ready(function() {
        $("#menu").metisMenu();


        // Show modal when clicking "Category"
        $(".open-category-modal").click(function(e) {
            e.preventDefault();
            $("#categoryModal").show();
        });

        // Hide modal when clicking the close button
        $(".close-modal").click(function() {
            $("#categoryModal").hide();
        });

        // Hide modal when clicking outside the modal content
        $(window).click(function(e) {
            if (e.target.id === "categoryModal") {
                $("#categoryModal").hide();
            }
        });

        // Handle form submission
        $("#categoryForm").submit(function(e) {
            e.preventDefault();
            const categoryName = $("#categoryName").val();
            if (categoryName) {
                alert(`Category '${categoryName}' created!`);
                $("#categoryModal").hide();
                $("#categoryForm")[0].reset(); // Reset the form
            } else {
                alert("Please enter a category name.");
            }
        });
    });
</script>