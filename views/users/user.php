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
            padding: 0;
        }
        
        .header {
            
            color: white;
            padding: 15px 20px;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        [data-header-position="fixed"] .header {
            position: fixed;
            top: 0;
            width: 100%;
        }
        .header1 {
            
            background: teal;
            padding: 15px 20px;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        [data-header-position="fixed"] .header {
            position: fixed;
            top: 0;
            width: 100%;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .header1 h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: white !important;
        }
        
        .header-buttons {
            display: flex;
            gap: 10px;
            color: pink;
        }
        .header1-buttons .btn{
            color: white !important;
        }
        
        .header-buttons .btn {
            margin: 0;
            
            color: white !important;
            border: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .container-fluid{
            width: 90%;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            text-align: left;
            margin: 0;
        }
        
        .table img {
            max-width: 40px !important;
            height: 40px !important;
            border-radius: 50% !important;
            margin-right: 10px;
        }
        
        .table thead th {
            background-color: #f8f9fa;
            padding: 12px 20px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
            font-weight: 600;
            color: #333;
        }

        .table th, .table td {
            padding: 12px 20px;
            border-bottom: 1px solid #e0e0e0;
        }

        .table th {
            background-color: #f8f9fa !important;
            color: #333 !important;
            font-weight: 600;
            top: 0;
            z-index: 2;
        }

        .table tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
            transition: background-color 0.3s ease;
        }

        .user-info {
            display: flex;
            align-items: center;
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
            margin-right: 10px;
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
            padding: 4px 7px;
        }
        .action-buttons .material-icons {
            font-size: 16px !important; /* Smaller icon size */
            display: flex;
            justify-content: center;
            align-items: center;
            text-align:center;
            
   
        }
        
        /* .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-top: 1px solid #dee2e6;
        } */

        .pagination {
            margin: 0;
        }

        .pagination .page-item.active .page-link {
            background-color: #2196F3;
            border-color: #2196F3;
        }

        .pagination .page-link {
            color: #2196F3;
        }
        
        /* Responsive styles for tablet */
        @media (max-width: 992px) {
            .table-responsive {
                max-width: 95%;
                margin-top: 15%;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .header-buttons {
                width: 100%;
                justify-content: flex-end;
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
                font-size: 0.9rem;
            }
            
            .table th, .table td {
                padding: 8px 10px;
            }
            
            .header-buttons {
                flex-direction: column;
                width: 100%;
            }
            
            .header-buttons .btn {
                width: 100%;
                margin-bottom: 5px;
            }
/*             
            .pagination-container {
                flex-direction: column;
                gap: 10px;
            } */
            
            
            /* Stack action buttons on mobile */
            .action-buttons {
                flex-direction: column;
            }
            
            .action-buttons .btn {
                margin: 2px 0;
            }
        }
        
        /* Extra small devices */
        @media (max-width: 576px) {
            .table th:nth-child(3), .table td:nth-child(3) { /* Hide date column on very small screens */
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid table-responsive">
        <!-- Header -->
        <div class="header1">
            <h1>User Management</h1>
            <div class="header1-buttons">
                <button class="btn" id="exportBtn">
                    <i class="material-icons" style="font-size: 16px; vertical-align: text-bottom;">file_download</i>
                    Export to Excel
                </button>
                <a href="/users/create" class="btn">
                    <i class="material-icons" style="font-size: 16px; vertical-align: text-bottom;">add</i>
                    Add New User
                </a>
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
                    <th>Role</th>
                    <th>Status</th>
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
                        <?= isset($user['created_at']) ? date('m/d/Y', strtotime($user['created_at'])) : date('m/d/Y') ?>
                    </td>
                    <td>
                        <span class="badge <?= $user['role'] === 'admin' ? 'bg-success' : 'bg-info' ?> role-badge">
                            <?= htmlspecialchars($user['role']) ?>
                        </span>
                    </td>
                    <td>
                        <span class="status-indicator <?= $statusClass ?>"></span>
                        <?= $status ?>
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
                            <span>Michael Holz</span>
                        </div>
                    </td>
                    <td>04/10/2013</td>
                    <td><span class="badge bg-success">Admin</span></td>
                    <td><span class="status-indicator status-active"></span>Active</td>
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
                            <span>Paula Wilson</span>
                        </div>
                    </td>
                    <td>05/08/2014</td>
                    <td><span class="badge bg-info">Publisher</span></td>
                    <td><span class="status-indicator status-active"></span>Active</td>
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
                            <span>Antonio Moreno</span>
                        </div>
                    </td>
                    <td>11/05/2015</td>
                    <td><span class="badge bg-info">Publisher</span></td>
                    <td><span class="status-indicator status-suspended"></span>Suspended</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-warning btn-sm"><i class="material-icons">edit</i></button>
                            <button class="btn btn-danger btn-sm"><i class="material-icons">delete</i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>
                        <div class="user-info">
                            <div class="placeholder-image">M</div>
                            <span>Mary Saveley</span>
                        </div>
                    </td>
                    <td>06/09/2016</td>
                    <td><span class="badge bg-info">Reviewer</span></td>
                    <td><span class="status-indicator status-active"></span>Active</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-warning btn-sm"><i class="material-icons">edit</i></button>
                            <button class="btn btn-danger btn-sm"><i class="material-icons">delete</i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>
                        <div class="user-info">
                            <div class="placeholder-image">M</div>
                            <span>Martin Sommer</span>
                        </div>
                    </td>
                    <td>12/08/2017</td>
                    <td><span class="badge bg-info">Moderator</span></td>
                    <td><span class="status-indicator status-inactive"></span>Inactive</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-warning btn-sm">
                                <i class="material-icons" style="font-size: 14px;">edit</i>
                            </button>
                            <button class="btn btn-danger btn-sm">
                                <i class="material-icons" style="font-size: 14px;">delete</i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- Pagination -->
        <!-- <div class="pagination-container">
            <div>
                Showing <span>5</span> out of <span>25</span> entries
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">Previous</span>
                        </a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item active"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                    <li class="page-item"><a class="page-link" href="#">5</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div> -->
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
    }

    // Export to Excel functionality
    document.getElementById('exportBtn').addEventListener('click', function() {
        // You can implement the export functionality here
        // For now, just show an alert
        showLiveAlert('Exporting to Excel...', 'custom-success');
    });
    </script>
</body>

<?php 
else: 
    $this->redirect("/"); 
endif;   
?>