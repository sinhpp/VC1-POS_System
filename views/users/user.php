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
    <!-- </style> -->
<!-- </head> -->
<!-- <body> -->

    <!-- Sidebar -->
    <!-- <nav class="sidebar d-flex flex-column flex-shrink-0">
        <h3 class="text-center">Admin Panel</h3>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="/dashboard" class="nav-link">
                    <i class="material-icons">dashboard</i> Dashboard
                </a>
            </li>
            <li>
                <a href="/users" class="nav-link">
                    <i class="material-icons">group</i> Users
                </a>
            </li>
            <li>
                <a href="/settings" class="nav-link">
                    <i class="material-icons">settings</i> Settings
                </a>
            </li>
            <li>
                <a href="/" class="nav-link">
                    <i class="material-icons">logout</i> Logout
                </a>
            </li>
            <li>
                <a href="/products" class="nav-link">
                    <i class="material-icons"></i> products
                </a>
            </li>
        </ul>
    </nav> -->

    <!-- Main Content Area -->
    <div class="content">
    <h2 class="text-center mb-4">Users List</h2>
 
 <div class="d-flex justify-content-between align-items-center mb-3">
     <a href="/users/create" class="btn btn-success">+ Create User</a>
 </div>

 <style>
    .table-responsive {
        margin: 20px; /* Optional: Add some margin to the table */
    }

    .table th, .table td {
        text-align: center; /* Center align text in headers and data cells */
    }

    .table th {
        background-color: #add8e6; /* Light blue color */
        color: #000; /* Text color for better contrast */
    }

    .role-badge {
        display: inline-block; /* Ensure the badge behaves like a block */
        width: 100px; /* Set a specific width for balance */
        text-align: center; /* Center align the text */
    }
</style>
<style>
    .table-responsive {
        margin: 20px; /* Optional: Add some margin to the table */
    }

    .table th, .table td {
        text-align: center; /* Center align text in headers and data cells */
    }

    .table th {
        background-color: #add8e6; /* Light blue color */
        color: #000; /* Text color for better contrast */
    }

    .role-badge {
        display: inline-block; /* Ensure the badge behaves like a block */
        width: 100px; /* Set a specific width for balance */
        text-align: center; /* Center align the text */
    }
</style>
<div class="table-responsive">
    <table class="table table-striped table-hover shadow-sm rounded">
        <thead>
            <tr>
                <th>Id</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $index = 1; // Initialize a counter for the sequential ID
            foreach ($users as $user): ?>
            <tr>
                <td><?= $index++ ?></td> <!-- Use the counter for ID -->
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                    <span class="badge <?= $user['role'] === 'admin' ? 'bg-success' : 'bg-info' ?> role-badge">
                        <?= htmlspecialchars($user['role']) ?>
                    </span>
                </td>
                <td>
                    <a href="/users/edit/<?= $user['id'] ?>" class="btn btn-warning btn-sm mx-1">
                        <i class="material-icons">edit</i>
                    </a>
                    <a href="#" class="btn btn-danger btn-sm mx-1" onclick="confirmDelete(<?= $user['id'] ?>)">
                        <i class="material-icons">delete</i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(userId) {
        Swal.fire({
            title: 'Do you want to delete this user?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Store success message
                sessionStorage.setItem('deleteSuccess', 'true');

                // Redirect to delete URL
                window.location.href = '/users/delete/' + userId;
            }
        });
    }

    // Show the success alert after page reload
    document.addEventListener("DOMContentLoaded", function () {
        if (sessionStorage.getItem('deleteSuccess')) {
            Swal.fire({
                title: 'Deleted!',
                text: 'User has been deleted successfully.',
                icon: 'success',
                timer: 1000, // Auto close after 3 seconds
                showConfirmButton: false
            });

            // Remove the session storage flag
            sessionStorage.removeItem('deleteSuccess');
        }
    });
</script>

<!-- Optional: Add a placeholder for success alert -->
<div id="success-alert" style="display: none;"></div>

</body>
</html>
<style>
    /* Main Content Area */
.content {
    font-family: "Poppins", sans-serif;
    /* width: 82%; */
    /* height: 100%; */
    margin-left:23.3%;
    background-color: #f8f9fa;
    /* border-radius: 8px; Rounded corners */
    /* box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); Subtle shadow */
}

/* Table Styles */
.table {
    border-radius: 8px; /* Rounded corners for the table */
    overflow: hidden; /* Ensure corners are rounded */
}


.table th {
    background-color: #343a40; /* Dark background for header */
    color: #ffffff; /* White text for header */
}

.table-striped tbody tr:nth-child(odd) {
    background-color: #f2f2f2; /* Light gray for odd rows */
}

.table-hover tbody tr:hover {
    background-color: #e9ecef; /* Light gray on hover */
}
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<?php 
else: 
    $this->redirect("/"); 
endif;   
?>