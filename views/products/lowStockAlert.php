      <link rel="stylesheet" href="../assets/css/lowStockAlert.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
      <!-- Add Bootstrap CSS for responsive grid -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
      <style>
          body {
              display: flex;
              margin: 0;
              padding: 0;
              overflow-x: hidden;
          }
        
          .main-content {
              flex: 1;
              margin-left: 250px; /* Width of sidebar */
              transition: margin-left 0.3s;
              padding: 20px;
              width: calc(100% - 250px);
          }
        
          @media (max-width: 768px) {
              .main-content {
                  margin-left: 0;
                  width: 100%;
              }
            
              .sidebar-active .main-content {
                  margin-left: 220px;
                  width: calc(100% - 220px);
              }
          }
        
          .toggle-sidebar {
              display: none;
              position: fixed;
              top: 10px;
              left: 10px;
              z-index: 1000;
              background: #4E36E2;
              color: white;
              border: none;
              border-radius: 5px;
              padding: 8px 12px;
          }
        
          @media (max-width: 768px) {
              .toggle-sidebar {
                  display: block;
              }
          }
      </style>
  </head>
  <body>
      <!-- Include the sidebar -->
      <!-- Toggle sidebar button for mobile -->
      <!-- <button class="toggle-sidebar" id="toggleSidebar">
          <i class="fas fa-bars"></i>
      </button> -->
    
      <div class="main-content">
          <div class="container-fluid">
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
                                              <?php 
                                              // Explicitly set status based on stock
                                              $statusClass = ($product['stock'] <= 0) ? 'disabled' : 'enabled';
                                              $statusText = ($product['stock'] <= 0) ? 'Disabled' : 'Enabled';
                                              ?>
                                              <span class="status-pill <?= $statusClass ?>"><?= $statusText ?></span>
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
                                              <?php 
                                              $statusClass = ($product['stock'] <= 0) ? 'disabled' : ($product['status'] ? 'enabled' : 'disabled');
                                              $statusText = ($product['stock'] <= 0) ? 'Disabled' : ($product['status'] ? 'Enabled' : 'Disabled');
                                              ?>
                                              <span class="status-pill <?= $statusClass ?>"><?= $statusText ?></span>
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
      </div>

      <!-- Add Bootstrap JS and jQuery -->
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
      <script src="../assets/js/lowStockAlert.js"></script>
    
      <!-- Add sidebar toggle functionality -->
      <script>
          $(document).ready(function() {
              // Toggle sidebar on mobile
              $('#toggleSidebar').on('click', function() {
                  $('body').toggleClass('sidebar-active');
                
                  // Toggle sidebar visibility
                  if ($('body').hasClass('sidebar-active')) {
                      $('.dlabnav').css('left', '0');
                  } else {
                      $('.dlabnav').css('left', '-250px');
                  }
              });
            
              // Initially hide sidebar on mobile
              if ($(window).width() <= 768) {
                  $('.dlabnav').css('left', '-250px');
              }
            
              // Adjust on window resize
              $(window).resize(function() {
                  if ($(window).width() <= 768) {
                      if (!$('body').hasClass('sidebar-active')) {
                          $('.dlabnav').css('left', '-250px');
                      }
                  } else {
                      $('.dlabnav').css('left', '0');
                  }
              });
          });
      </script>
  </body>
  </html>

  <!-- Add this button somewhere in the view for debugging -->
<button id="debug-status" style="margin: 10px; padding: 5px 10px; background: #f0f0f0; border: 1px solid #ccc; border-radius: 4px;">
    Debug Status Elements
</button>

<script>
    document.getElementById('debug-status').addEventListener('click', function() {
        console.log("=== DEBUG STATUS ELEMENTS ===");
        
        // Debug table rows
        document.querySelectorAll('.table-row').forEach((row, i) => {
            const statusEl = row.querySelector('.status .status-pill');
            if (statusEl) {
                console.log(`Table Row ${i}:`, {
                    text: statusEl.textContent,
                    class: statusEl.className,
                    display: row.style.display
                });
            }
        });
        
        // Debug cards
        document.querySelectorAll('.product-card').forEach((card, i) => {
            const statusEl = card.querySelector('.status-pill');
            if (statusEl) {
                console.log(`Card ${i}:`, {
                    text: statusEl.textContent,
                    class: statusEl.className,
                    display: card.style.display
                });
            }
        });
        
        // Debug filter value
        console.log("Current status filter:", document.getElementById('status').value);
    });
</script>
