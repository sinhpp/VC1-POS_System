<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) : ?>

<div class="container-fluid">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .table-responsive {
            position: relative;
            left: 8%;
            margin: 20px;
            max-width: 80%; /* Set to 90% */
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background: linear-gradient(145deg, #ffffff, #f9f9f9);
            margin-top: 10%;
            margin-left: auto; /* Center the table */
            margin-right: auto; /* Center the table */
        }

        .table {
          
            width: 80%; /* Make the table take full width */
            border-collapse: separate;
            justify-content: center;
            text-align: center;
            margin-left: 10%;
        }
        .table img {
            max-width: 100% !important;
            height: auto !important;
            border-radius: 5% !important; /* Adjust as needed */
            display: block; /* Ensures it behaves as expected */
        }
        

                .btn{
            margin-top: 2%;
            margin-left: 12%;
        }
        .table thead th {
            background-color: #f8f9fa;
            padding: 12px;
            text-align: center;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #333;
        }

        .table th, .table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .table th {
            background-color: #007bff !important;
            color: #fff;
            font-weight: 600;
            justify-content: center;
            align-items: center;
            top: 0;
            z-index: 2;
        }

        .table tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
            transition: background-color 0.3s ease;
            cursor: pointer; /* Pointer cursor for hover effect */
        }

        .profile-image {
            width: 80px;
            height: 80px;
            border-radius: 50%; /* Make the image circular */
        }

        .placeholder-image {
            width: 40px;
            height: 40px;
            border-radius: 50%; /* Circular placeholder */
            background-color: #e0e0e0; /* Light gray background */
            display: flex;
            justify-content: center;
            align-items: center;
            color: #666; /* Text color for placeholder */
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container-fluid table-responsive">
        <table class="table table-striped table-hover shadow-sm rounded">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="/users/create" class="btn btn-success">+ Create User</a>
            </div>
            <thead>
                <tr>
                    <th>Profile</th> <!-- Image Column -->
                    <th>Username</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td onclick="window.location.href='/users/view/<?= $user['id'] ?>'" style="cursor: pointer;">
                        <?php if (!empty($user['image'])): ?>
                            <img src="/<?= htmlspecialchars($user['image']) ?>" alt="Profile Image" class="profile-image">
                        <?php else: ?>
                            <div class="placeholder-image">No Image</div>
                        <?php endif; ?>
                    </td>
                    <td onclick="window.location.href='/users/view/<?= $user['id'] ?>'" style="cursor: pointer;">
                        <?= htmlspecialchars($user['name']) ?>
                    </td>
                    <td onclick="window.location.href='/users/view/<?= $user['id'] ?>'" style="cursor: pointer;">
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

<!-- Live Alert Placeholder -->
<div id="liveAlertPlaceholder"></div>
<button type="button" class="btn btn-primary" id="liveAlertBtn" style="display: none;">Show live alert</button>

<script>
 function confirmDelete(userId) {
Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel'
}).then((result) => {
    if (result.isConfirmed) {
        // Show live alert
        showLiveAlert('User has been deleted successfully.', 'success');

        // Redirect to delete URL after a delay
        setTimeout(() => {
            window.location.href = '/users/delete/' + userId;
        }, 3000); // 3 seconds delay
    }
});
}

function showLiveAlert(message, type) {
const alertPlaceholder = document.getElementById('liveAlertPlaceholder');

// Clear any existing alerts
alertPlaceholder.innerHTML = '';

// Create the alert element
const wrapper = document.createElement('div');
wrapper.innerHTML = `
    <div class="alert alert-${type} alert-dismissible" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
`;

// Append the alert to the placeholder
alertPlaceholder.appendChild(wrapper);

// Remove the alert after 3 seconds
setTimeout(() => {
    wrapper.remove();
}, 3000);
}
</script>
</body>

<?php 
else: 
    $this->redirect("/"); 
endif;   
?>