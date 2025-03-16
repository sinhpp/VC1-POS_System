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
    </style>
</head>
<body>

    <!-- Sidebar -->
    <nav class="sidebar d-flex flex-column flex-shrink-0">
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
    </nav>

    <!-- Main Content Area -->
    <div class="content">
        <h2 class="text-center mb-4">Users List</h2>

      

        <div class="table-responsive">
            <table class="table table-striped table-hover shadow-sm rounded">
                <thead class="table-dark">
                    <tr>
                        <th>Id</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= $user['name'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><span class="badge bg-info text-dark"><?= $user['role'] ?></span></td>
                        <td>
                            <a href="/users/edit/<?= $user['id'] ?>" class="btn btn-warning btn-sm mx-1">
                                <i class="material-icons">edit</i>
                            </a>
                           
                                                        <!-- Delete Button -->
                            <a href="#" class="btn btn-danger btn-sm mx-1" onclick="confirmDelete(<?= $user['id'] ?>)">
                                <i class="material-icons">delete</i>
                            </a>

                            <script>
                            function confirmDelete(userId) {
                                Swal.fire({
                                    title: 'Do you want to delete this user?',
                                    text: "You won't be able to revert this!",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#dc3545', // Bootstrap danger color
                                    cancelButtonColor: '#6c757d', // Bootstrap secondary color
                                    confirmButtonText: 'Yes, delete it!',
                                    cancelButtonText: 'Cancel'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // If user confirms, redirect to delete URL
                                        window.location.href = '/users/delete/' + userId;

                                        // Show success alert after deletion
                                        Swal.fire(
                                            'Deleted!',
                                            'User has been deleted successfully.',
                                            'success'
                                        );
                                    }
                                });
                            }
                            </script>

                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
<style>
    /* Main Content Area */
.content {
    font-family: "Poppins", sans-serif;
    width: 82%;
    height: 100%;
    margin-left:18%;
    background-color: #f8f9fa; /* Light background */
    border-radius: 8px; /* Rounded corners */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
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