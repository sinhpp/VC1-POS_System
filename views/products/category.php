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
                <a href="javascript:void(0);" id="openCategory" onclick="openCategoryModal()">Create Category</a>
            </div>
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Category Name</th>
                    <th>Created At</th>
                    <th>Actions</th>
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
                                    <button class="btn btn-warning btn-sm" onclick="editCategory('<?= $category['id']; ?>', '<?= htmlspecialchars($category['name']); ?>')"><i class="material-icons">edit</i></button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteCategory('<?= $category['id']; ?>')"><i class="material-icons">delete</i></button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No categories found.</td>
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

<!-- Modal for Create Category -->
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Create Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createCategoryForm" method="POST" action="/products/create-category">
                    <div class="form-group mb-3">
                        <label for="categoryName">Category Name:</label>
                        <input type="text" class="form-control" id="categoryName" name="categoryName" required>
                    </div>
                    <input type="hidden" id="categoryId" name="categoryId" value="">
                    <button type="submit" class="btn btn-primary btn-block">Save</button>
                </form>
                <div id="categoryFeedback" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function openCategoryModal() {
    // Reset form when opening for creation
    document.getElementById('createCategoryForm').reset();
    document.getElementById('categoryId').value = '';
    document.getElementById('categoryModalLabel').textContent = 'Create Category';
    document.getElementById('categoryFeedback').innerHTML = '';
    
    // Show the modal using Bootstrap 5 syntax
    var myModal = new bootstrap.Modal(document.getElementById('categoryModal'));
    myModal.show();
}

function editCategory(id, name) {
    document.getElementById('categoryId').value = id;
    document.getElementById('categoryName').value = name;
    document.getElementById('categoryModalLabel').textContent = 'Edit Category';
    document.getElementById('categoryFeedback').innerHTML = '';
    
    // Show the modal using Bootstrap 5 syntax
    var myModal = new bootstrap.Modal(document.getElementById('categoryModal'));
    myModal.show();
}

function deleteCategory(id) {
    if (confirm('Are you sure you want to delete this category?')) {
        fetch('/products/delete-category', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ categoryId: id }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh the page to show updated list
                location.reload();
            } else {
                alert(data.message || 'Error deleting category');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the category');
        });
    }
}

// Handle form submission
document.getElementById('createCategoryForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent default form submission

    const formData = new FormData(this);
    const categoryId = document.getElementById('categoryId').value;
    
    // Determine if this is a create or update operation
    const url = categoryId ? '/products/update-category' : '/products/create-category';

    fetch(url, {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        const feedbackDiv = document.getElementById('categoryFeedback');
        
        if (data.success) {
            feedbackDiv.innerHTML = '<div class="alert alert-success">' + 
                (categoryId ? 'Category updated' : 'Category created') + ' successfully!</div>';
            
            // Hide the modal after a short delay
            setTimeout(() => {
                bootstrap.Modal.getInstance(document.getElementById('categoryModal')).hide();
                // Refresh the page to show the updated list
                location.reload();
            }, 1500);
        } else {
            feedbackDiv.innerHTML = '<div class="alert alert-danger">' + 
                (data.message || 'An error occurred') + '</div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('categoryFeedback').innerHTML = 
            '<div class="alert alert-danger">An unexpected error occurred</div>';
    });
});
</script>
