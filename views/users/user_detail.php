<?php
// Assuming you have started the session and included necessary files
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-card {
            border-radius: 10px;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .progress {
            height: 5px;
        }
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 8% !important;
            margin-left: 28%;
            width: 70%;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <!-- Profile Section -->
            <div class="col-md-4">
                <div class="profile-card text-center p-3">
                    <?php if (!empty($user['image'])): ?>
                        <img src="/<?= htmlspecialchars($user['image']) ?>" class="rounded-circle mb-3" alt="Profile Image" style="width: 120px; height: 120px;">
                    <?php else: ?>
                        <div class="rounded-circle mb-3" style="width: 120px; height: 120px; background-color: #e0e0e0; display: flex; justify-content: center; align-items: center;">No Image</div>
                    <?php endif; ?>
                    <h4><?= htmlspecialchars($user['name']) ?></h4>
                    <p class="text-muted"><?= htmlspecialchars($user['role']) ?></p>
                    <button class="btn btn-primary">Follow</button>
                    <button class="btn btn-outline-primary">Message</button>
                </div>
                <div class="profile-card mt-3 p-3">
                <p><strong>Website:</strong> https://bootdey.com</p>
                    <p><strong>Github:</strong> bootdey</p>
                    <p><strong>Twitter:</strong> @bootdey</p>
                    <p><strong>Instagram:</strong> bootdey</p>
                    <p><strong>Facebook:</strong> bootdey</p>
                </div>
            </div>
            <!-- Details Section -->
            <div class="col-md-8">
                <div class="profile-card p-3">
                    <h5>Full Name: <span class="text-muted"><?= htmlspecialchars($user['name']) ?></span></h5>
                    <h5>Email: <span class="text-muted"><?= htmlspecialchars($user['email']) ?></span></h5>
                    <h5>Phone: <span class="text-muted"><?= htmlspecialchars($user['phone']) ?></span></h5>
                    <h5>Address: <span class="text-muted"><?= htmlspecialchars($user['address']) ?></span></h5>
                </div>
                <!-- Project Status -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="profile-card p-3">
                            <h6 class="text-muted">Project Status</h6>
                            <p>Web Design</p>
                            <div class="progress mb-2"><div class="progress-bar" style="width: 70%;"></div></div>
                            <p>Website Markup</p>
                            <div class="progress mb-2"><div class="progress-bar" style="width: 50%;"></div></div>
                            <p>One Page</p>
                            <div class="progress mb-2"><div class="progress-bar" style="width: 80%;"></div></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="profile-card p-3">
                            <h6 class="text-muted">Project Status</h6>
                            <p>Mobile Template</p>
                            <div class="progress mb-2"><div class="progress-bar" style="width: 60%;"></div></div>
                            <p>Backend API</p>
                            <div class="progress mb-2"><div class="progress-bar" style="width: 90%;"></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>