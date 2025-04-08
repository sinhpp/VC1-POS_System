<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) : ?>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="/views/assets/js/product.js"></script>
  <script>
  tailwind.config = {
    corePlugins: {
      preflight: false
    }
  };
</script>



    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            display: block;

            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
    

    
        .btn-success {
            margin-top:2%;
            
            padding: 15px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
          
            transition: all 0.3s ease;
            background:rgb(17, 110, 38);
        }
      
      
        

        .table-striped tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .badge.bg-success {
            background-color: #28a745;
            color: white;
        }

        .badge.bg-danger {
            background-color: #dc3545;
            color: white;
        }
        .btn-primary {
            border-color: var(--primary);
            background-color: var(--primary);
            box-shadow: 4px 4px 8px rgb(189 200 213), -4px -4px 8px rgb(255 255 255);
        }
        .header-right > li:not(:first-child) {
            padding-left: 0rem !important; }

        .btn {
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 2%;
            transition: all 0.3s ease;
        }

        .btn-warning {
            background-color: #ffc107;
            color: black;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn:hover {
            background-color: #495057;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
      

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            margin-left: -10%;
        }

        .action-icons {
            display: flex;
            gap: 10px;
        }

        .action-icons i {
            cursor: pointer;
            font-size: 18px;
        }

        .view { color: green; }
        .edit { color: blue; }
        .delete { color: red; }

        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .alert {
            display: inline-block;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 10px;
            border-radius: 5px;
            position: relative;
            margin-bottom: 5px;
            font-size: 14px;
            cursor: pointer;
        }

        .alert::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: rgba(0, 0, 0, 0.7) transparent transparent transparent;
        }

        #delete-icon {
            display: none;
            padding: 5px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s ease-in-out;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 30px;
            height: 30px;
        }

        .fa-ellipsis-vertical {
            margin-left: 50px;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            width: 120px;
            z-index: 1000;
        }

        .dropdown-menu a {
            display: flex;
            align-items: end;
            gap:5px;
            padding: 14px;
            text-decoration: none;
            color: black;
            font-size: 14px;
        }

        .dropdown-menu a:hover {
            background: #f1f1f1;
        }

        .page-btn {
            padding: 5px 10px;
            margin: 0 5px;
            cursor: pointer;
        
        }

        .page-btn.active {
            background-color: #007bff;
            color: white;
        }

        .page-btn:hover {
            background-color: #0056b3;
            color: white;
        }

        /* Adjust the width of the main content area */
        .main-content {
   
    justify-content:center;
    align-items:center;
            width: 100%; /* Make it full width */
   
   
}
.addProductBtn{
    display: inline;
}

