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
    .category-container {
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        padding: 25px;
        margin-bottom: 30px;
        margin-top:5%;
        margin-left:10%;
    }
    
    .category-header {
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        color: white;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 25px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .create-btn {
        background: linear-gradient(to right, #11998e, #38ef7d);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 50px;
        font-weight: bold;
        transition: all 0.3s ease;
        display: inline-block;
        margin-bottom: 20px;
        text-decoration: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .create-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 8px rgba(0,0,0,0.15);
        color: white;
    }
    
    .category-table {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    
    .category-table thead {
        background: linear-gradient(to right, #5e35b1, #3949ab);
        color: white;
    }
    
    .category-table th, .category-table td {
        vertical-align: middle;
    }
    
    .action-buttons .btn {
        margin: 2px;
        border-radius: 50px;
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }
    
    .action-buttons .btn:hover {
        transform: scale(1.1);
    }
    
    .btn-edit {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #212529;
    }
    
    .btn-delete {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    
    .empty-state {
        padding: 30px;
        text-align: center;
        color: #6c757d;
        font-style: italic;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .category-container {
            padding: 15px;
        }
        
        .category-header h1 {
            font-size: 1.8rem;
        }
        
        .action-buttons {
            display: flex;
            justify-content: center;
        }
        
        .create-btn {
            width: 100%;
            text-align: center;
        }
    }
    
    @media (max-width: 576px) {
        .category-header h1 {
            font-size: 1.5rem;
        }
        
        .category-table th, .category-table td {
            padding: 0.5rem;
            font-size: 0.9rem;
        }
        
        .action-buttons .btn {
            width: 32px;
            height: 32px;
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
