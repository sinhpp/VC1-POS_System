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
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Responsive meta tag -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>

        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .table-responsive {
            position: relative;
            left: 3%;

            max-width: 80%;
            overflow: hidden;
            border-radius: 12px;


            margin-top: 10%;
            padding: 0;
            transition: all 0.3s ease; /* Smooth transitions when resizing */
        }
        
        [data-header-position="fixed"] .header {
            position: fixed;
            top: 0;
            width: 100%;
        }
        
        .header1 {
            background: linear-gradient(135deg, #1FA1A1, #17807f);
            padding: 20px 25px;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .header1 h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
            color: white !important;
            letter-spacing: 0.5px;
        }
        
        .header1-buttons {
            display: flex;
            gap: 12px;
        }
        
        .header1-buttons .btn {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .header1-buttons .btn:hover {
            background-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }
        
        .container-fluid {
            width: 95%;
            max-width: 1400px;
           
        }

        .table {
            position: relative;
          
            left:20%;
            width: 80%;
            border-collapse: separate;
            border-spacing: 0;
            text-align: left;
            margin: 0;
        }
        
        .table img {
            width: 40px !important;
            height: 40px !important;
            border-radius: 50% !important;
            object-fit: cover;
            border: 2px solid #f0f0f0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .table thead th {
            background-color:rgb(48, 48, 167);
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
            font-weight: 600;
            color: white;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table th, .table td {
            padding: 15px 20px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(31, 161, 161, 0.05);
            transform: translateY(-1px);
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .placeholder-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #6c757d;
            font-weight: 500;
            margin-right: 10px;
            font-size: 0.75rem;
        }
         
        .alert-small {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4CAF50 !important;
            border-radius: 8px;
            max-height: 8% !important;
            z-index: 1000;
            max-width: 300px;
            width: 100%;
            opacity: 0.95;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            animation: slideIn 0.3s ease-out forwards;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 0.95;
            }
        }
        
        .status-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .status-active {
            background-color: #4CAF50;
        }

        .status-suspended {
            background-color: #F44336;
        }

        .status-inactive {
            background-color: #FF9800;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .action-buttons .btn {
            margin: 0;
            padding: 6px 10px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        
        .action-buttons .btn:hover {
            transform: translateY(-2px);
        }
        
        .action-buttons .btn-warning {
            background-color: #ffb74d;
            border-color: #ffb74d;
        }
        
        .action-buttons .btn-danger {
            background-color: #ef5350;
            border-color: #ef5350;
        }
        
        .action-buttons .material-icons {
            font-size: 16px !important;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        
        /* Role badges styling */
        .badge {
            padding: 6px 12px;
            font-weight: 500;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-radius: 6px;
            text-transform: uppercase;
        }
        
        .role-admin {
            background-color: #1FA1A1;
            color: white;
        }
        
        .role-cashier {
            background-color: #3498db;
            color: white;
        }
        
        .role-stock-manager {
            background-color: #f39c12;
            color: white;
        }
        
        .role-publisher {
            background-color: #9C27B0;
            color: white;
        }
        
        .role-default {
            background-color: #78909c;
            color: white;
        }

        /* Custom SweetAlert styling */
        .swal2-popup {
            font-family: 'Poppins', sans-serif;
            border-radius: 12px;
        }
        
        .swal2-title {
            font-weight: 600;
        }
        
        .swal2-confirm {
            border-radius: 8px !important;
        }
        
        .swal2-cancel {
            border-radius: 8px !important;
        }
        .btn-success{
            margin-top: 3%;
            margin-left: 87%;
        }
        
       /* Large devices (desktops) */
/* Responsive styles that follow your existing design approach */
@media (max-width: 1200px) {
    .table-responsive {
        left: 5%;
        max-width: 90%;
    }
    
    .table {
        left: 15%;
        width: 85%;
    }
    
    .btn-success {
        margin-left: 85%;
        margin-top: 3%;
    }
}

@media (max-width: 992px) {
    .table-responsive {
        left: 2%;
        max-width: 95%;
    }
    
    .table {
        left: 10%;
        width: 90%;
    }
    
    .btn-success {
        margin-left: 80%;
        margin-top: 3%;
    }
    
    .header1-buttons {
        gap: 8px;
    }
    
    .header1-buttons .btn {
        padding: 6px 12px;
    }
}

@media (max-width: 768px) {
    .table-responsive {
        left: 0;
        max-width: 100%;
    }
    
    .table {
        left: 5%;
        width: 95%;
    }
    
    .container-fluid {
        width: 100%;
    }
    
    .btn-success {
        margin-left: 70%;
        margin-top: 3%;
    }
    
    /* Hide date created column on tablets and below */
    .table th:nth-child(5), 
    .table td:nth-child(5) {
        display: none;
    }
}

@media (max-width: 576px) {
    .table {
        left: 0;
        width: 100%;
    }
    
    .btn-success {
        margin-left: 60%;
        margin-top: 10%;
        margin-left: 70%;
    }
    
    .table th, 
    .table td {
        padding: 10px 8px;
    }
    
    .badge {
        padding: 4px 8px;
        font-size: 0.7rem;
    }
    
    .action-buttons .btn {
        padding: 4px 6px;
    }
    
    .action-buttons .material-icons {
        font-size: 14px !important;
    }
}

@media (max-width: 480px) {
    .btn-success {
        margin-left: 50%;
        margin-top: 5%;
    }
    
    /* Hide image column on very small screens */
    .table th:nth-child(2), 
    .table td:nth-child(2) {
        display: none;
    }
    
    .table th, 
    .table td {
        padding: 8px 5px;
        font-size: 0.85rem;
    }
}

@media (max-width: 400px) {
    .btn-success {
        margin-left: 30%;
        margin-top: 5%;
    }
    
    /* Hide ID column on extremely small screens */
    .table th:nth-child(1), 
    .table td:nth-child(1) {
        display: none;
    }
}
    </style>
     <link rel="stylesheet" href="../../views/assets/css/category.css">
</head>
<body>
    <div class="container-fluid table-responsive">
        <!-- Header -->
          
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="/users/create" class="btn btn-success">+ Create User</a>
        </div>
    </div>

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Date Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $counter = 1;
                foreach ($users as $user): 
                    // Determine status (this is an example - adjust based on your actual data)
                    $status = 'Active'; // Default status
                    $statusClass = 'status-active';
                    
                    // You would need to add a status field to your users table
                    // This is just a placeholder logic
                    if (isset($user['status'])) {
                        $status = $user['status'];
                        if ($status == 'Suspended') {
                            $statusClass = 'status-suspended';
                        } else if ($status == 'Inactive') {
                            $statusClass = 'status-inactive';
                        }
                    }
                ?>
                <tr>
                    <td><?= $counter++ ?></td>
                    <td onclick="window.location.href='/users/view/<?= $user['id'] ?>'" style="cursor: pointer;">
                        <?php if (!empty($user['image'])): ?>
                            <img src="/<?= htmlspecialchars($user['image']) ?>" alt="Profile Image" class="profile-image">
                        <?php else: ?>
                            <div class="placeholder-image"><?= substr(htmlspecialchars($user['name']), 0, 1) ?></div>
                        <?php endif; ?>
                    </td>
                    <td onclick="window.location.href='/users/view/<?= $user['id'] ?>'" style="cursor: pointer;">
                        <?= htmlspecialchars($user['name']) ?>
                    </td>
                    <td onclick="window.location.href='/users/view/<?= $user['id'] ?>'" style="cursor: pointer;">
                        <?php 
                            // Ensure that the role exists and print the role value for debugging
                            $role = $user['role'] ?? 'user'; //
                            $formattedRole = ucwords(str_replace('_', ' ', $role)); 

                            // Define role classes for each role
                            $roleClasses = [
                                'admin' => 'role-admin',
                                'cashier' => 'role-cashier',
                                'stock_manager' => 'role-stock-manager',
                                'publisher' => 'role-publisher',
                            ];

                            // Use the role class if exists, otherwise use a default class
                            $roleClass = isset($roleClasses[$role]) ? $roleClasses[$role] : 'role-default'; 
                        ?>
                        <span class="badge <?= $roleClass ?>">
                            <?= htmlspecialchars($formattedRole) ?> <!-- Display formatted role -->
                        </span>
                    </td>

                    <td>
                        <?= isset($user['created_at']) ? date('m/d/Y', strtotime($user['created_at'])) : date('m/d/Y') ?>
                    </td>
                    
                    <td>
                        <div class="action-buttons">
                            <a href="/users/edit/<?= $user['id'] ?>" class="btn btn-warning btn-sm">
                                <i class="material-icons">edit</i>
                            </a>
                            <a href="#" class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $user['id'] ?>)">
                                <i class="material-icons">delete</i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($users)): ?>
                <!-- Sample data for demonstration - only shown if no users exist -->
                <tr>
                    <td>1</td>
                    <td>
                        <div class="user-info">
                            <div class="placeholder-image">M</div>
                        </div>
                    </td>
                    <td>Michael Holz</td>
                    <td><span class="badge role-admin">Admin</span></td>
                    <td>04/10/2023</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-warning btn-sm"><i class="material-icons">edit</i></button>
                            <button class="btn btn-danger btn-sm"><i class="material-icons">delete</i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>
                        <div class="user-info">
                            <div class="placeholder-image">P</div>
                        </div>
                    </td>
                    <td>Paula Wilson</td>
                    <td><span class="badge role-publisher">Publisher</span></td>
                    <td>05/08/2023</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-warning btn-sm"><i class="material-icons">edit</i></button>
                            <button class="btn btn-danger btn-sm"><i class="material-icons">delete</i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>
                        <div class="user-info">
                            <div class="placeholder-image">A</div>
                        </div>
                    </td>
                    <td>Antonio Moreno</td>
                    <td><span class="badge role-cashier">Cashier</span></td>
                    <td>11/05/2023</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-warning btn-sm"><i class="material-icons">edit</i></button>
                            <button class="btn btn-danger btn-sm"><i class="material-icons">delete</i></button>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
 

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
            cancelButtonText: 'Cancel',
            heightAuto: false,
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Use custom class for successful deletion alert
                showLiveAlert('User deleted successfully!', 'alert-success');

                // Redirect to delete URL after a delay
                setTimeout(() => {
                    window.location.href = '/users/delete/' + userId;
                }, 1500); // 1.5 seconds delay
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
            wrapper.querySelector('.alert').classList.remove('show');
            setTimeout(() => {
                wrapper.remove();
            }, 300);
        }, 3000);
    }

    // Export to Excel functionality
    document.getElementById('exportBtn').addEventListener('click', function() {
        // You can implement the export functionality here
        // For now, just show an alert
        showLiveAlert('Exporting users to Excel...', 'alert-success');
        
        // Add your Excel export logic here
        // Example: window.location.href = '/users/export';
    });
    </script>
</body>

<?php 
else: 
    $this->redirect("/"); 
endif;   
?>