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
     <link rel="stylesheet" href="../../views/assets/css/user.css">
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
                    <th class="column-id">#</th>
                    <th class="column-image">Image</th>
                    <th class="column-name">Name</th>
                    <th class="column-role">Role</th>
                    <th class="column-date">Date Created</th>
                    <th class="column-actions">Actions</th>
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
                    <td class="column-id"><?= $counter++ ?></td>
                    <td class="column-image" onclick="window.location.href='/users/view/<?= $user['id'] ?>'" style="cursor: pointer;">
                        <?php if (!empty($user['image'])): ?>
                            <img src="/<?= htmlspecialchars($user['image']) ?>" alt="Profile Image" class="profile-image">
                        <?php else: ?>
                            <div class="placeholder-image"><?= substr(htmlspecialchars($user['name']), 0, 1) ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="column-name" onclick="window.location.href='/users/view/<?= $user['id'] ?>'" style="cursor: pointer;">
                        <?= htmlspecialchars($user['name']) ?>
                    </td>
                    <td class="column-role" onclick="window.location.href='/users/view/<?= $user['id'] ?>'" style="cursor: pointer;">
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

                    <td class="column-date">
                        <?= isset($user['created_at']) ? date('m/d/Y', strtotime($user['created_at'])) : date('m/d/Y') ?>
                    </td>
                    
                    <td class="column-actions">
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
                    <td class="column-id">1</td>
                    <td class="column-image">
                        <div class="user-info">
                            <div class="placeholder-image">M</div>
                        </div>
                    </td>
                    <td class="column-name">Michael Holz</td>
                    <td class="column-role"><span class="badge role-admin">Admin</span></td>
                    <td class="column-date">04/10/2023</td>
                    <td class="column-actions">
                        <div class="action-buttons">
                            <button class="btn btn-warning btn-sm"><i class="material-icons">edit</i></button>
                            <button class="btn btn-danger btn-sm"><i class="material-icons">delete</i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="column-id">2</td>
                    <td class="column-image">
                        <div class="user-info">
                            <div class="placeholder-image">P</div>
                        </div>
                    </td>
                    <td class="column-name">Paula Wilson</td>
                    <td class="column-role"><span class="badge role-publisher">Publisher</span></td>
                    <td class="column-date">05/08/2023</td>
                    <td class="column-actions">
                        <div class="action-buttons">
                            <button class="btn btn-warning btn-sm"><i class="material-icons">edit</i></button>
                            <button class="btn btn-danger btn-sm"><i class="material-icons">delete</i></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="column-id">3</td>
                    <td class="column-image">
                        <div class="user-info">
                            <div class="placeholder-image">A</div>
                        </div>
                    </td>
                    <td class="column-name">Antonio Moreno</td>
                    <td class="column-role"><span class="badge role-cashier">Cashier</span></td>
                    <td class="column-date">11/05/2023</td>
                    <td class="column-actions">
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
