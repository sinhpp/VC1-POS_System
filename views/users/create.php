
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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            
            min-height: 100vh;
            
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 500px;
            margin-left:18%;
        }
        .form-control:focus {
            border-color:rgb(57, 132, 194);
            box-shadow: 0px 0px 8px rgba(106, 17, 203, 0.5);
            
        }
        .btn-custom {
            background:rgb(54, 82, 173);
            border: none;
            transition: all 0.3s ease-in-out;
        }
        .btn-custom:hover {
            background: #2575fc;
            transform: scale(1.05);
        }
       
    </style>
</head>
<body>

<div class="form-container">
    <!-- Show Error Message if there is one -->
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger text-center">
            <?= $_SESSION['error_message']; ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <h3 class="text-center mb-4">User Registration</h3>
    
    <form action="/users/storeuser" method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" id="name" placeholder="Enter name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role:</label>
            <select name="role" id="role" class="form-control" required>
                <option value="" disabled selected>Select Role</option>
                <option value="admin">Admin</option>
                <option value="cashier">Cashier</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="pwd" class="form-label">Password:</label>
            <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="password" required>
        </div>
        
        <button type="submit" class="btn btn-custom w-100">Submit</button>
        <a href="/users" class="btn btn-secondary w-100 mt-2">Cancel</a>
    </form>
</div>

</body>
</html>
