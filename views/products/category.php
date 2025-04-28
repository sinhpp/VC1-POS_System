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
<script>
function deleteCategory(id, row) {
    if (confirm("Are you sure you want to delete this category?")) {
        fetch(`/categories/delete/${id}`, {
            method: 'DELETE',
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the row from the table
                row.remove();
                alert("Category deleted successfully.");
                updateRowNumbers();
            } else {
                alert("Failed to delete category.");
            }
        })
        
    }
}

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
                (categoryId ? 'Category updated successfully!' : 'Category created successfully!') + '</div>';

            // Show success alert
            alert(categoryId ? 'Category updated successfully!' : 'Category created successfully!');

            if (!categoryId) { // If it's a new category
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td></td>
                    <td><span class="fw-bold">${data.category.name}</span></td>
                    <td>${new Date().toLocaleString()}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-edit" onclick="editCategory('${data.category.id}', '${data.category.name}')">
                                <i class="material-icons">edit</i>
                            </button>
                            <button class="btn btn-delete" onclick="deleteCategory('${data.category.id}', this.closest('tr'))">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                        </div>
                    </td>
                `;
                document.querySelector('tbody').appendChild(newRow);
                updateRowNumbers();
            } else {
                // Update the existing row if it's an edit
                const row = document.querySelector(`tr[data-id="${categoryId}"]`);
                row.querySelector('td:nth-child(2) span').textContent = data.category.name;
                updateRowNumbers();
            }

            bootstrap.Modal.getInstance(document.getElementById('categoryModal')).hide();
        } else {
            feedbackDiv.innerHTML = '<div class="alert alert-danger">' + 
                (data.message || 'An error occurred') + '</div>';
        }
    });
});

// Function to update row numbers
function updateRowNumbers() {
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach((row, index) => {
        row.querySelector('td:first-child').textContent = index + 1;
    });
}
</script>

<link rel="stylesheet" href="../../views/assets/css/category.css">
<div class="container mt-4">
    <div class="category-container">
        <div class="category-header">
            <h4 class="mb-0">Categories Management</h4>
        </div>
        
        <div class="d-flex justify-content-end">
            <a href="javascript:void(0);" class="create-btn" id="openCategory" onclick="openCategoryModal()">
                <i class="material-icons me-2"> Create Category
            </a>
        </div>
        
        <div class="table-responsive category-table">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="45%">Category Name</th>
                        <th width="30%">Created At</th>
                        <th width="20%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($categories) && count($categories) > 0): ?>
                        <?php foreach ($categories as $index => $category): ?>
                            <tr data-id="<?= $category['id']; ?>">
                                <td><?= $index + 1; ?></td>
                                <td>
                                    <span class="fw-bold"><?= htmlspecialchars($category['name']); ?></span>
                                </td>
                                <td><?= htmlspecialchars($category['created_at']); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-edit" onclick="editCategory('<?= $category['id']; ?>', '<?= htmlspecialchars($category['name']); ?>')">
                                            <i class="material-icons">edit</i>
                                        </button>
                                        <button class="btn btn-delete" onclick="deleteCategory('<?= $category['id']; ?>', this.closest('tr'))">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="empty-state">
                                <div class="py-4">
                                    <i class="material-icons" style="font-size: 48px; color: #dee2e6;">category</i>
                                    <p class="mt-3 mb-0">No categories found. Create your first category!</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
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
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="categoryModalLabel">Create Category</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createCategoryForm" method="POST" action="/products/create-category">
                    <div class="form-group mb-3">
                        <label for="categoryName" class="form-label">Category Name:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="material-icons">category</i></span>
                            <input type="text" class="form-control" id="categoryName" name="categoryName" required placeholder="Enter category name">
                        </div>
                    </div>
                    <input type="hidden" id="categoryId" name="categoryId" value="">
                    <button type="submit" class="btn btn-primary w-100 py-2">
                         Save Category
                    </button>
                </form>
                <div id="categoryFeedback" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>