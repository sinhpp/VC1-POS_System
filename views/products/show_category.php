<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) : ?>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="/views/assets/js/product.js"></script>
  <script>
  tailwind.config = {
    corePlugins: {
      preflight: false
    }
  };
</script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7fafc; /* Light gray background */
            font-family: Arial, sans-serif; /* Font styling */
            color: #333; /* Darker text color */
        }
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 15px;
        }
        h2 {
            text-align: center;
            font-size: 0.5rem;
            margin-bottom: 20px;
            color: #2c3e50; /* Darker color for the header */
        }
        .text-sm{
          font-size: 12px;
        }
        .alert {
            font-size: 1.25rem; /* Larger font size */
            padding: 10px 15px; /* Increased padding */
            margin-bottom: 20px; /* Spacing below the alert */
        }
        .grid {
          position: relative;
          left: -10%;
            display: grid;
            gap: 20px;
        }
        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 25px;
            display: flex;
            align-items: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 24px;
            margin-right: 15px;
        }
        .font-semibold{
          font-size: 14px;
        }
       
        /* Icon color classes */
        .clothes { background-color: #e0f7fa; color: #00796b; }
        .bag { background-color: #fff9c4; color: #fbc02d; }
        .shoes { background-color: #fce4ec; color: #e91e63; }
        .toys { background-color: #e1bee7; color: #9c27b0; }
        .student { background-color: #d1c4e9; color: #673ab7; }
        .jewelry { background-color: #ffccbc; color: #ff5722; }
        .makeup { background-color: #f8bbd0; color: #d81b60; }
        .other { background-color: #cfd8dc; color: #607d8b; }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>

    <div class="container">
        <h2>Select a Category</h2>
        <div id="categoryFeedback" class="alert alert-success" style="display: none;"></div>
        <div class="grid">
            <!-- Card Template -->
            <a href="/products/create" class="card">
                <div class="icon clothes">👕</div>
                <div>
                    <h3 class="font-semibold text-lg">Clothes</h3>
                    <p class="text-sm">Fashion items, outfits, and more.</p>
                </div>
            </a>

            <a href="/products/create" class="card">
                <div class="icon bag">👜</div>
                <div>
                    <h3 class="font-semibold text-lg">Bag</h3>
                    <p class="text-sm">Handbags, backpacks, and travel bags.</p>
                </div>
            </a>

            <a href="/products/create" class="card">
                <div class="icon shoes">👟</div>
                <div>
                    <h3 class="font-semibold text-lg">Shoes</h3>
                    <p class="text-sm">Casual, formal, or sports footwear.</p>
                </div>
            </a>

            <a href="/products/create" class="card">
                <div class="icon toys">🧸</div>
                <div>
                    <h3 class="font-semibold text-lg">Toys</h3>
                    <p class="text-sm">Toys and games for all ages.</p>
                </div>
            </a>

            <a href="/products/create" class="card">
                <div class="icon student">🎒</div>
                <div>
                    <h3 class="font-semibold text-lg">Student Material</h3>
                    <p class="text-sm">Books, pens, and school items.</p>
                </div>
            </a>

            <a href="/products/create" class="card">
                <div class="icon jewelry">💍</div>
                <div>
                    <h3 class="font-semibold text-lg">Jewelry</h3>
                    <p class="text-sm">Rings, necklaces, and more.</p>
                </div>
            </a>

            <a href="/products/create" class="card">
                <div class="icon makeup">💄</div>
                <div>
                    <h3 class="font-semibold text-lg">Make Up</h3>
                    <p class="text-sm">Cosmetics and beauty products.</p>
                </div>
            </a>

            <a href="/products/create" class="card">
                <div class="icon other">📦</div>
                <div>
                    <h3 class="font-semibold text-lg">Other</h3>
                    <p class="text-sm">Miscellaneous category items.</p>
                </div>
            </a>
        </div>
    </div>


    <script>
        // Example function to show alert
        function showAlert(message) {
            const feedbackDiv = document.getElementById('categoryFeedback');
            feedbackDiv.innerHTML = message;
            feedbackDiv.style.display = 'block';
            setTimeout(() => {
                feedbackDiv.style.display = 'none'; // Hide after 3 seconds
            }, 3000);
        }

        // Example usage
        showAlert('Welcome! Please select a category.');
    </script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<?php else: $this->redirect("/"); endif; ?>
