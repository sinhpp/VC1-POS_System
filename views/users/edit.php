
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) : ?>
  
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const img = document.getElementById("profile-pic");
                img.src = reader.result;
                document.getElementById("file-input").style.display = "none";
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</head>
<style>
       
    </style>
    <link rel="stylesheet" href="../../views/assets/css/edit_user.css">
  <script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const img = document.getElementById("profile-pic");
            img.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
</head>
<div class="content-body">
    <div class="container-fluid">
        <div class="container">
            <div class="profile-sidebar">
                <!-- Profile Image -->
                <img id="profile-pic" src="/<?= htmlspecialchars($user['image']) ?>" 
                     class="rounded-circle mb-3" alt="Profile Image" 
                     style="width: 120px; height: 120px; cursor: pointer;">
                     <h2>Edit Profile</h2>
                
                <form action="/users/update/<?= $user['id']; ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="<?= $user['id']; ?>">

                    <!-- File Input (INSIDE the form) -->
                    <input id="file-input" type="file" name="image" accept="image/*" onchange="previewImage(event)" style="display: none;">

                    <!-- Button to Trigger File Selection -->
                    <button type="button" onclick="document.getElementById('file-input').click();" class="btn btn-primary">Change Profile</button>

            </div>

            <div class="profile-form">
              
                    <div class="input-group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>" required>
                    </div>

                    <div class="input-group">
                        <label>Role</label>
                        <select name="role" class="role" required>
                            <option value="cashier" <?= ($user['role'] == 'cashier') ? 'selected' : ''; ?>>Cashier</option>
                            <option value="admin" <?= ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="stock_manager" <?= ($user['role'] == 'stock_manager') ? 'selected' : ''; ?>>Stock Manager</option>
                        </select>
                    </div>

                    
                    <div class="input-group">
                        <label>Email Address</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
                    </div>
                   

                    <!-- Phone Field -->
                <div class="input-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']); ?>" required>
                </div>

                <!-- Address Field -->
                <div class="input-group">
                    <label>Address</label>
                    <input type="text" name="address" value="<?= htmlspecialchars($user['address']); ?>" required>
                </div>
                
                <div class="submit">
                <a href="/users" class="btn-secondary1">Cancel</a>
                    <button type="submit" class="update-btn">Update Info</button>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<?php 
else: 
    $this->redirect("/"); 
endif;   
?>