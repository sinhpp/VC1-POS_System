<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) : ?>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
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
            background-color: #f7fafc;
            font-family: Arial, sans-serif;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: auto;
            padding: 10px;
        }
        h2 {
            text-align: center;
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: #2c3e50;
        }
        .text-sm {
            font-size: 10px;
            line-height: 1.2;
        }
        .alert {
            font-size: 1rem;
            padding: 8px 12px;
            margin-bottom: 15px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin: 0 auto;
            width: 50%;
        }
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
            color: inherit;
            text-align: center;
            height: 100px;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        .icon {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 16px;
            margin-bottom: 8px;
        }
        .font-semibold {
            font-size: 12px;
            font-weight: 600;
            margin: 5px 0;
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

        @media (max-width: 768px) {
            .grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 576px) {
            .grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .card {
                height: 90px;
            }
        }
    </style>
</head>
<div class="container">
    <h2>Select a Category</h2>
    <div id="categoryFeedback" class="alert alert-success" style="display: none;"></div>
    <div class="grid">
        <!-- Card Template -->
        <a href="/products/create?category=clothes" class="card">
            <div class="icon clothes">👕</div>
            <div>
                <h3 class="font-semibold text-lg">Clothes</h3>
                <p class="text-sm">Fashion items, outfits, and more.</p>
            </div>
        </a>

        <a href="/products/create?category=bag" class="card">
            <div class="icon bag">👜</div>
            <div>
                <h3 class="font-semibold text-lg">Bag</h3>
                <p class="text-sm">Handbags, backpacks, and travel bags.</p>
            </div>
        </a>

        <a href="/products/create?category=shoes" class="card">
            <div class="icon shoes">👟</div>
            <div>
                <h3 class="font-semibold text-lg">Shoes</h3>
                <p class="text-sm">Casual, formal, or sports footwear.</p>
            </div>
        </a>

        <a href="/products/create?category=toys" class="card">
            <div class="icon toys">🧸</div>
            <div>
                <h3 class="font-semibold text-lg">Toys</h3>
                <p class="text-sm">Toys and games for all ages.</p>
            </div>
        </a>

        <a href="/products/create?category=student" class="card">
            <div class="icon student">🎒</div>
            <div>
                <h3 class="font-semibold text-lg">Student Material</h3>
                <p class="text-sm">Books, pens, and school items.</p>
            </div>
        </a>

        <a href="/products/create?category=jewelry" class="card">
            <div class="icon jewelry">💍</div>
            <div>
                <h3 class="font-semibold text-lg">Jewelry</h3>
                <p class="text-sm">Rings, necklaces, and more.</p>
            </div>
        </a>

        <a href="/products/create?category=makeup" class="card">
            <div class="icon makeup">💄</div>
            <div>
                <h3 class="font-semibold text-lg">Make Up</h3>
                <p class="text-sm">Cosmetics and beauty products.</p>
            </div>
        </a>

        <a href="/products/create?category=other" class="card">
            <div class="icon other">📦</div>
            <div>
                <h3 class="font-semibold text-lg">Other</h3>
                <p class="text-sm">Miscellaneous category items.</p>
            </div>
        </a>
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
</div>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<?php else: $this->redirect("/"); endif; ?>
