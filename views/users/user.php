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
            padding: 10px;
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
            margin-left: 270px; /* Adjust based on sidebar width */
            padding: 20px;
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
        <h1 class="text-center mb-4">Users List</h1>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="/users/create" class="btn btn-success">+ Create User</a>
        </div>

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
                            <a href="/users/delete/<?= $user['id'] ?>" class="btn btn-danger btn-sm mx-1">
                                <i class="material-icons">delete</i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>

<?php 
else: 
    $this->redirect("/"); 
endif;   
?>