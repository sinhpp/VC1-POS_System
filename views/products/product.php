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
    <link rel="stylesheet" href="../../views/assets/css/product.css"> 
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
                    <option value="">Categories</option>
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

            
                <button id="addProductBtn" class="button">
                    Add Product
                </button>
                
            <!-- Modal -->
            <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
                <div class="modal-dialog">
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
        <th class="col-code">Code</th>
        <th>Stock</th>
        <th>Category</th>
        <th>Price</th>
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
                        <td class="col-code"><?= htmlspecialchars($product['barcode']) ?></td>
                        <td><span class="badge bg-<?= $product['stock'] > 0 ? 'success' : 'danger' ?>"><?= htmlspecialchars($product['stock']) ?></span></td>
                        <td class="category-cell"><?= htmlspecialchars($product['category']) ?></td>
                        <td>$<?= number_format($product['price'], 2) ?></td>
                      
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
let filteredProducts = [...products]; // Initialize filtered products with all products
const productsPerPage = 10;
let currentPage = 1;
let totalPages = Math.ceil(products.length / productsPerPage);

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
            else if (stockFilter === 'medium' && stock > 10 && stock <= 20) stockMatch = true;
            else if (stockFilter === 'high' && stock > 20) stockMatch = true;
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
                <td class="col-code">${product.barcode}</td>
                
                <td><span class="badge bg-${product.stock > 0 ? 'success' : 'danger'}">${product.stock}</span></td>
                <td class="category-cell">${product.category}</td>
                <td>${parseFloat(product.price).toFixed(2)}</td>
              
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
    
    // Re-attach event listeners to checkboxes
    document.querySelectorAll('.product-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateDeleteIcon);
    });
}

function renderPagination() {
    const paginationDiv = document.getElementById('pagination-buttons');
    paginationDiv.innerHTML = ''; // Clear previous pagination buttons
    
    // Add previous button
    if (totalPages > 1) {
        const prevButton = document.createElement('button');
        prevButton.classList.add('page-btn', 'prev-btn');
        prevButton.innerHTML = '&laquo;';
        prevButton.disabled = currentPage === 1;
        prevButton.onclick = function() {
            if (currentPage > 1) {
                renderProducts(currentPage - 1);
            }
        };
        paginationDiv.appendChild(prevButton);
    }
    
    // Calculate which page buttons to show
    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(totalPages, startPage + 4);
    
    // Adjust if we're near the end
    if (endPage - startPage < 4 && startPage > 1) {
        startPage = Math.max(1, endPage - 4);
    }
    
    // Add page number buttons
    for (let i = startPage; i <= endPage; i++) {
        const button = document.createElement('button');
        button.classList.add('page-btn');
        button.textContent = i;
        button.onclick = function() {
            renderProducts(i);
        };
        
        if (i === currentPage) {
            button.classList.add('active');
        }
        
        paginationDiv.appendChild(button);
    }
    
    // Add next button
    if (totalPages > 1) {
        const nextButton = document.createElement('button');
        nextButton.classList.add('page-btn', 'next-btn');
        nextButton.innerHTML = '&raquo;';
        nextButton.disabled = currentPage === totalPages;
        nextButton.onclick = function() {
            if (currentPage < totalPages) {
                renderProducts(currentPage + 1);
            }
        };
        paginationDiv.appendChild(nextButton);
    }
    
    // Add page counter if there are pages
    if (totalPages > 0) {
        const pageInfo = document.createElement('span');
        pageInfo.classList.add('page-info');
        pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
        paginationDiv.appendChild(pageInfo);
    }
}

// Initial load - Now only 10 products will show on page 1
function init() {
    // Connect filter events
    document.getElementById('category-filter').addEventListener('change', filterProducts);
    document.getElementById('stock-filter').addEventListener('change', filterProducts);
    document.getElementById('product-search').addEventListener('input', filterProducts);
    
    renderProducts(1);
}

// Call init when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    init();
    
    // Add event listeners to all checkboxes
    document.querySelectorAll('.product-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateDeleteIcon);
    });
});
</script>

<style>
/* Pagination Button Styling */
.pagination {
    margin-left: -55%;
    margin-top: 10px;
    padding: 10px 0;
    display: flex;
    justify-content: center; /* Centers items horizontally */
    align-items: center; /* Centers items vertically */
    flex-wrap: wrap; /* Allows buttons to wrap on smaller screens */
    gap: 5px;
    width: 100%; /* Ensures the container takes full width */
}

.page-btn {
    background-color: #f8f9fa;
    border: 1px solid #ddd;
    padding: 8px 12px;
    margin: 2px;
    cursor: pointer;
    border-radius: 4px;
    min-width: 40px; /* Ensures consistent button width */
    text-align: center; /* Centers text within buttons */
}

.page-btn:hover {
    background-color: #e9ecef;
}

.page-btn.active {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.prev-btn, .next-btn {
    font-weight: bold;
}

.page-info {
    margin: 0 10px;
    color: white;
    text-align: center; /* Centers the page info text */
}

.page-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
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