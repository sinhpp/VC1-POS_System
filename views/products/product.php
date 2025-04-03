
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
       body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    color: #333;
}

.table-responsive {
    margin: 20px auto;
    max-width: 90%;
    overflow: hidden;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    background: white;
}



.header h1 {
    margin: 0;
    font-size: 24px;
    font-weight: bold;
}

.header-buttons {
    display: flex;
    gap: 10px;
}

.header-buttons .btn {
    background-color: #ffffff; /* White background for buttons */
    color: #007bff; /* Blue text */
    border: 1px solid #007bff; /* Blue border */
    padding: 10px 15px;
    border-radius: 5px;
    transition: background-color 0.3s, color 0.3s;
}

.header-buttons .btn:hover {
    background-color: #007bff; /* Blue background on hover */
    color: white; /* White text on hover */
}

.table {
    width: 100%;
    border-collapse: separate;
    text-align: left;
}
.table-container{
    width: 76%;
    margin-top: 10%;
    margin-left: 23%;
}

.table thead th {
    background-color: teal; /* Darker gray for header */
    color: white;
    padding: 12px 15px;
    font-weight: 600;
}

.table th, .table td {
    padding: 12px 15px;
    border-bottom: 1px solid #dee2e6;
}

.table tr:hover {
    background-color: rgba(0, 123, 255, 0.1); /* Light blue on hover */
}

.user-info {
    display: flex;
    align-items: center;
}

.placeholder-image {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e0e0e0;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #666;
    font-weight: bold;
    margin-right: 10px;
}

.status-indicator {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 5px;
}

.status-active {
    background-color: #28a745; /* Green for active */
}

.status-suspended {
    background-color: #dc3545; /* Red for suspended */
}

.status-inactive {
    background-color: #ffc107; /* Yellow for inactive */
}

.action-buttons {
    display: flex;
    gap: 5px;
}

.action-buttons .btn {
    padding: 5px 10px;
    border-radius: 5px;
}

.action-buttons .btn-warning {
    background-color: #ffc107; /* Yellow for edit */
    color: white;
}

.action-buttons .btn-danger {
    background-color: #dc3545; /* Red for delete */
    color: white;
}

.pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    border-top: 1px solid #dee2e6;
}

.pagination {
    margin: 0;
}

.pagination .page-item.active .page-link {
    background-color: #007bff; /* Active page color */
    border-color: #007bff;
}

.pagination .page-link {
    color: #007bff; /* Page link color */
}

.action-icons {
    display: flex; /* Use flexbox */
    justify-content: center; /* Center horizontally */
    align-items: center; /* Center vertically */
    padding: 5px; /* Adjust padding as needed */
    border-radius: 5px; /* Rounded corners */
}


/* Responsive styles */
@media (max-width: 768px) {
    .header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .header-buttons {
        width: 100%;
        justify-content: flex-end;
    }

    .table th, .table td {
        padding: 8px 10px;
    }

    .pagination-container {
        flex-direction: column;
        gap: 10px;
    }
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
                <th>Category
                <!-- Dropdown Icon -->
                <i class="fa-solid fa-filter-circle-dollar" onclick="toggleCatSortOptions(event, this)" style="cursor: pointer;"></i>

                <!-- Dropdown Menu -->
                <div class="sort-options" id="cat-sort-options" style="display: none;">
                    <input type="text" id="stockSearch" placeholder="Search categories..." oninput="searchCat()" style="margin-top: 5px; padding: 5px; width: calc(100% - 10px);">
                    <div id="categoryList">
                        <button onclick="selectCategory('Clothes', event)">Clothes</button>
                        <button onclick="selectCategory('Bags', event)">Bags</button>
                        <button onclick="selectCategory('Shoes', event)">Shoes</button>
                        <button onclick="selectCategory('Accessories', event)">Accessories</button>
                    </div>
                </div>
            </th>

<script>
    function toggleCatSortOptions(event) {
        const sortOptions = document.getElementById('cat-sort-options');
        sortOptions.style.display = sortOptions.style.display === 'block' ? 'none' : 'block';

        // Position the dropdown menu correctly (if necessary)
        const iconPos = event.target.getBoundingClientRect();
        sortOptions.style.position = 'absolute'; // Use absolute positioning
        sortOptions.style.top = `${iconPos.bottom + window.scrollY}px`;
        sortOptions.style.left = `${iconPos.left}px`;
    }

    function searchCat() {
        const input = document.getElementById('catSearch').value.toLowerCase();
        const buttons = document.querySelectorAll('#categoryList button');

        buttons.forEach(button => {
            const text = button.textContent.toLowerCase();
            button.style.display = text.includes(input) ? 'block' : 'none';
        });
    }

    function selectCategory(category) {
        console.log('Selected category:', category);
        document.getElementById('cat-sort-options').style.display = 'none'; // Hide dropdown after selection
    }

    // Close dropdown when clicking outside of it
    window.onclick = function(event) {
        const dropdown = document.getElementById('cat-sort-options');
        if (!event.target.matches('.fa-filter-circle-dollar') && dropdown.style.display === 'block') {
            dropdown.style.display = 'none';
        }
    };
</script>

<style>
    .sort-options {
        background: white; 
        border: 1px solid #ccc; 
        padding: 5px; 
        z-index: 100; 
        border-radius: 5px; 
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    }

    .sort-options button {
        display: block;
        width: 100%; 
        text-align: left; 
        margin: 5px 0;
        border: none;
        background: transparent;
        cursor: pointer;
    }
    
    .sort-options button:hover {
        background-color: #f0f0f0; /* Highlight on hover */
    }
</style>
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
                        <td class="category-cell"><?= htmlspecialchars($product['category']) ?></td> <!-- This cell will be updated -->                        <td><?= htmlspecialchars($product['created_at']) ?></td>
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