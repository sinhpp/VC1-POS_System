<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Low Stock Alert</title>
    <link rel="stylesheet" href="../assets/css/lowStockAlert.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <header class="header">
            <div class="title">
                <i class="fas fa-exclamation-triangle icon-alert"></i>
                <h1>Low Stock Alert</h1>
            </div>
            <div class="header-actions">
                <button class="help-btn">
                    <i class="far fa-question-circle"></i>
                    <span class="btn-text">How do I use this?</span>
                </button>
                <button class="save-btn">
                    <i class="far fa-save"></i>
                    <span class="btn-text">Save</span>
                </button>
            </div>
        </header>

        <!-- Notification Section -->
        <div class="notification">
            <p>Get notify when products are low in stock. <a href="#" class="read-more">Read More</a></p>
        </div>

        <!-- Tab Navigation -->
        <nav class="tabs">
            <button class="tab active">Low Stock Products</button>
            <button class="tab">Settings</button>
        </nav>

        <!-- Content Section -->
        <main class="content">
            <!-- Filter Section -->
            <section class="filter-section">
                <div class="filter-header">
                    <h2 class="list-title">Low Stock Product List</h2>
                    <button class="toggle-filters" aria-expanded="true" aria-controls="filters-container">
                        <i class="fas fa-sliders-h"></i>
                        <span>Filters</span>
                    </button>
                </div>
                
                <div id="filters-container" class="filters">
                    <div class="filter-group">
                        <label for="product-name">Product Name</label>
                        <input type="text" id="product-name" class="filter-input">
                    </div>
                    
                    <div class="filter-group">
                        <label for="quantity-min">Quantity</label>
                        <div class="quantity-range">
                            <input type="number" id="quantity-min" class="filter-input quantity-input" placeholder="Min">
                            <span class="range-separator">-</span>
                            <input type="number" id="quantity-max" class="filter-input quantity-input" value="2" placeholder="Max">
                        </div>
                        <span class="filter-hint">Filter by quantity range: from - to</span>
                    </div>
                    
                    <div class="filter-group">
                        <label for="subtract-stock">Subtract Stock</label>
                        <div class="select-wrapper">
                            <select id="subtract-stock" class="filter-select">
                                <option value="">All</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <label for="status">Status</label>
                        <div class="select-wrapper">
                            <select id="status" class="filter-select">
                                <option value="">All</option>
                                <option value="enabled">Enabled</option>
                                <option value="disabled">Disabled</option>
                            </select>
                        </div>
                    </div>
                    
                    <button class="filter-btn">
                        <i class="fas fa-filter"></i>
                        <span>Filter</span>
                    </button>
                </div>
            </section>

            <!-- Product List -->
            <section class="product-list">
                <!-- Table view (visible on larger screens) -->
                <div class="product-table">
                    <div class="table-header">
                        <div class="header-cell product-name">Product Name</div>
                        <div class="header-cell quantity">Quantity</div>
                        <div class="header-cell subtract-stock">Subtract Stock</div>
                        <div class="header-cell status">Status</div>
                        <div class="header-cell actions">Actions</div>
                    </div>
                    
                    <div class="table-body">
                        <?php if (empty($products)): ?>
                            <div class="empty-state">
                                <p>No low stock products found.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($products as $product): ?>
                                <div class="table-row">
                                    <div class="cell product-name">
                                        <img src="/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
                                        <span><?= htmlspecialchars($product['name']) ?></span>
                                    </div>
                                    <div class="cell quantity"><?= htmlspecialchars($product['stock']) ?></div>
                                    <div class="cell subtract-stock"><?= $product['subtract_stock'] ? 'Yes' : 'No' ?></div>
                                    <div class="cell status">
                                        <span class="status-pill <?= $product['status'] ? 'enabled' : 'disabled' ?>"><?= $product['status'] ? 'Enabled' : 'Disabled' ?></span>
                                    </div>
                                    <div class="cell actions">
                                        <button class="action-btn" data-product-id="<?= $product['id'] ?>">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Card view (visible on smaller screens) -->
                <div class="product-cards">
                    <?php if (empty($products)): ?>
                        <div class="empty-state">
                            <p>No low stock products found.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <div class="product-card">
                                <div class="card-header">
                                    <img src="/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
                                    <h3 class="product-title"><?= htmlspecialchars($product['name']) ?></h3>
                                </div>
                                <div class="card-body">
                                    <div class="card-row">
                                        <span class="card-label">Quantity:</span>
                                        <span class="card-value"><?= htmlspecialchars($product['stock']) ?></span>
                                    </div>
                                    <div class="card-row">
                                        <span class="card-label">Subtract Stock:</span>
                                        <span class="card-value"><?= $product['subtract_stock'] ? 'Yes' : 'No' ?></span>
                                    </div>
                                    <div class="card-row">
                                        <span class="card-label">Status:</span>
                                        <span class="status-pill <?= $product['status'] ? 'enabled' : 'disabled' ?>"><?= $product['status'] ? 'Enabled' : 'Disabled' ?></span>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="action-btn" data-product-id="<?= $product['id'] ?>">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
            
            <!-- Pagination -->
            <div class="pagination">
                <p>Showing 1 to 3 of 3 (1 Pages)</p>
            </div>
        </main>
    </div>

    <script src="../assets/js/lowStockAlert.js"></script>
</body>
</html>
