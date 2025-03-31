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
        <div class="header">
            <div class="title">
                <i class="fas fa-exclamation-triangle icon-alert"></i>
                <h1>Low Stock Alert</h1>
            </div>
            <div class="header-actions">
                <button class="help-btn">
                    <i class="far fa-question-circle"></i>
                    How do I use this?
                </button>
                <button class="save-btn">
                    <i class="far fa-save"></i>
                    Save
                </button>
            </div>
        </div>

        <!-- Notification Section -->
        <div class="notification">
            <p>Get notify when products are low in stock. <a href="#" class="read-more">Read More</a></p>
        </div>

        <!-- Tab Navigation -->
        <div class="tabs">
            <div class="tab active">Low Stock Products</div>
            <div class="tab">Settings</div>
        </div>

        <!-- Content Section -->
        <div class="content">
            <!-- Filter Section -->
            <div class="filter-section">
                <h2 class="list-title">Low Stock Product List</h2>
                <div class="filters">
                    <div class="filter-group">
                        <label for="product-name">Product Name</label>
                        <input type="text" id="product-name" class="filter-input">
                    </div>
                    
                    <div class="filter-group">
                        <label for="quantity-min">Quantity</label>
                        <div class="quantity-range">
                            <input type="number" id="quantity-min" class="filter-input quantity-input">
                            <span class="range-separator">-</span>
                            <input type="number" id="quantity-max" class="filter-input quantity-input" value="2">
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
                        Filter
                    </button>
                </div>
            </div>

            <!-- Product Table -->
            <div class="product-table">
                <div class="table-header">
                    <div class="header-cell product-name">Product Name</div>
                    <div class="header-cell quantity">Quantity</div>
                    <div class="header-cell subtract-stock">Subtract Stock</div>
                    <div class="header-cell status">Status</div>
                </div>
                
                <div class="table-body">
                    <!-- Product Row 1 -->
                    <div class="table-row">
                        <div class="cell product-name">
                            <img src="https://placehold.co/60x80" alt="Atelier V Neck Blouse" class="product-image">
                            <span>Atelier V Neck Blouse</span>
                        </div>
                        <div class="cell quantity">0</div>
                        <div class="cell subtract-stock">Yes</div>
                        <div class="cell status">
                            <span class="status-pill enabled">Enabled</span>
                        </div>
                        <div class="cell actions">
                            <button class="action-btn">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Product Row 2 -->
                    <div class="table-row">
                        <div class="cell product-name">
                            <img src="https://placehold.co/60x80" alt="Atelier Ruffle Dresses" class="product-image">
                            <span>Atelier Ruffle Dresses</span>
                        </div>
                        <div class="cell quantity">1</div>
                        <div class="cell subtract-stock">No</div>
                        <div class="cell status">
                            <span class="status-pill enabled">Enabled</span>
                        </div>
                        <div class="cell actions">
                            <button class="action-btn">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Product Row 3 -->
                    <div class="table-row">
                        <div class="cell product-name">
                            <img src="https://placehold.co/60x80" alt="Atelier Loose Blouse" class="product-image">
                            <span>Atelier Loose Blouse</span>
                        </div>
                        <div class="cell quantity">2</div>
                        <div class="cell subtract-stock">Yes</div>
                        <div class="cell status">
                            <span class="status-pill enabled">Enabled</span>
                        </div>
                        <div class="cell actions">
                            <button class="action-btn">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="pagination">
                <p>Showing 1 to 3 of 3 (1 Pages)</p>
            </div>
        </div>
    </div>

    <script src="../assets/js/lowStockAlert.js"></script>
</body>
</html>
