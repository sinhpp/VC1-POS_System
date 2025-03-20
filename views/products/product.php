
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

    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="/views/assets/js/product.js"></script>

    <style>
        
        .table {
            width: 100%;
            margin-left: 50%;
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
        }
        .btn-warning { background-color: #ffc107; color: black; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn:hover { background-color: #495057; }

        body {
            font-family: Arial, sans-serif;
          
            font-family: "Poppins", sans-serif;
            margin-left: 18%;
        }

        .table-container {
            max-width: 80%;
            margin-left: 22%;
            margin-top: 10%;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
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
    </style>
</head>
<body>
<!-- Include SweetAlert2 CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Include SweetAlert2 CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>
                    <div class="alert" id="toast" style="display:none;">
                        Delete all!
                    </div>
                    <input type="checkbox" onclick="toggleAllCheckboxes(this)">
                </th>
                <th onclick="sortTable(1)">Image <i class="fas fa-sort"></i></th>
                <th onclick="sortTable(2)">NAME <i class="fas fa-sort"></i></th>
                <th onclick="sortTable(3)">CODE <i class="fas fa-sort"></i></th>
                <th onclick="sortTable(4)">PRICE <i class="fas fa-sort"></i></th>
                <th onclick="sortTable(5)">STOCK <i class="fas fa-sort"></i></th>
                <th onclick="sortTable(6)">CATEGORY <i class="fas fa-sort"></i></th>
                <th onclick="sortTable(7)">CREATED AT <i class="fas fa-sort"></i></th>
                <th>ACTION</th>
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
    <a href="/products/create" class="btn btn-success">+ Add Product</a>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
    // List of stylesheets to disable
    const stylesToDisable = [
        "/views/assets/css/form.css",
        "/views/assets/css/form.forgot.password.css",
        "https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap",
        "https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"
    ];

    // Disable the stylesheets
    document.querySelectorAll("link[rel='stylesheet']").forEach(link => {
        if (stylesToDisable.includes(link.getAttribute("href"))) {
            link.disabled = true; // Disable the stylesheet
        }
    });
});

function sortTable(columnIndex) {
    let table = document.querySelector(".table-container table");
    let rows = Array.from(table.rows).slice(1); // Skip the header row
    let isAscending = table.dataset.sortOrder === "asc";

    rows.sort((a, b) => {
        let aValue = a.cells[columnIndex].textContent.trim();
        let bValue = b.cells[columnIndex].textContent.trim();

        if (!isNaN(aValue) && !isNaN(bValue)) {
            return isAscending ? aValue - bValue : bValue - aValue;
        } else {
            return isAscending ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
        }
    });

    table.tBodies[0].innerHTML = ""; // Clear the existing rows
    rows.forEach(row => table.tBodies[0].appendChild(row)); // Append sorted rows

    table.dataset.sortOrder = isAscending ? "desc" : "asc";
}

    function toggleAllCheckboxes(source) {
        let checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
        checkboxes.forEach(checkbox => checkbox.checked = source.checked);
    }
</script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<?php 
else: 
    $this->redirect("/"); 
endif;   
?>