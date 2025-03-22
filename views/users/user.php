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
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->

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
   
    <div class="content">
    <h2 class="text-center mb-4"></h2>
 
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
        <!-- Optional: Add a placeholder for success alert -->
        <!-- <div id="success-alert" style="display: none;"></div> -->

</body>
</html>
<style>
    /* Main Content Area */

    #liveAlertPlaceholder {
    position: fixed; /* Makes it stay on screen */
    top: 130px; /* Adjust this to move it up/down */
    right: 20px; /* Adjust this to move it left/right */
    z-index: 1050; /* Ensures it stays above other elements */
    width: 600px; /* Adjust width if needed */
}

.alert {
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Adds a slight shadow */
    border-radius: 8px; /* Rounded corners */
    padding: 10px 15px;
    font-size: 16px;
}

.content {
    font-family: "Poppins", sans-serif;
    /* width: 82%; */
    /* height: 100%; */
    margin-left:23.3%;
    margin-top:100px;
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