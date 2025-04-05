<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    exit('You must be logged in to access this page.');
}

// Include DB connection
include('db.php');

// Initialize feedback message
$feedbackMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categoryName'])) {
    $categoryName = trim($_POST['categoryName']);
    
    if (!empty($categoryName)) {
        // Insert category into the database
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
        $stmt->bindParam(':name', $categoryName);

        if ($stmt->execute()) {
            $feedbackMessage = "<div class='alert alert-success'>Category '$categoryName' created successfully!</div>";
        } else {
            $feedbackMessage = "<div class='alert alert-danger'>Failed to create category.</div>";
        }
    } else {
        $feedbackMessage = "<div class='alert alert-danger'>Please enter a category name.</div>";
    }
}
?>

<!-- Modal for Create Category -->
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Create Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <!-- Category Form -->
                <form id="createCategoryForm" method="POST">
                    <div class="form-group">
                        <label for="categoryName">Category Name:</label>
                        <input type="text" class="form-control" id="categoryName" name="categoryName" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Create</button>
                </form>

                <!-- Feedback Section (empty initially) -->
                <div id="categoryFeedback" class="mt-3">
                    <?= $feedbackMessage ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap and jQuery Scripts for Modal -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- AJAX Script for Form Submission -->
<script>
$(document).ready(function() {
    $('#createCategoryForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        
        // Serialize the form data
        var formData = $(this).serialize();

        // Perform AJAX request to submit the form
        $.ajax({
            type: 'POST',
            url: 'category.php', // This is the same file to handle the form submission
            data: formData,
            success: function(response) {
                // Update the feedback section with the response from the server
                $('#categoryFeedback').html(response);
            },
            error: function() {
                $('#categoryFeedback').html('<div class="alert alert-danger">There was an error processing your request.</div>');
            }
        });
    });
});
</script>
