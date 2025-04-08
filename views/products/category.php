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

<!-- Custom CSS for enhanced UI -->
<style>
/* Container styling */

.category-container{
    margin-top: 10%;
    margin-left: 5%;
    margin-right: 5%;
    margin-bottom: 5%;
}
.container {
    max-width: 70%;
    margin: auto;
    padding: 20px;
  
    border-radius: 10px;
  
    position: relative;
    top: 20%;
    left: 10%;
}


/* Header styling */
.category-header h4 {
    color: #343a40;
    font-weight: bold;
    
    padding-bottom: 10px;
}

/* Button styling */
.create-btn {
    background-color: #007bff;
    color: white;
    padding: 10px 15px;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.create-btn:hover {
    background-color: #0056b3;
}

/* Table styling */
.category-table {
  
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th, .table td {
   
    padding: 15px;
    text-align: left;
}

.table th {
   
    color: white;
}

.table tr {
    transition: background-color 0.3s ease;
}

.table tr:hover {
    background-color: #e9ecef;
}

/* Action buttons */
.action-buttons {
    display: flex;
    gap: 10px;
}

.btn {

    border: none;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: #5a6268;
}

.btn-edit {
    background-color: #28a745;
}

.btn-edit:hover {
    background-color: #218838;
}

.btn-delete {
    background-color: #dc3545;
}

.btn-delete:hover {
    background-color: #c82333;
}

/* Empty state styling */
.empty-state {
    text-align: center;
}

.empty-state i {
    font-size: 48px;
    color: #dee2e6;
}

.empty-state p {
    margin-top: 10px;
    color: #6c757d;
}

/* Modal styling */
.modal-header {
    background-color: #007bff;
    color: white;
}

.modal-body {
    padding: 20px;
}

.form-label {
    font-weight: bold;
}

.input-group-text {
    background-color: #f8f9fa;
}

/* Responsive styling */
@media (max-width: 1024px) {
    .create-btn {
        width: 100%;
    }

    .action-buttons {
        flex-direction: row;
        justify-content: space-between;
    }
}

@media (max-width: 768px) {
    .table th, .table td {
        padding: 10px;
        font-size: 14px;
    }

    .btn {
        padding: 5px 8px;
    }

    .empty-state i {
        font-size: 36px;
    }
}

@media (max-width: 576px) {
    .container {
        padding: 15px;
    }

    .category-header h4 {
        font-size: 18px;
    }

    .create-btn {
        font-size: 14px;
    }

    .table th, .table td {
        font-size: 12px;
    }

    .btn {
        width: 100%;
        margin-bottom: 5px;
    }

    .action-buttons {
        flex-direction: column;
        gap: 5px;
    }
}
</style>

<div class="container mt-4">
    <div class="category-container">
        <div class="category-header">
            <h4 class="mb-0">Categories Management</h4>
        </div>
        
        <div class="d-flex justify-content-end">
            <a href="javascript:void(0);" class="create-btn" id="openCategory" onclick="openCategoryModal()">
                <i class="material-icons me-2" style="vertical-align: middle;"> Create Category
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
                            <tr>
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
                                        <button class="btn btn-delete" onclick="deleteCategory('<?= $category['id']; ?>')">
                                            <i class="material-icons">delete</i>
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
                        <i class="material-icons me-2" style="vertical-align: middle;">save</i> Save Category
                    </button>
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

});

</script>
