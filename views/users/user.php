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

    <!-- Responsive meta tag -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .table-responsive {
            position: relative;
            left:10%;
            margin: 20px auto;
            max-width: 80%;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background: linear-gradient(145deg, #ffffff, #f9f9f9);
            margin-top: 10%;
        }

        .table {
            width: 80%;
            border-collapse: separate;
            border-spacing: 0;
            display: block;
            margin-left:7%;
            justify-content: center;
            text-align: center;
            margin-left: 10%;
        }
        
        .table img {
            max-width: 66px !important;
            height: 66px !important;
            border-radius: 50% !important; /* Adjust as needed */
       
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
            background-color: #8A5AD9 !important;
            color: white !important;
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
            border-radius: 80%; /* Make the image circular */
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
        }
         
        .alert-small {
            position: fixed;
            top: 15px;
            right: 15px;
            background-color: #4CAF50 !important;
            border-radius: 5%;
            max-height: 8% !important;
            z-index: 1000;
            max-width: 300px;
            width: 100%;
            opacity: 0.9;
        }
        
        /* Responsive styles for tablet */
        @media (max-width: 992px) {
            .table-responsive {
                max-width: 95%;
                margin-top: 15%;
            }
            
            .table {
                width: 90%;
                margin-left: 5%;
            }
            
            .btn {
                margin-left: 5%;
            }
            
            .profile-image {
                width: 60px;
                height: 60px;
            }
        }
        
        /* Responsive styles for mobile */
        @media (max-width: 768px) {
            .table-responsive {
                max-width: 100%;
                margin: 10px 0;
                margin-top: 20%;
                border-radius: 0;
            }
            
            .table {
                width: 100%;
                margin-left: 0;
                font-size: 0.9rem;
            }
            
            .table th, .table td {
                padding: 8px 5px;
            }
            
            .btn {
                margin-left: 5%;
                margin-bottom: 10px;
                display: block;
            }
            
            .profile-image {
                width: 50px;
                height: 50px;
            }
            
            .btn-sm {
                padding: 0.2rem 0.4rem;
                font-size: 0.75rem;
            }
            
            /* Stack action buttons on mobile */
            .action-buttons {
                display: flex;
                flex-direction: column;
            }
            
            .action-buttons .btn {
                margin: 2px 0;
            }
        }
        
        /* Extra small devices */
        @media (max-width: 576px) {
            .table th:nth-child(3), .table td:nth-child(3) { /* Hide role column on very small screens */
                display: none;
            }
            
            .profile-image {
                width: 40px;
                height: 40px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid table-responsive">
        
        <table class="table table-striped table-hover shadow-sm rounded">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="/users/create" class="btn1 btn-success">+ Create User</a>
        </div>
            <thead>
                <tr>
                    <th>Profile</th>
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
                        <div class="action-buttons">
                            <a href="/users/edit/<?= $user['id'] ?>" class="btn btn-warning btn-sm mx-1">
                                <i class="material-icons">edit</i>
                            </a>
                            <a href="#" class="btn btn-danger btn-sm mx-1" onclick="confirmDelete(<?= $user['id'] ?>)">
                                <i class="material-icons">delete</i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

  <!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Live Alert Placeholder -->
<div id="liveAlertPlaceholder" class="alert-small"></div>

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
            // Use custom class for successful deletion alert
            showLiveAlert('Deleted successfully.', 'custom-success');

            // Redirect to delete URL after a delay
            setTimeout(() => {
                window.location.href = '/users/delete/' + userId;
            }, 3000); // 3 seconds delay
        }
    });
}

function showLiveAlert(message, customClass) {
    const alertPlaceholder = document.getElementById('liveAlertPlaceholder');
    // Clear any existing alerts
    alertPlaceholder.innerHTML = '';
    // Create the alert element with the custom class
    const wrapper = document.createElement('div');
    wrapper.innerHTML = `
        <div class="alert ${customClass} alert-dismissible fade show" role="alert">
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
}y
</script>
</body>

<?php 
else: 
    $this->redirect("/"); 
endif;   
?>