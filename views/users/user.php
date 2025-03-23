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
        /* General Styles */
        .header {
            margin-left: -28%;
            font-size: 1.5rem;
            font-weight: bold;
            color: #2c3e50;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .table-responsive {
            margin: 20px;
            max-width: 100%;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background: linear-gradient(145deg, #ffffff, #f9f9f9);
            margin-top: 25%;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table th, .table td {
            text-align: center;
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .table th {
            background-color: #007bff;
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            position: sticky;
            top: 0;
            z-index: 2;
        }

        .table tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
            transition: background-color 0.3s ease;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f8f9fa;
        }

        .table-hover tbody tr:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .shadow-sm {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .rounded {
            border-radius: 8px;
        }

        .role-badge {
            display: inline-block;
            width: 100px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            text-align: center;
            color: #fff;
            background: linear-gradient(145deg, #28a745, #218838);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .role-badge.bg-info {
            background: linear-gradient(145deg, #17a2b8, #138496);
        }

        .role-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.9rem;
            font-weight: 500;
            text-align: center;
            transition: all 0.3s ease;
        }

        .btn-warning {
            background: linear-gradient(145deg, #ffc107, #e0a800);
            border: none;
            color: #000;
        }

        .btn-warning:hover {
            background: linear-gradient(145deg, #e0a800, #d39e00);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-danger {
            background: linear-gradient(145deg, #dc3545, #c82333);
            border: none;
            color: #fff;
        }

        .btn-danger:hover {
            background: linear-gradient(145deg, #c82333, #bd2130);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .material-icons {
            vertical-align: middle;
            font-size: 1.2rem;
        }

        .alert {
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 16px;
        }

        /* Responsive Styles */
        @media (max-width: 1200px) {
            .header {
                margin-left: 0;
                text-align: center;
            }

            .table-responsive {
                margin-top: 15%;
            }

            .table th, .table td {
                padding: 10px;
            }

            .role-badge {
                width: 80px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 768px) {
            .table-responsive {
                margin-top: 20%;
            }

            .table th, .table td {
                padding: 8px;
                font-size: 0.9rem;
            }

            .role-badge {
                width: 70px;
                font-size: 0.75rem;
            }

            .btn {
                padding: 5px 10px;
                font-size: 0.8rem;
            }

            .material-icons {
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .table-responsive {
                margin-top: 25%;
            }

            .table th, .table td {
                padding: 6px;
                font-size: 0.8rem;
            }

            .role-badge {
                width: 60px;
                font-size: 0.7rem;
            }

            .btn {
                padding: 4px 8px;
                font-size: 0.75rem;
            }

            .material-icons {
                font-size: 0.9rem;
            }

            .header {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="table-responsive">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="/users/create" class="btn btn-success">+ Create User</a>
        </div>
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

    <!-- Live Alert Placeholder -->
    <div id="liveAlertPlaceholder"></div>
    <button type="button" class="btn btn-primary" id="liveAlertBtn" style="display: none;">Show live alert</button>

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
                    // Show live alert instead of SweetAlert2 success message
                    showLiveAlert('User has been deleted successfully.', 'success');
                    
                    // Redirect to delete URL after short delay
                    setTimeout(() => {
                        window.location.href = '/users/delete/' + userId;
                    }, 1000);
                }
            });
        }

        function showLiveAlert(message, type) {
            const alertPlaceholder = document.getElementById('liveAlertPlaceholder');
            const wrapper = document.createElement('div');
            wrapper.innerHTML = `
                <div class="alert alert-${type} alert-dismissible" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            alertPlaceholder.appendChild(wrapper);
        }
    </script>
</body>
</html>

<?php 
else: 
    $this->redirect("/"); 
endif;   
?>