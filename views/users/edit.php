
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .header{
            margin-left:-6%;
        }
        .container {
            margin-left:20%;
            display: flex;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(126, 54, 54, 0.1);
            max-width: 100%;
            width: 80%;
        }
        .profile-sidebar {
            width: 30%;
            
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
            object-fit: cover;
        }
        .upload-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }
        .profile-form {
            width: 70%;
            padding: 20px;
        }
        .input-group {
            margin-bottom: 15px;
        }
        .role{
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .input-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .input-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .update-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
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
<div class="content-body">
    <!-- row -->
	<div class="container-fluid">
        <div class="container">
            <div class="profile-sidebar">
                <img id="profile-pic" src="profile.jpg" alt="Profile Picture" class="profile-pic">
                <h2><?= htmlspecialchars($user['name']); ?></h2>
                <input id="file-input" type="file" accept="image/*" onchange="previewImage(event)">
            </div>
        <div class="profile-form">
            <h2>Edit Profile</h2>
            <form action="/users/update/<?= $user['id']; ?>" method="POST">
        <input type="hidden" name="user_id" value="<?= $user['id']; ?>">

        <div class="input-group">
            <label>Full Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>" required>
        </div>
        
        <div class="input-group">
            <label>Email Address</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
        </div>
        
        <div class="input-group">
            <label>Role</label>
            <select name="role" class="role" required>
                <option value="cashier" <?= ($user['role'] == 'cashier') ? 'selected' : ''; ?>>Cashier</option>
                <option value="admin" <?= ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>

        <button type="submit" class="update-btn">Update Info</button>
    </form>
</div>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<?php 
else: 
    $this->redirect("/"); 
endif;   
?>