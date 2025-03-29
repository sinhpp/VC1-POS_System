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
      
        th {
            background-color: #007BFF !important; 
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            top: 0;
            z-index: 2;
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

/* Adjust the table width */
.table-container {
    margin: 20px auto; /* Centers the container horizontally */
    display: flex;
    flex-direction: column;
   
    width: 90%; /* Adjust width as needed */
 
    background: linear-gradient(145deg, #ffffff, #f9f9f9);
    padding: 10%; /* Adds spacing */

    border-radius: 8px; /* Optional: Makes corners rounded */
}

.table{
    width: 80%;

    margin-left: 12%; /* Adds spacing */
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
    </style>
</head>
<body>
</head>
<body>
<!-- Include SweetAlert2 CSS and JS -->
<!-- Include SweetAlert2 CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="table-container">
    
    <div class="table">
    <div class="button">
        <a href="/products/create" class="btn btn-success">+ Add Product</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>
                    <div class="alert" id="toast" style="display:none;">Delete all!</div>
                    <input type="checkbox" onclick="toggleAllCheckboxes(this)">
                </th>
                <th>Image</th>
                <th>Name</th>
                
                <th>Code</th>
                <th>Price

                <i class="fa-solid fa-filter-circle-dollar" onclick="toggleSortOptions(event)">		</i>
                <div class="sort-options" id="sort-options" style="display: none; position: absolute; background: white; border: 1px solid #ccc; padding: 5px;">
                <input type="text" id="priceSearch" placeholder="input price..." oninput="searchPrice()" style="margin-top:5px; padding: 5px; width: 100%;">
                    <button onclick="sortPrice('high')" style="display: block; width: 100%; text-align: left;"><i class="fas fa-arrow-down"></i> High</button>
                    <button onclick="sortPrice('low')" style="display: block; width: 100%; text-align: left;"><i class="fas fa-arrow-up"></i> Low</button>
                </div>
                </th>

                <th>Stock

                    <i class="fa-solid fa-filter-circle-dollar" onclick="toggleStockSortOptions(event)"></i>
                    <div class="sort-options" id="stock-sort-options" style="display: none; position: absolute; background: white; border: 1px solid #ccc; padding: 5px;">
                        <input type="text" id="stockSearch" placeholder="input stock..." oninput="searchStock()" style="margin-top:5px; padding: 5px; width: 100%;">
                        <button onclick="sortStock('high')" style="display: block; width: 100%; text-align: left;">
                            <i class="fas fa-arrow-down"></i> High
                        </button>
                        <button onclick="sortStock('low')" style="display: block; width: 100%; text-align: left;">
                            <i class="fas fa-arrow-up"></i> Low
                        </button>
                    </div>
                </th>

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
                    <tr>
                        <td><input type="checkbox" class="product-checkbox" value="<?= htmlspecialchars($product['id']) ?>"></td>
                        <td><img src="/<?= htmlspecialchars($product['image']) ?>" alt="Product Image" class="product-image"></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($product['barcode']) ?></td>
                        <td>$<?= number_format($product['price'], 2) ?></td>
                        <td><span class="badge bg-<?= $product['stock'] > 0 ? 'success' : 'danger' ?>"><?= htmlspecialchars($product['stock']) ?></span></td>
                        <td><?= htmlspecialchars($product['category']) ?></td>
                        <td><?= htmlspecialchars($product['created_at']) ?></td>
                        <td class="action-icons">
                            <div class="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical" onclick="toggleDropdown(this)"></i>
                                <div class="dropdown-menu">
                                    <a href="/products/edit_pro/<?= $product['id'] ?>" class="dropdown-item"><i class="fa-solid fa-pen"></i> Edit</a>
                                    <a href="/products/delete/<?= $product['id'] ?>" class="dropdown-item text-danger" onclick="return confirm('Are you sure?');"><i class="fa-solid fa-trash"></i> Delete</a>
                                    <a href="/products/edit_pro/<?= $product['id'] ?>" class="dropdown-item"><i class="fa-solid fa-eye"></i> Detail</a>
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
const products = <?= json_encode($products); ?>; // Convert PHP array to JavaScript
const productsPerPage = 10;
let currentPage = 1;
let totalPages = Math.ceil(products.length / productsPerPage);

function renderProducts(page) {
    currentPage = page;
    const start = (currentPage - 1) * productsPerPage;
    const end = start + productsPerPage;
    const currentProducts = products.slice(start, end);

    const tbody = document.getElementById('product-list');
    tbody.innerHTML = '';

    if (currentProducts.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" class="text-center">No products available.</td></tr>';
    } else {
        currentProducts.forEach(product => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="checkbox" class="product-checkbox" value="${product.id}"></td>
                <td><img src="/${product.image}" alt="Product Image" class="product-image"></td>
                <td>${product.name}</td>
                <td>${product.barcode}</td>
                <td>$${product.price}</td>
                <td><span class="badge bg-${product.stock > 0 ? 'success' : 'danger'}">${product.stock}</span></td>
                <td>${product.category}</td>
                <td>${product.created_at}</td>
                <td class="action-icons">
                    <div class="dropdown">
                        <i class="fa-solid fa-ellipsis-vertical" onclick="toggleDropdown(this)"></i>
                        <div class="dropdown-menu">
                            <a href="/products/edit_pro/${product.id}" class="dropdown-item"><i class="fa-solid fa-pen"></i> Edit</a>
                            <a href="/products/delete/${product.id}" class="dropdown-item text-danger" onclick="return confirm('Are you sure?');"><i class="fa-solid fa-trash"></i> Delete</a>
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
    renderProducts(1);
}

init();
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


</style>


<script>
    document.addEventListener("DOMContentLoaded", function() {
    document.addEventListener("click", function(event) {
        const sortOptions = document.getElementById("sort-options");
        const sortIcon = document.querySelector(".sort-icon");

        // Close dropdown if clicking outside (not on icon or options)
        if (sortOptions.style.display === "block" && !sortOptions.contains(event.target) && event.target !== sortIcon) {
            sortOptions.style.display = "none";
        }
    });
});

// Function to toggle visibility of Price filter
function toggleSortOptions(event) {
    event.stopPropagation(); // Prevents event from bubbling up to other elements
    closeAllSortOptions();  // Close any other open filters (Stock filter)
    const priceOptions = document.getElementById("price-sort-options"); // Get the Price dropdown
    priceOptions.style.display = priceOptions.style.display === "none" || priceOptions.style.display === "" ? "block" : "none"; // Toggle the dropdown
}


    function sortTable(columnIndex) {
        let table = document.querySelector(".table-container table");
        let rows = Array.from(table.rows).slice(1);
        let isAscending = table.dataset.sortOrder === "asc";

        rows.sort((a, b) => {
            let aValue = a.cells[columnIndex].textContent.trim();
            let bValue = b.cells[columnIndex].textContent.trim();
            return isAscending ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
        });

        table.tBodies[0].innerHTML = "";
        rows.forEach(row => table.tBodies[0].appendChild(row));
        table.dataset.sortOrder = isAscending ? "desc" : "asc";
    }

    function sortPrice(order) {
    let table = document.querySelector(".table-container table");
    let rows = Array.from(table.rows).slice(1);
    
    rows.sort((a, b) => {
        let aPrice = parseFloat(a.cells[4].textContent.replace('$', '').replace(',', ''));
        let bPrice = parseFloat(b.cells[4].textContent.replace('$', '').replace(',', ''));
        return order === 'high' ? bPrice - aPrice : aPrice - bPrice;
    });

    table.tBodies[0].innerHTML = "";
    rows.forEach(row => table.tBodies[0].appendChild(row));

    // Show alert and hide it after 2 seconds
    showToast(`Sorted by ${order === 'high' ? 'highest' : 'lowest'} price`);
    
    // Hide alert after sorting
    setTimeout(() => { hideToast(); });

    // Hide dropdown
    document.getElementById("sort-options").style.display = "none";
}

function searchPrice() {
    let input = document.getElementById("priceSearch").value.trim();
    let table = document.querySelector(".table-container table");
    let rows = Array.from(table.rows).slice(1);
    
    rows.forEach(row => {
        let priceText = row.cells[4].textContent.replace('$', '').replace(',', '').trim();
        let priceValue = parseFloat(priceText);
        
        // Show row if price matches, otherwise hide
        row.style.display = input === "" || priceText.includes(input) ? "" : "none";
    });

    // Hide alert when searching
    hideToast();
}

function toggleSortOptions(event) {
    event.stopPropagation();
    const options = document.getElementById("sort-options");
    options.style.display = options.style.display === "none" || options.style.display === "" ? "block" : "none";

    closeAllSortOptions();  // Close other filters (Price filter)
    options.style.display = options.style.display === "none" || options.style.display === "" ? "block" : "none"; // Toggle the dropdown
}

function showToast(message) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.style.display = 'block';
}

function hideToast() {
    const toast = document.getElementById('toast');
    toast.style.display = 'none';
}

// Hide dropdown if clicking outside
document.addEventListener("click", function(event) {
    const sortOptions = document.getElementById("sort-options");
    const sortIcon = document.querySelector(".sort-icon");

    if (sortOptions.style.display === "block" && !sortOptions.contains(event.target) && event.target !== sortIcon) {
        sortOptions.style.display = "none";
    }
});
document.addEventListener("DOMContentLoaded", function() {
    // Attach event listener to both input fields
    document.getElementById("priceSearch").addEventListener("keydown", handleEnterKey);
    document.getElementById("stockSearch").addEventListener("keydown", handleEnterKey);
});

function handleEnterKey(event) {
    if (event.key === "Enter") { 
        event.preventDefault(); // Prevent form submission if inside a form
        closeAllSortOptions();  // Close all dropdowns
    }
}

    document.addEventListener("DOMContentLoaded", function() {
        document.addEventListener("click", function(event) {
            const stockSortOptions = document.getElementById("stock-sort-options");

            // Close dropdown if clicking outside the stock sort options
            if (stockSortOptions.style.display === "block" && !stockSortOptions.contains(event.target)) {
                stockSortOptions.style.display = "none";
            }
        });
    });

    // Toggle the stock sort options dropdown
    // Function to toggle visibility of Stock filter
function toggleStockSortOptions(event) {
    
    event.stopPropagation(); // Prevents the event from bubbling up to other elements
    closeAllSortOptions();  // Close other filters (Price filter)
    const stockOptions = document.getElementById("stock-sort-options"); // Get the Stock dropdown
    stockOptions.style.display = stockOptions.style.display === "none" || stockOptions.style.display === "" ? "block" : "none"; // Toggle the dropdown
}

    // Function to sort by stock (high or low)
    function sortStock(order) {
        let table = document.querySelector(".table-container table");
        let rows = Array.from(table.rows).slice(1); // Get all rows except the header
        
        rows.sort((a, b) => {
            let aStock = parseInt(a.cells[5].textContent.trim()); // Assuming stock is in column 6 (index 5)
            let bStock = parseInt(b.cells[5].textContent.trim());
            return order === 'high' ? bStock - aStock : aStock - bStock;
        });

        table.tBodies[0].innerHTML = "";
        rows.forEach(row => table.tBodies[0].appendChild(row));

        // showToast(`Sorted by ${order === 'high' ? 'highest' : 'lowest'} stock`);
        
        // Hide dropdown after sorting
        document.getElementById("stock-sort-options").style.display = "none";
    }

    // Function to search within the stock column
    function searchStock() {
        let input = document.getElementById("stockSearch").value.trim();
        let table = document.querySelector(".table-container table");
        let rows = Array.from(table.rows).slice(1); // Get all rows except the header

        rows.forEach(row => {
            let stockText = row.cells[5].textContent.trim(); // Assuming stock is in column 6 (index 5)
            row.style.display = input === "" || stockText.includes(input) ? "" : "none";
        });

        hideToast(); // Hide toast when searching
    }

    // Function to show toast messages
    function showToast(message) {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.style.display = 'block';
    }

    // Function to hide toast messages
    function hideToast() {
        const toast = document.getElementById('toast');
        toast.style.display = 'none';
    }

    // Function to close all open filter dropdowns
    function closeAllSortOptions() {
    const allSortOptions = document.querySelectorAll(".sort-options");
    allSortOptions.forEach(option => {
        option.style.display = "none"; // Close all dropdowns
    });
}
// drop down for action

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


</script>
<style>

    .btn
    .sort-options {
        position: absolute;
        background-color: white;
        border: 1px solid #ccc;
        z-index: 1000;
    }
    .sort-options button {
        display: block;
        width: 100%;
        padding: 8px;
        border: none;
        background: none;
        cursor: pointer;
    }
    .sort-options button:hover {
        background-color: #f0f0f0;
    }
</style>
<script>
    function toggleAllCheckboxes(source) {
        document.querySelectorAll('tbody input[type="checkbox"]').forEach(checkbox => checkbox.checked = source.checked);
        updateDeleteIcon();
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

</script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<?php else: $this->redirect("/"); endif; ?>