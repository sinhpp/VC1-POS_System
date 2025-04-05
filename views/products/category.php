<?php
// Start the session if not started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure the user is logged in before showing the modal
if (!isset($_SESSION['user_id'])) {
    return;
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

        <!-- Feedback Section -->
        <div id="categoryFeedback" class="mt-3">
          <?php
          // Handling the form submission
          if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categoryName'])) {
              include('db.php'); // Include database connection
              $categoryName = trim($_POST['categoryName']);

              if (!empty($categoryName)) {
                  // Insert category into the database
                  $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
                  $stmt->bindParam(':name', $categoryName);
                  
                  if ($stmt->execute()) {
                      echo "<div class='alert alert-success'>Category '$categoryName' created successfully!</div>";
                  } else {
                      echo "<div class='alert alert-danger'>Failed to create category.</div>";
                  }
              } else {
                  echo "<div class='alert alert-danger'>Please enter a category name.</div>";
              }
          }
          ?>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Bootstrap and jQuery Scripts for Modal -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
