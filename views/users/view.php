<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user data is available
if (!isset($user) || empty($user)) {
    die("User not found.");
}

// Debugging (remove this after confirming data is correct)
// var_dump($user); exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #6c63ff;
            --secondary-color: #f8f9fa;
            --accent-color: #ff4757;
            --text-color: #333;
            --light-text: #6c757d;
            --border-radius: 12px;
            --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            color: var(--text-color);
        }

        .profile-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 15px;
        }

        .profile-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }

        .profile-header {
            background: linear-gradient(135deg, var(--primary-color), #8a85ff);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            margin-bottom: 1rem;
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .profile-role {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.25rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .profile-body {
            padding: 2rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
            display: flex;
            align-items: center;
        }

        .btn-update {
            background-color: var(--accent-color);
            border: none;
            padding: 0.75rem 2rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-update:hover {
            background-color: #ff6b81;
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-header">
                <h2 class="profile-name"><?= htmlspecialchars($user['name']); ?></h2>
                <span class="profile-role"><?= ucfirst(htmlspecialchars($user['role'])); ?></span>
            </div>
            
            <div class="profile-body">
                <h3 class="section-title"><i class="fas fa-user-edit"></i> User Details</h3>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
                <p><strong>Role:</strong> <?= htmlspecialchars($user['role']); ?></p>
                <p><strong>Created At:</strong> <?= htmlspecialchars($user['created_at']); ?></p>

                <a href="/users/edit/<?= $user['id']; ?>" class="btn btn-update">Edit User</a>
            </div>
        </div>
    </div>
</body>
</html>
