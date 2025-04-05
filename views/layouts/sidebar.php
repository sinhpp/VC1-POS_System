

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

/* Icon styling */
.metismenu li a i {
    margin-right: 10px;
    font-size: 18px;
}

/* Dropdown-specific styles */
.dropdown ul {
    display: none;
    padding: 0;
    margin: 0;
    background-color: #f8f9fa;
}

/* Position dropdown below the parent */
.dropdown ul {
    position: relative;
    left: 0;
    top: 0;
    padding-left: 20px;
}

/* Show dropdown on hover */
.dropdown:hover > ul {
    display: block;
}

/* Style dropdown items */
.dropdown ul li a {
    padding: 8px 15px;
    display: block;
    color: #6c757d;
    text-decoration: none;
    font-size: 14px;
}

/* Hover effect for dropdown items */
.dropdown ul li a:hover {
    background-color: #e9ecef;
}

.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    z-index: 1000;
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    width: 400px;
    max-width: 90%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    position: relative;
}

.close-modal {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 24px;
    cursor: pointer;
    color: #333;
}

.modal-content h2 {
    margin: 0 0 20px;
    font-size: 20px;
    color: #333;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
}

.form-group input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.submit-btn {
    width: 100%;
    padding: 10px;
    background-color: #28a745; /* Green to match the Checkout button */
    color: #fff;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.submit-btn:hover {
    background-color: #218838; /* Darker green on hover */
}

.metismenu .has-arrow::after {
    content: "\f107"; /* FontAwesome down arrow, adjust as needed */
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    position: absolute;
    right: 1em;
}
.metismenu ul {
    padding-left: 20px;
    display: none; /* Hidden by default, metismenu will toggle this */
}
.metismenu .mm-active > ul {
    display: block; /* Show when active */
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
            <li class="dropdown">
    <a href="javascript:void(0);" aria-expanded="false" class="products-list" onclick="toggleDropdown()">
        <i class="flaticon-045-heart"></i>
        <span class="nav-text">Products List</span>
    </a>
    <ul class="dropdown-menu" id="dropdownMenu">
        <li><a href="/products">Product list</a></li>
        <!-- Link to trigger category modal -->
        <li><a href="javascript:void(0);" data-toggle="modal" data-target="#categoryModal">Category</a></li>
    </ul>
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
function toggleDropdown() {
    const dropdownMenu = document.getElementById('dropdownMenu');
    dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
}

window.onclick = function(event) {
    if (!event.target.matches('.products-list') && !event.target.closest('.dropdown-menu')) {
        document.getElementById('dropdownMenu').style.display = 'none';
    }
}
</script>

<!-- category.php -->
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    return; // Don't show modal if not logged in
}
?>

<!-- Modal for Create Category -->
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="categoryModalLabel">Create Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">
        <form id="createCategoryForm" method="POST">
          <div class="form-group">
            <label for="categoryName">Category Name:</label>
            <input type="text" class="form-control" id="categoryName" name="categoryName" required>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Create</button>
        </form>

        <div id="categoryFeedback" class="mt-3">
          <?php
          if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categoryName'])) {
              include('db.php');
              $categoryName = trim($_POST['categoryName']);

              if (!empty($categoryName)) {
                  $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
                  $stmt->bindParam(':name', $categoryName);
                  
                  if ($stmt->execute()) {
                      echo "<div class='alert alert-success'>Category '$categoryName' created successfully!</div>";
                  } else {
                      echo "<div class='alert alert-danger'>Failed to create category.</div>";
                  }
              } else {
                  echo "<div class='alert alert-danger'>Please enter a category name.</div>";
              }
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Required Scripts for Bootstrap Modal -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>