
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) : ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    

    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="/views/assets/js/product.js"></script>
       <!-- <style> -->
        /* Sidebar Styles */
        .sidebar {
            font-family: "Poppins", sans-serif;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background-color: #343a40;
            padding: 15px;
            color: white;
        }
        .sidebar .nav-link {
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
        }
        .sidebar .nav-link:hover {
            background-color: #495057;
            border-radius: 5px;
        }
        .material-icons {
            font-size: 20px;
        }
        /* Content area */
        .content {
            margin-left: 170px; /* Adjust based on sidebar width */
            padding: 50px;
        }
   
    <style>
        
        .table {
            width: 100%;
            margin-left: 70px;
        }
        .table th, .table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .table-striped tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }
        .table-dark {
            background-color: #343a40;
            color: white;
        }
        .badge.bg-success { background-color: #28a745; color: white; }
        .badge.bg-danger { background-color: #dc3545; color: white; }
        
        .btn {
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom:2%;
        }
        .btn-warning { background-color: #ffc107; color: black; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn:hover { background-color: #495057; }

        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            font-family: "Poppins", sans-serif;
            
        }

        .table-container {
            margin-top: 5%;
            padding: 20px;
            border-radius: 8px;
            margin-left: 23%;
        }

        table {
            width: 100%%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #96dbe4;
            cursor: pointer;
        }

        th i {
            font-size: 14px;
            margin-left: 5px;
        }

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
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

        .table-container table th:nth-child(6),
    .table-container table td:nth-child(6) {
        width: 120px; /* Set a specific width for the stock column */
        text-align: center; /* Center align the text */
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
        cursor: pointer; /* Change cursor to pointer */
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
        /* Add this to your existing CSS */
    #delete-icon {
    display: none; /* Initially hidden */
    background-color: red; /* Background */
    padding: 5px;
    border-radius: 5px; /* Rounded */
    cursor: pointer;
    transition: 0.3s ease-in-out;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 30px;  /* Set width */
    height: 30px; /* Set height */
}

/* Change text color */
#delete-icon {
    font-size: 18px; /* Adjust icon size */
    color: white !important; /* Ensure text is white */
}

    </style>
</head>
<body>
</head>
<body>
<!-- Include SweetAlert2 CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="table-container">
<a href="/products/create" class="btn btn-success">+ Add Product</a>
    <table>
        <thead>
            <tr>
                <th>
                    <div class="alert" id="toast" style="display:none;">Delete all!</div>
                    <input type="checkbox" onclick="toggleAllCheckboxes(this)">
                </th>
                <th >Image</th>
                <th >NAME</th>
                <th>CODE</th>
                <th>
                PRICE 
                <i class="fa-solid fa-filter-circle-dollar" onclick="toggleSortOptions(event)"></i>

                
                <div class="sort-options" id="sort-options" style="display: none; position: absolute; background: white; border: 1px solid #ccc; padding: 5px;">
                <input type="text" id="priceSearch" placeholder="Search price..." oninput="searchPrice()" style="margin-top:5px; padding: 5px; width: 100%;">
                    <button onclick="sortPrice('high')" style="display: block; width: 100%; text-align: left;">><i class="fas fa-arrow-down"></i> High</button>
                    <button onclick="sortPrice('low')" style="display: block; width: 100%; text-align: left;">><i class="fas fa-arrow-up"></i> Low</button>
                </div>
            </th>

            <th>STOCK
    <i class="fa-solid fa-filter-circle-dollar" onclick="toggleStockSortOptions(event)"></i>
    <div class="sort-options" id="stock-sort-options" style="display: none; position: absolute; background: white; border: 1px solid #ccc; padding: 5px;">
        <input type="text" id="stockSearch" placeholder="Search stock..." oninput="searchStock()" style="margin-top:5px; padding: 5px; width: 100%;">
        <button onclick="sortStock('high')" style="display: block; width: 100%; text-align: left;">
            <i class="fas fa-arrow-down"></i> High
        </button>
        <button onclick="sortStock('low')" style="display: block; width: 100%; text-align: left;">
            <i class="fas fa-arrow-up"></i> Low
        </button>
    </div>
                </th>
                <th >CATEGORY</th>
                <th >CREATED AT</th>
           <th>ACTION
                <i class="material-icons" id="delete-icon" onclick="handleDelete()" style="display: none; color: red; cursor: pointer;">delete</i>
            </th>
         </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
                <tr>
                    <td colspan="8" class="text-center">No products available.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><input type="checkbox" class="product-checkbox" value="<?= htmlspecialchars($product['id']) ?>"></td>
                        <td><img src="/<?= htmlspecialchars($product['image']) ?>" alt="Product Image" class="product-image"></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($product['barcode']) ?></td>
                        <td>$<?= number_format($product['price'], 2) ?></td>
                        <td>
                            <span class="badge bg-<?= $product['stock'] > 0 ? 'success' : 'danger' ?>">
                                <?= htmlspecialchars($product['stock']) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($product['category']) ?></td>
                        <td><?= htmlspecialchars($product['created_at']) ?></td>
                        <td class="action-icons">
                            <a href="/products/edit_pro/<?= $product['id'] ?>" class="btn btn-warning btn-sm mx-1">
                                <i class="material-icons">edit</i>
                            </a>
                            <a href="/products/delete/<?= $product['id'] ?>" class="btn btn-danger btn-sm mx-1" onclick="return confirm('Are you sure you want to delete this product?');">
                                <i class="material-icons">delete</i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
  
</div>

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

// </script>
// <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
// <?php else: $this->redirect("/"); endif; ?>