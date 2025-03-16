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

    <style>
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
        /* Table Styles */
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
        }
        .btn-warning { background-color: #ffc107; color: black; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn:hover { background-color: #495057; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    
    <nav class="sidebar">
        <h3 class="text-center">Admin Panel</h3>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item"><a href="/dashboard" class="nav-link"><i class="material-icons">dashboard</i> Dashboard</a></li>
            <li><a href="/users" class="nav-link"><i class="material-icons">group</i> Users</a></li>
            <li><a href="/settings" class="nav-link"><i class="material-icons">settings</i> Settings</a></li>
            <li><a href="/" class="nav-link"><i class="material-icons">logout</i> Logout</a></li>
            <li><a href="/products" class="nav-link"><i class="material-icons">shopping_cart</i> Products</a></li>
        </ul>
    </nav>

    <!-- Table Content -->
   
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            font-family: "Poppins", sans-serif;
            margin-left: 50px;
        }

        .table-container {
            max-width: 1700px;
           
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
        /* Add this to your existing CSS */
.btn-success {
    background-color:rgb(64, 196, 90); /* Green background */
    color: white;             /* White text */
    border: none;             /* No border */
    padding: 10px 20px;       /* Padding for the button */
    font-size: 16px;          /* Font size */
    border-radius: 5px;       /* Rounded corners */
    cursor: pointer;          /* Pointer cursor on hover */
    transition: background-color 0.3s; /* Smooth transition */
  
    margin-top: 500px;         /* Space above the button */
    margin-left: -300px;         /* Space above the button */
}

.btn-success:hover {
    background-color: #218838; /* Darker green on hover */
}
    </style>
</head>
<body>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" onclick="toggleAllCheckboxes(this)"></th>
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
                            <td><?= htmlspecialchars($product['id']) ?></td>
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
                                
                                <td>
                                    <a href="/products/edit/<?= $product['id'] ?>" class="btn btn-warning btn-sm mx-1">
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
        <a href="/products/create" class="btn btn-success">+ Add Product</a>
    <script>
        function sortTable(columnIndex) {
            let table = document.getElementById("productTable");
            let rows = Array.from(table.rows);
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

            table.innerHTML = "";
            rows.forEach(row => table.appendChild(row));

            table.dataset.sortOrder = isAscending ? "desc" : "asc";
        }

        function toggleAllCheckboxes(source) {
            let checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
            checkboxes.forEach(checkbox => checkbox.checked = source.checked);
        }
    </script>

</body>
</html>


<?php 
else: 
    $this->redirect("/"); 
endif;   
?>