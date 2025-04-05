<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: /");
    exit;
}

// Set the page title for the layout
$pageTitle = "Categories";

// Start output buffering to capture the content
ob_start();
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Categories</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
        <div class="button">
        
        <a href="/products/create" class="btn btn-success">+ Add Product</a>
    </div>
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Category Name</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($categories) && count($categories) > 0): ?>
                    <?php foreach ($categories as $index => $category): ?>
                        <tr>
                            <td><?= $index + 1; ?></td>
                            <td><?= htmlspecialchars($category['name']); ?></td>
                            <td><?= htmlspecialchars($category['created_at']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-warning btn-sm"><i class="material-icons">edit</i></button>
                                    <button class="btn btn-danger btn-sm"><i class="material-icons">delete</i></button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No categories found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// Get the content from the buffer
$content = ob_get_clean();

// Include the layout which will use $pageTitle and $content
require_once __DIR__ . '/../layout.php';
?>