/* Adjust the table width */
.table-container {
    margin: 20px auto; /* Centers the container horizontally */
    display: flex;
    flex-direction: column;
   
    width: 96%; /* Adjust width as needed */
 
    background: linear-gradient(145deg, #ffffff, #f9f9f9);
    padding: 10%; /* Adds spacing */

    border-radius: 8px; /* Optional: Makes corners rounded */
}

.table{
    width: 80%;

    margin-left: 14%; /* Adds spacing */
    display: flex;
    flex-direction: column;
  
    justify-content: center;
}


.sidebar {
    display: none; /* Hide the sidebar */
}
.navbar {
    padding: 10px 20px; /* Add padding */

        }

        .navbar-nav {
            gap: 15px; /* Add space between navbar items */
        }

        .notification_dropdown .badge {
            top: -10px; /* Adjust badge position */
            right: -10px;
        }

        .search-area {
            max-width: 300px; /* Limit search bar width */
        }

        /* Adjust width of main content */
        .main-content {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
      
        }
        .table thead th {
            background-color: #52C2EE;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: white;
        }
        th{
            background-color: teal !important;
        }

        /* Responsive Styles */
        @media (max-width: 1200px) {
            .header {
                margin-left: 0;
                text-align: center;
            }

            .table-container {
                margin-top: 10%;
                margin-left: 10%;
            }

            th, td {
                padding: 10px;
            }
        }

        @media (max-width: 768px) {
            .table-container {
                margin-top: 15%;
                margin-left: 5%;
            }

            th, td {
                padding: 8px;
                font-size: 0.9rem;
            }

            .btn {
                padding: 4px 8px;
                font-size: 0.8rem;
            }

            .material-icons {
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .table-container {
                margin-top: 20%;
                margin-left: 2%;
            }

            th, td {
                padding: 6px;
                font-size: 0.8rem;
            }

            .btn {
                padding: 3px 6px;
                font-size: 0.75rem;
            }

            .material-icons {
                font-size: 0.9rem;
            }

            .header-right {
                font-size: 0rem;
            }
            
        }
        .pagination{

            position: relative;
            left:60%;
        }
        .button{
            width: auto;
            position: relative;
          
        }
        h2 {
    color: #333;
}
.container {
    text-align: center;    
    justify-content: space-between;
    padding: 0 ;
    max-width: 100%;
    margin: auto;
}


.search {
    padding: 10px;
    width: 50%;
    margin-bottom: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

table {
  
    width: 100%;
    border-collapse: collapse;
    background-color: var(--table-bg, white); /* Change this color */
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: var(--header-bg, #333); /* Change this color */
    color: white;
}

tbody tr:nth-child(even) {
    background-color: var(--row-alt-bg, #f2f2f2); /* Change this color */
}

img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

td img {
    display: block;
    margin: 0 auto;
}
.filters {
    margin-bottom: 15px;
 
    justify-content: space-between;
    width: 100%;
    display: flex;
 
}

.search, .filter {
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin: 5px;
}



select.filter:focus {
    outline: none;
    border-color: #555;
}


button.filter-btn {
    padding: 10px 15px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
.alert{
    display: inline;
    position: static;
 
}

button.filter-btn:hover {
    background-color: #0056b3;
}
/* Larger alert styling */
.alert {
    font-size: 1.25rem; /* Increase font size */
    padding: 15px 20px; /* Increase padding */
    border-radius: 8px; /* Slightly round corners */
}
.alert#toast {
    display: none;
    position: absolute;
 
  
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 10px;
    border-radius: 5px;
    font-size: 14px;
    cursor: pointer;
    z-index: 100; /* Ensure it appears above other elements */

    .grid {
    margin-left: 0px !important;
    margin-top: 4%;
    display: grid
;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 19.8px;
    justify-content: space-between;
    flex-wrap: wrap;
}
}
    </style>  
</head>
<body>
</head>
<body>
<?php
$categories = $this->getCategories(); 
?>
<!-- Include SweetAlert2 CSS and JS -->
<!-- Include SweetAlert2 CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


<div class="table-container">
    <div class="table">
        <div class="container">
            <div class="filters">
                <input type="text" class="search" id="product-search" placeholder="Search...">
                
                <select class="filter" id="stock-filter">
                    <option value="">Stock</option>
                    <option value="low">Low Stock</option>
                    <option value="medium">Medium Stock</option>
                    <option value="high">High Stock</option>
                </select>

                <select class="filter" id="category-filter">
                    <option value="">All Categories</option>
                    <?php if (isset($categories) && count($categories) > 0): ?>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['name']); ?>">
                                <?= htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No categories available</option>
                    <?php endif; ?>
                </select>

            
                <button id="addProductBtn" class="btn btn-success">
                    Add Product
                </button>
           

            <!-- Modal -->
            <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
                <div class="modal-dialog">

                      
                            <h5 class="modal-title" id="categoryModalLabel">Add Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="categoryContent" style="font-weight: bold;">
                            <!-- Modal content will be loaded here -->
                        
                    
                </div>
            </div>



    
            </div>
        </div>
    

    <table>
        <thead>
            <tr>
            <th>
    <div style="position: relative;">
        <div class="alert" id="toast" style="display:none; position: absolute; bottom: 100%; left: 0; margin-bottom: 5px;">Delete all!</div>
        <input type="checkbox" onclick="toggleAllCheckboxes(this)">
    </div>
</th>
                <th>Image</th>
                <th>Name</th>
                <th>Code</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Category</th>
                <th>Created At</th>
                <th>Action
                <i class="fa-solid fa-trash" id="delete-icon" onclick="handleDelete()" style="display: none;  cursor: pointer;"></i>
                </th>
            </tr>
        </thead>
        <tbody id="product-list">
            <?php if (empty($products)): ?>
                <tr>
                    <td colspan="9" class="text-center">No products available.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <tr class="product-row" data-category="<?= htmlspecialchars($product['category']); ?>" data-stock="<?= $product['stock']; ?>">
                        <td><input type="checkbox" class="product-checkbox" value="<?= htmlspecialchars($product['id']) ?>"></td>
                        <td><img src="/<?= htmlspecialchars($product['image']) ?>" alt="Product Image" class="product-image"></td>
                        <td class="product-name"><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($product['barcode']) ?></td>
                        <td>$<?= number_format($product['price'], 2) ?></td>
                        <td><span class="badge bg-<?= $product['stock'] > 0 ? 'success' : 'danger' ?>"><?= htmlspecialchars($product['stock']) ?></span></td>
                        <td class="category-cell"><?= htmlspecialchars($product['category']) ?></td>
                        <td><?= htmlspecialchars($product['created_at']) ?></td>
                        <td class="action-icons">
                            <div class="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical" onclick="toggleDropdown(this)"></i>
                                <div class="dropdown-menu">
                                    <a href="/products/edit_pro/<?= $product['id'] ?>" class="dropdown-item"><i class="fa-solid fa-pen"></i> Edit</a>
                                    <a href="/products/delete/<?= $product['id'] ?>" class="dropdown-item text-danger" onclick="return confirm('Are you sure?');"><i class="fa-solid fa-trash"></i> Delete</a>
                                    <!-- Fixed link: Changed from product_detail to detail -->
                                    <a href="/products/product_detail/<?= $product['id'] ?>" class="dropdown-item"><i class="fa-solid fa-eye"></i> Detail</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    </div>
     <!-- Pagination Buttons (Now at the bottom) -->
     <div class="pagination" id="pagination-buttons"></div>
    </div>
   
</div>

<script>

////alert category/////////////////////////////////////////////////////////////////

const addProductBtn = document.getElementById("addProductBtn");
const categoryModal = new bootstrap.Modal(document.getElementById('categoryModal'));
const categoryContent = document.getElementById("categoryContent");
const feedbackDiv = document.getElementById('categoryFeedback'); // Ensure this div exists in your HTML

addProductBtn.addEventListener("click", () => {
    // Fetch the category content and load it into the modal
    fetch("/views/products/show_category.php")
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            categoryContent.innerHTML = html;
            categoryModal.show(); // Show the modal using Bootstrap's modal method
            
            // Show alert indicating the category was loaded successfully
            showAlert('Category loaded successfully!', 'success');
        })
        .catch(error => {
            // Handle any errors
            showAlert('Failed to load category. Please try again.', 'danger');
        });
});

// Function to show alert
function showAlert(message, type) {
    feedbackDiv.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert" style="font-size: 1.25rem;">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>`;
}
/////////////////////////////end alert category/////////////////////////////////////////////////////////
const products = <?= json_encode($products); ?>; // Convert PHP array to JavaScript
const productsPerPage = 10;
let currentPage = 1;
let filteredProducts = [...products]; // Create a copy of the products array
let totalPages = Math.ceil(filteredProducts.length / productsPerPage);

// Filter products based on category, stock, and search term
function filterProducts() {
    const categoryFilter = document.getElementById('category-filter').value.toLowerCase();
    const stockFilter = document.getElementById('stock-filter').value.toLowerCase();
    const searchTerm = document.getElementById('product-search').value.toLowerCase();
    
    // Reset filtered products to all products
    filteredProducts = products.filter(product => {
        // Category filter
        const categoryMatch = !categoryFilter || product.category.toLowerCase() === categoryFilter;
        
        // Stock filter
        let stockMatch = true;
        if (stockFilter) {
            const stock = parseInt(product.stock);
            if (stockFilter === 'low' && stock <= 10) stockMatch = true;
            else if (stockFilter === 'medium' && stock > 10 && stock <= 50) stockMatch = true;
            else if (stockFilter === 'high' && stock > 50) stockMatch = true;
            else stockMatch = false;
        }
        
        // Search filter (match name, barcode, or category)
        const searchMatch = !searchTerm || 
            product.name.toLowerCase().includes(searchTerm) || 
            product.barcode.toLowerCase().includes(searchTerm) ||
            product.category.toLowerCase().includes(searchTerm);
        
        return categoryMatch && stockMatch && searchMatch;
    });
    
    // Update total pages based on filtered products
    totalPages = Math.ceil(filteredProducts.length / productsPerPage);
    
    // Reset to first page when filters change
    currentPage = 1;
    
    // Render the filtered products
    renderProducts(1);
}

function renderProducts(page) {
    currentPage = page;
    const start = (currentPage - 1) * productsPerPage;
    const end = start + productsPerPage;
    const currentProducts = filteredProducts.slice(start, end);

    const tbody = document.getElementById('product-list');
    tbody.innerHTML = '';

    if (currentProducts.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" class="text-center">No products match your filters.</td></tr>';
    } else {
        currentProducts.forEach(product => {
            const row = document.createElement('tr');
            row.className = 'product-row';
            row.setAttribute('data-category', product.category);
            row.setAttribute('data-stock', product.stock);
            
            row.innerHTML = `
                <td><input type="checkbox" class="product-checkbox" value="${product.id}"></td>
                <td><img src="/${product.image}" alt="Product Image" class="product-image"></td>
                <td class="product-name">${product.name}</td>
                <td>${product.barcode}</td>
                <td>$${parseFloat(product.price).toFixed(2)}</td>
                <td><span class="badge bg-${product.stock > 0 ? 'success' : 'danger'}">${product.stock}</span></td>
                <td class="category-cell">${product.category}</td>
                <td>${product.created_at}</td>
                <td class="action-icons">
                    <div class="dropdown">
                        <i class="fa-solid fa-ellipsis-vertical" onclick="toggleDropdown(this)"></i>
                        <div class="dropdown-menu">
                            <a href="/products/edit_pro/${product.id}" class="dropdown-item"><i class="fa-solid fa-pen"></i> Edit</a>
                            <a href="/products/delete/${product.id}" class="dropdown-item text-danger" onclick="return confirm('Are you sure?');"><i class="fa-solid fa-trash"></i> Delete</a>
                            <!-- Fixed link: Changed from product_detail to detail -->
                            <a href="/products/product_detail/${product.id}" class="dropdown-item"><i class="fa-solid fa-eye"></i> Detail</a>
                        </div>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    renderPagination(); // Update pagination
}

function renderPagination() {
    const paginationDiv = document.getElementById('pagination-buttons');
    paginationDiv.innerHTML = ''; // Clear previous pagination buttons

    // Only show pagination if we have more than one page
    if (totalPages > 1) {
        for (let i = 1; i <= Math.min(5, totalPages); i++) {
            const button = document.createElement('button');
            button.classList.add('page-btn');
            button.textContent = i;
            button.onclick = function () {
                renderProducts(i);
                updateActivePage(i);
            };

            if (i === currentPage) {
                button.classList.add('active'); // Highlight active page
            }

            paginationDiv.appendChild(button);
        }
    }
}

// Highlight active page button
function updateActivePage(page) {
    const buttons = document.querySelectorAll('.page-btn');
    buttons.forEach(button => {
        button.classList.remove('active');
        if (parseInt(button.textContent) === page) {
            button.classList.add('active');
        }
    });
}

// Initial load - Now only 10 products will show on page 1
function init() {
    // Add event listeners for filters
    document.getElementById('category-filter').addEventListener('change', filterProducts);
    document.getElementById('stock-filter').addEventListener('change', filterProducts);
    document.getElementById('product-search').addEventListener('input', filterProducts);
    
    // Initial render
    renderProducts(1);
}

// Call init when DOM is loaded
document.addEventListener('DOMContentLoaded', init);
</script>


<style>
/* Pagination Button Styling */
.pagination {
    margin-top: 10px;
    text-align: center;
    padding: 10px 0;
}

.page-btn {
    background-color: #f8f9fa;
    border: 1px solid #ddd;
    padding: 8px 12px;
    margin: 2px;
    cursor: pointer;
    border-radius: 4px;
}

.page-btn:hover {
    background-color: #e9ecef;
}

.page-btn.active {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

/* Highlight search matches */
.highlight {
    background-color: yellow;
    font-weight: bold;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.addEventListener("click", function(event) {
        const sortOptions = document.getElementById("sort-options");
        const sortIcon = document.querySelector(".sort-icon");

        // Close dropdown if clicking outside (not on icon or options)
        if (sortOptions && sortOptions.style.display === "block" && !sortOptions.contains(event.target) && event.target !== sortIcon) {
            sortOptions.style.display = "none";
        }
    });
});

function toggleDropdown(icon) {
    let dropdownMenu = icon.nextElementSibling;
    dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function closeDropdown(event) {
        if (!icon.parentElement.contains(event.target)) {
            dropdownMenu.style.display = 'none';
            document.removeEventListener('click', closeDropdown);
        }
    });
}

function toggleAllCheckboxes(source) {
    document.querySelectorAll('tbody input[type="checkbox"]').forEach(checkbox => checkbox.checked = source.checked);
    updateToastVisibility();
}

function updateToastVisibility() {
    const selectedCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]:checked');
    const toast = document.getElementById('toast');
    toast.style.display = selectedCheckboxes.length > 0 ? 'inline-block' : 'none';

    if (selectedCheckboxes.length > 0) {
        toast.onclick = handleDelete;
        toast.style.cursor = 'pointer';
    } else {
        toast.onclick = null;
        toast.style.cursor = 'default';
    }
}


function updateDeleteIcon() {
    const selectedCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]:checked');
    const deleteIcon = document.getElementById('delete-icon');

    // Show trash icon if checkboxes are selected, otherwise hide it
    deleteIcon.style.display = selectedCheckboxes.length > 0 ? 'inline-block' : 'none';
}

function handleDelete() {
    const selectedCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]:checked');

    if (selectedCheckboxes.length > 0) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You will delete all selected products.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete them!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const selectedIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
                fetch('/products/delete_all', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ids: selectedIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) location.reload();
               
                    else Swal.fire('Error', 'Failed to delete products.', 'error');
                })
                .catch(error => Swal.fire('Error', 'An error occurred while deleting products.', 'error'));
            }
        });
    }
}

// Add event listener to checkboxes to update delete icon
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to all checkboxes
    document.querySelectorAll('.product-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateDeleteIcon);
    });
});
</script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<?php else: $this->redirect("/"); endif; ?>
