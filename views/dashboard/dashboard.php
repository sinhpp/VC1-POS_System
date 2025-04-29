<UPDATED_CODE><!-- Add this at the top of the dashboard.php file, after any opening PHP tags -->
<?php if (isset($error)): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error!</strong> <?php echo htmlspecialchars($error); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>
<?php
// Check for database connection errors
$hasError = isset($error);
?>

<!-- Success alert for login -->
<style>
    .success-alert {
        position: fixed;
        bottom: 5px;
        width: 100%;
        background-color: rgba(0, 139, 5, 0.87);
        color: white;
        padding: 10px;
        border-radius: 5px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.21);
        display: none;
        z-index: 1000;
        opacity: 1;
        transition: opacity 1s ease;
    }
    
    @keyframes slide-out {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(100%);
        }
    }
    
    .error-message {
        background-color: #f8d7da;
        color: #721c24;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 20px;
        border: 1px solid #f5c6cb;
    }
    
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    
    .quick-action-link {
        text-decoration: none;
    }
    
    .quick-action-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 15px;
        border-radius: 8px;
        transition: all 0.3s ease;
        height: 100px;
    }
    
    .quick-action-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .quick-action-box span {
        margin-top: 5px;
        font-size: 14px;
        font-weight: 500;
    }
    
    .timeline-list {
        position: relative;
        padding: 0;
        margin: 0;
    }
    
    .timeline-item {
        display: flex;
        margin-bottom: 20px;
        position: relative;
    }
    
    .timeline-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 15px;
        flex-shrink: 0;
    }
    
    .timeline-content {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 12px;
        flex-grow: 1;
    }
    
    .timeline-content h6 {
        margin-bottom: 5px;
        font-weight: 600;
    }
    
    .timeline-content p {
        margin-bottom: 5px;
        font-size: 14px;
    }
    
    .timeline-content small {
        font-size: 12px;
    }
    
    .refresh-notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        display: none;
        z-index: 1000;
    }
    
    .performance-metric {
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 8px;
        text-align: center;
    }
    
    .performance-metric h3 {
        font-size: 24px;
        font-weight: 600;
        margin: 10px 0;
    }
    
    .ytd-stat {
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    
    .system-activity-item {
        display: flex;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    
    .system-activity-item:last-child {
        border-bottom: none;
    }
    
    .system-activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 15px;
        flex-shrink: 0;
    }
    
    .system-activity-content {
        flex-grow: 1;
    }
    
    .system-activity-content h6 {
        margin-bottom: 5px;
        font-weight: 600;
    }
    
    .system-activity-content p {
        margin-bottom: 5px;
        font-size: 14px;
    }
    
    .system-activity-content small {
        font-size: 12px;
    }
    
    @keyframes highlight-pulse {
        0% { box-shadow: 0 0 0 0 rgba(13, 202, 240, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(13, 202, 240, 0); }
        100% { box-shadow: 0 0 0 0 rgba(13, 202, 240, 0); }
    }
    
    .highlight-update {
        animation: highlight-pulse 2s ease-out;
    }
</style>

<div id="success-alert" class="success-alert">
    <div class="alert-message">Login successful! Welcome!</div>
</div>

<script>
    function showSuccessAlert() {
        const alert = document.getElementById("success-alert");
        alert.style.display = "flex";
        
        setTimeout(() => {
            alert.style.animation = 'slide-out 3s forwards';
            setTimeout(() => {
                alert.style.display = "none";
            }, 2000);
        }, 1000);

        localStorage.removeItem('loginSuccess');
    }

    function checkLoginSuccess() {
        if (localStorage.getItem('loginSuccess')) {
            showSuccessAlert();
        }
    }

    window.onload = checkLoginSuccess;
</script>

<!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="d-flex justify-content-end mb-3">
            <button id="refreshDashboardData" class="btn btn-sm btn-outline-primary" title="Refresh dashboard data">
                <i class="fas fa-sync-alt"></i> Refresh Data
            </button>
        </div>
        
        <?php if ($hasError): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <div class="row invoice-card-row">
            <!-- Total Sales Today Card -->
            <div class="col-xl-3 col-xxl-3 col-sm-6">
                <div class="card bg-success invoice-card">
                    <div class="card-body d-flex">
                        <div class="icon me-3">
                            <svg width="35px" height="34px">
                                <path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M32.482,9.730 C31.092,6.789 28.892,4.319 26.120,2.586 C22.265,0.183 17.698,-0.580 13.271,0.442 C8.843,1.458 5.074,4.140 2.668,7.990 C0.255,11.840 -0.509,16.394 0.514,20.822 C1.538,25.244 4.224,29.008 8.072,31.411 C10.785,33.104 13.896,34.000 17.080,34.000 L17.286,34.000 C20.456,33.960 23.541,33.044 26.213,31.358 C26.991,30.866 27.217,29.844 26.725,29.067 C26.234,28.291 25.210,28.065 24.432,28.556 C22.285,29.917 19.799,30.654 17.246,30.687 C14.627,30.720 12.067,29.997 9.834,28.609 C6.730,26.671 4.569,23.644 3.752,20.085 C2.934,16.527 3.546,12.863 5.486,9.763 C9.488,3.370 17.957,1.418 24.359,5.414 C26.592,6.808 28.360,8.793 29.477,11.157 C30.568,13.460 30.993,16.016 30.707,18.539 C30.607,19.448 31.259,20.271 32.177,20.371 C33.087,20.470 33.911,19.820 34.011,18.904 C34.363,15.764 33.832,12.591 32.482,9.730 L32.482,9.730 Z" />
                                <path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M22.593,11.237 L14.575,19.244 L11.604,16.277 C10.952,15.626 9.902,15.626 9.250,16.277 C8.599,16.927 8.599,17.976 9.250,18.627 L13.399,22.770 C13.725,23.095 14.150,23.254 14.575,23.254 C15.001,23.254 15.427,23.095 15.753,22.770 L24.940,13.588 C25.592,12.937 25.592,11.888 24.940,11.237 C24.289,10.593 23.238,10.593 22.593,11.237 L22.593,11.237 Z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-white invoice-num">$<?php echo isset($totalSalesToday) ? $totalSalesToday : '0.00'; ?></h2>
                            <span class="text-white fs-18">Total Sales Today</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Orders Today Card -->
            <div class="col-xl-3 col-xxl-3 col-sm-6">
                <div class="card bg-info invoice-card">
                    <div class="card-body d-flex">
                        <div class="icon me-3">
                            <svg width="35px" height="34px">
                                <path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M33.002,9.728 C31.612,6.787 29.411,4.316 26.638,2.583 C22.781,0.179 18.219,-0.584 13.784,0.438 C9.356,1.454 5.585,4.137 3.178,7.989 C0.764,11.840 -0.000,16.396 1.023,20.825 C2.048,25.247 4.734,29.013 8.584,31.417 C11.297,33.110 14.409,34.006 17.594,34.006 L17.800,34.006 C20.973,33.967 24.058,33.050 26.731,31.363 C27.509,30.872 27.735,29.849 27.243,29.072 C26.751,28.296 25.727,28.070 24.949,28.561 C22.801,29.922 20.314,30.660 17.761,30.693 C15.141,30.726 12.581,30.002 10.346,28.614 C7.241,26.675 5.080,23.647 4.262,20.088 C3.444,16.515 4.056,12.850 5.997,9.748 C10.001,3.353 18.473,1.401 24.876,5.399 C27.110,6.793 28.879,8.779 29.996,11.143 C31.087,13.447 31.513,16.004 31.227,18.527 C31.126,19.437 31.778,20.260 32.696,20.360 C33.607,20.459 34.432,19.809 34.531,18.892 C34.884,15.765 34.352,12.591 33.002,9.728 L33.002,9.728 Z" />
                                <path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M23.380,11.236 C22.728,10.585 21.678,10.585 21.026,11.236 L17.608,14.656 L14.190,11.243 C13.539,10.592 12.488,10.592 11.836,11.243 C11.184,11.893 11.184,12.942 11.836,13.593 L15.254,17.006 L11.836,20.420 C11.184,21.071 11.184,22.120 11.836,22.770 C12.162,23.096 12.588,23.255 13.014,23.255 C13.438,23.255 13.864,23.096 14.190,22.770 L17.608,19.357 L21.026,22.770 C21.352,23.096 21.777,23.255 22.203,23.255 C22.629,23.255 23.054,23.096 23.380,22.770 C24.031,22.120 24.031,21.071 23.380,20.420 L19.962,17.000 L23.380,13.587 C24.031,12.936 24.031,11.887 23.380,11.236 L23.380,11.236 Z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-white invoice-num"><?php echo isset($ordersToday) ? $ordersToday : '0'; ?></h2>
                            <span class="text-white fs-18">Orders Today</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Low Stock Count Card -->
            <div class="col-xl-3 col-xxl-3 col-sm-6">
                <div class="card bg-danger invoice-card">
                    <div class="card-body d-flex">
                        <div class="icon me-3">
                            <svg width="33px" height="32px">
                                <path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M31.963,30.931 C31.818,31.160 31.609,31.342 31.363,31.455 C31.175,31.538 30.972,31.582 30.767,31.583 C30.429,31.583 30.102,31.463 29.845,31.243 L25.802,27.786 L21.758,31.243 C21.502,31.463 21.175,31.583 20.837,31.583 C20.498,31.583 20.172,31.463 19.915,31.243 L15.872,27.786 L11.829,31.243 C11.622,31.420 11.370,31.534 11.101,31.572 C10.832,31.609 10.558,31.569 10.311,31.455 C10.065,31.342 9.857,31.160 9.710,30.931 C9.565,30.703 9.488,30.437 9.488,30.167 L9.488,17.416 L2.395,17.416 C2.019,17.416 1.658,17.267 1.392,17.001 C1.126,16.736 0.976,16.375 0.976,16.000 L0.976,6.083 C0.976,4.580 1.574,3.139 2.639,2.076 C3.703,1.014 5.146,0.417 6.651,0.417 L26.511,0.417 C28.016,0.417 29.459,1.014 30.524,2.076 C31.588,3.139 32.186,4.580 32.186,6.083 L32.186,30.167 C32.186,30.437 32.109,30.703 31.963,30.931 Z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-white invoice-num"><?php echo isset($lowStockCount) ? $lowStockCount : '0'; ?></h2>
                            <span class="text-white fs-18">Low Stock Items</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Expenses Today Card -->
            <div class="col-xl-3 col-xxl-3 col-sm-6">
                <div class="card bg-secondary invoice-card">
                    <div class="card-body d-flex">
                        <div class="icon me-3">
                            <svg width="33px" height="32px">
                                <path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M31.963,30.931 C31.818,31.160 31.609,31.342 31.363,31.455 C31.175,31.538 30.972,31.582 30.767,31.583 C30.429,31.583 30.102,31.463 29.845,31.243 L25.802,27.786 L21.758,31.243 C21.502,31.463 21.175,31.583 20.837,31.583 C20.498,31.583 20.172,31.463 19.915,31.243 L15.872,27.786 L11.829,31.243 C11.622,31.420 11.370,31.534 11.101,31.572 C10.832,31.609 10.558,31.569 10.311,31.455 C10.065,31.342 9.857,31.160 9.710,30.931 C9.565,30.703 9.488,30.437 9.488,30.167 L9.488,17.416 L2.395,17.416 C2.019,17.416 1.658,17.267 1.392,17.001 C1.126,16.736 0.976,16.375 0.976,16.000 L0.976,6.083 C0.976,4.580 1.574,3.139 2.639,2.076 C3.703,1.014 5.146,0.417 6.651,0.417 L26.511,0.417 C28.016,0.417 29.459,1.014 30.524,2.076 C31.588,3.139 32.186,4.580 32.186,6.083 L32.186,30.167 C32.186,30.437 32.109,30.703 31.963,30.931 Z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-white invoice-num">$<?php echo isset($expensesToday) ? $expensesToday : '0.00'; ?></h2>
                            <span class="text-white fs-18">Expenses Today</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Sales Chart for Last 7 Days -->
            <div class="col-xl-8 col-xxl-8">
                <div class="card">
                    <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Sales Overview (Last 7 Days)</h4>
                        <button id="refreshSalesChart" class="btn btn-sm btn-outline-primary" title="Refresh data">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Expenses Chart for Last 7 Days -->
            <div class="col-xl-4 col-xxl-4">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <h4 class="card-title">Expenses Overview (Last 7 Days)</h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="expensesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add this after the existing row with charts -->
        <div class="row">
            <!-- Sales by Category Chart -->
            <div class="col-xl-6 col-xxl-6">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <h4 class="card-title">Sales by Category</h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Customer Activity -->
            <div class="col-xl-6 col-xxl-6">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <h4 class="card-title">Recent Customer Activity</h4>
                    </div>
                    <div class="card-body">
                        <div id="customerActivityContainer">
                            <div class="text-center p-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading customer activity...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Top Categories by Revenue -->
            <div class="col-xl-6 col-xxl-6">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <h4 class="card-title">Top Categories by Revenue</h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="height: 300px;">
                            <canvas id="topCategoriesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Product Stock Status Chart -->
            <div class="col-xl-6 col-xxl-6">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <h4 class="card-title">Product Stock Status</h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="height: 300px;">
                            <canvas id="stockStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Top Selling Products -->
            <div class="col-xl-6 col-xxl-6">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <h4 class="card-title">Top Selling Products</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($topSellingProducts) && !empty($topSellingProducts)): ?>
                            <div class="table-responsive">
                                <table class="table table-responsive-md">
                                    <thead>
                                        <tr>
                                            <th><strong>Product Name</strong></th>
                                            <th><strong>Units Sold</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($topSellingProducts as $product): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                                <td><?php echo htmlspecialchars($product['total_sold']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">No data available for top selling products.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Recent Orders -->
            <div class="col-xl-6 col-xxl-6">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <h4 class="card-title">Recent Orders</h4>
                    </div>
                    <div class="card-body">
                        <div id="recentOrdersContainer">
                            <div class="text-center p-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading recent orders...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Add this after the existing rows -->
        <div class="row">
            <!-- Today's Top Selling Products -->
            <div class="col-xl-6 col-xxl-6">
                <div class="card">
                    <div class="card-header border-0 pb-0 d-flex justify-content-between">
                        <h4 class="card-title">Today's Top Selling Products</h4>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="topProductsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Today
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="topProductsDropdown">
                                <li><a class="dropdown-item active" href="javascript:void(0);" data-period="today">Today</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" data-period="week">This Week</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" data-period="month">This Month</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-responsive-md card-table transactions-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Sold</th>
                                        <th>Revenue</th>
                                    </tr>
                                </thead>
                                <tbody id="topProductsTable">
                                    <?php if (isset($todayTopProducts) && !empty($todayTopProducts)): ?>
                                        <?php foreach ($todayTopProducts as $product): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if (!empty($product['image'])): ?>
                                                        <img src="<?= htmlspecialchars($product['image']) ?>" class="rounded-circle" width="40" alt="">
                                                    <?php else: ?>
                                                        <div class="bg-light rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                            <i class="fas fa-box text-primary"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="ms-2">
                                                        <h6 class="mb-0 fs-14"><?= htmlspecialchars($product['name']) ?></h6>
                                                        <span class="fs-12 text-muted"><?= htmlspecialchars($product['category']) ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>$<?= number_format($product['price'], 2) ?></td>
                                            <td><?= $product['quantity_sold'] ?></td>
                                            <td>$<?= number_format($product['revenue'], 2) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No products sold today</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent System Activity -->
            <div class="col-xl-6 col-xxl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Recent System Activity</h4>
                    </div>
                    <div class="card-body" id="systemActivityContainer">
                        <?php if (isset($recentActivity) && !empty($recentActivity)): ?>
                            <div class="system-activity-list">
                                <?php foreach ($recentActivity as $activity): ?>
                                    <?php
                                        $iconClass = '';
                                        $bgClass = '';
                                        
                                        switch($activity['type']) {
                                            case 'login':
                                                $iconClass = 'fa-sign-in-alt';
                                                $bgClass = 'bg-primary';
                                                break;
                                            case 'product':
                                                $iconClass = 'fa-box';
                                                $bgClass = 'bg-success';
                                                break;
                                            case 'order':
                                                $iconClass = 'fa-shopping-cart';
                                                $bgClass = 'bg-warning';
                                                break;
                                            case 'user':
                                                $iconClass = 'fa-user';
                                                $bgClass = 'bg-info';
                                                break;
                                            case 'setting':
                                                $iconClass = 'fa-cog';
                                                $bgClass = 'bg-secondary';
                                                break;
                                            default:
                                                $iconClass = 'fa-info-circle';
                                                $bgClass = 'bg-dark';
                                        }
                                    ?>
                                    <div class="system-activity-item">
                                        <div class="system-activity-icon <?php echo $bgClass; ?>">
                                            <i class="fas <?php echo $iconClass; ?>"></i>
                                        </div>
                                        <div class="system-activity-content">
                                            <h6 class="mb-1"><?php echo $activity['title']; ?></h6>
                                            <p class="mb-0"><?php echo $activity['description']; ?></p>
                                            <small class="text-muted"><?php echo $activity['time_ago']; ?> by <?php echo $activity['user']; ?></small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center p-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading system activity...</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Store Performance -->
            <div class="col-xl-8 col-xxl-8">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <h4 class="card-title">Store Performance</h4>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="performanceDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                This Month
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="performanceDropdown">
                                <li><a class="dropdown-item" href="javascript:void(0);" data-period="week">This Week</a></li>
                                <li><a class="dropdown-item active" href="javascript:void(0);" data-period="month">This Month</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" data-period="quarter">This Quarter</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" data-period="year">This Year</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="performance-metric">
                                    <h6 class="text-muted">Total Sales</h6>
                                    <h3 class="mb-0">$<?= isset($performanceMetrics['totalSales']) ? number_format($performanceMetrics['totalSales'], 2) : '0.00' ?></h3>
                                    <span class="<?= isset($performanceMetrics['salesGrowth']) && $performanceMetrics['salesGrowth'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                        <i class="fas fa-<?= isset($performanceMetrics['salesGrowth']) && $performanceMetrics['salesGrowth'] >= 0 ? 'arrow-up' : 'arrow-down' ?>"></i>
                                        <?= isset($performanceMetrics['salesGrowth']) ? abs($performanceMetrics['salesGrowth']) : '0' ?>%
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="performance-metric">
                                    <h6 class="text-muted">Total Orders</h6>
                                    <h3 class="mb-0"><?= isset($performanceMetrics['totalOrders']) ? $performanceMetrics['totalOrders'] : '0' ?></h3>
                                    <span class="<?= isset($performanceMetrics['ordersGrowth']) && $performanceMetrics['ordersGrowth'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                        <i class="fas fa-<?= isset($performanceMetrics['ordersGrowth']) && $performanceMetrics['ordersGrowth'] >= 0 ? 'arrow-up' : 'arrow-down' ?>"></i>
                                        <?= isset($performanceMetrics['ordersGrowth']) ? abs($performanceMetrics['ordersGrowth']) : '0' ?>%
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="performance-metric">
                                    <h6 class="text-muted">Avg. Order Value</h6>
                                    <h3 class="mb-0">$<?= isset($performanceMetrics['avgOrderValue']) ? number_format($performanceMetrics['avgOrderValue'], 2) : '0.00' ?></h3>
                                    <span class="<?= isset($performanceMetrics['aovGrowth']) && $performanceMetrics['aovGrowth'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                        <i class="fas fa-<?= isset($performanceMetrics['aovGrowth']) && $performanceMetrics['aovGrowth'] >= 0 ? 'arrow-up' : 'arrow-down' ?>"></i>
                                        <?= isset($performanceMetrics['aovGrowth']) ? abs($performanceMetrics['aovGrowth']) : '0' ?>%
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="performance-metric">
                                    <h6 class="text-muted">Profit Margin</h6>
                                    <h3 class="mb-0"><?= isset($performanceMetrics['profitMargin']) ? $performanceMetrics['profitMargin'] : '0' ?>%</h3>
                                    <span class="<?= isset($performanceMetrics['marginGrowth']) && $performanceMetrics['marginGrowth'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                        <i class="fas fa-<?= isset($performanceMetrics['marginGrowth']) && $performanceMetrics['marginGrowth'] >= 0 ? 'arrow-up' : 'arrow-down' ?>"></i>
                                        <?= isset($performanceMetrics['marginGrowth']) ? abs($performanceMetrics['marginGrowth']) : '0' ?>%
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="chart-container" style="height: 300px;">
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="col-xl-4 col-xxl-4">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <h4 class="card-title">Year-to-Date Stats</h4>
                    </div>
                    <div class="card-body">
                        <div class="ytd-stat">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fs-14">Total Revenue</span>
                                <span class="fs-14 font-w600">$<?= isset($ytdStats['revenue']) ? number_format($ytdStats['revenue'], 2) : '0.00' ?></span>
                            </div>
                            <div class="progress mb-3" style="height:8px;">
                                <div class="progress-bar bg-primary" style="width: <?= isset($ytdStats['revenueProgress']) ? $ytdStats['revenueProgress'] : '0' ?>%; height:8px;" role="progressbar"></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="text-muted"><?= isset($ytdStats['revenueProgress']) ? $ytdStats['revenueProgress'] : '0' ?>% of annual target</small>
                                <small class="text-muted">Target: $<?= isset($ytdStats['revenueTarget']) ? number_format($ytdStats['revenueTarget'], 2) : '0.00' ?></small>
                            </div>
                        </div>
                        
                        <div class="ytd-stat mt-4">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fs-14">Total Orders</span>
                                <span class="fs-14 font-w600"><?= isset($ytdStats['orders']) ? $ytdStats['orders'] : '0' ?></span>
                            </div>
                            <div class="progress mb-3" style="height:8px;">
                                <div class="progress-bar bg-success" style="width: <?= isset($ytdStats['ordersProgress']) ? $ytdStats['ordersProgress'] : '0' ?>%; height:8px;" role="progressbar"></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="text-muted"><?= isset($ytdStats['ordersProgress']) ? $ytdStats['ordersProgress'] : '0' ?>% of annual target</small>
                                <small class="text-muted">Target: <?= isset($ytdStats['ordersTarget']) ? $ytdStats['ordersTarget'] : '0' ?></small>
                            </div>
                        </div>
                        
                        <div class="ytd-stat mt-4">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fs-14">New Customers</span>
                                <span class="fs-14 font-w600"><?= isset($ytdStats['newCustomers']) ? $ytdStats['newCustomers'] : '0' ?></span>
                            </div>
                            <div class="progress mb-3" style="height:8px;">
                                <div class="progress-bar bg-info" style="width: <?= isset($ytdStats['customersProgress']) ? $ytdStats['customersProgress'] : '0' ?>%; height:8px;" role="progressbar"></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="text-muted"><?= isset($ytdStats['customersProgress']) ? $ytdStats['customersProgress'] : '0' ?>% of annual target</small>
                                <small class="text-muted">Target: <?= isset($ytdStats['customersTarget']) ? $ytdStats['customersTarget'] : '0' ?></small>
                            </div>
                        </div>
                        
                        <div class="ytd-stat mt-4">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fs-14">Profit</span>
                                <span class="fs-14 font-w600">$<?= isset($ytdStats['profit']) ? number_format($ytdStats['profit'], 2) : '0.00' ?></span>
                            </div>
                            <div class="progress mb-3" style="height:8px;">
                                <div class="progress-bar bg-warning" style="width: <?= isset($ytdStats['profitProgress']) ? $ytdStats['profitProgress'] : '0' ?>%; height:8px;" role="progressbar"></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="text-muted"><?= isset($ytdStats['profitProgress']) ? $ytdStats['profitProgress'] : '0' ?>% of annual target</small>
                                <small class="text-muted">Target: $<?= isset($ytdStats['profitTarget']) ? number_format($ytdStats['profitTarget'], 2) : '0.00' ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--**********************************
    Content body end
***********************************-->

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Add this before the closing </body> tag, after the Chart.js script -->
<script src="/views/assets/js/dashboard-charts.js"></script>
<script src="/views/assets/js/dashboard-data.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all charts
        initPerformanceChart();
        initCategoryChart();
        initSalesExpensesChart();
        initStockStatusChart();
        initTopCategoriesChart();
        
        // Handle performance period change
        document.querySelectorAll('#performanceDropdown + .dropdown-menu .dropdown-item').forEach(item => {
            item.addEventListener('click', function() {
                const period = this.getAttribute('data-period');
                document.getElementById('performanceDropdown').textContent = 
                    period === 'week' ? 'This Week' : 
                    period === 'month' ? 'This Month' : 
                    period === 'quarter' ? 'This Quarter' : 'This Year';
                
                // Update active state
                document.querySelectorAll('#performanceDropdown + .dropdown-menu .dropdown-item').forEach(el => {
                    el.classList.remove('active');
                });
                this.classList.add('active');
                
                // Load data for selected period
                loadPerformanceData(period);
            });
        });
        
        // Handle top products period change
        document.querySelectorAll('#topProductsDropdown + .dropdown-menu .dropdown-item').forEach(item => {
            item.addEventListener('click', function() {
                const period = this.getAttribute('data-period');
                document.getElementById('topProductsDropdown').textContent = 
                    period === 'today' ? 'Today' : 
                    period === 'week' ? 'This Week' : 'This Month';
                
                // Update active state
                document.querySelectorAll('#topProductsDropdown + .dropdown-menu .dropdown-item').forEach(el => {
                    el.classList.remove('active');
                });
                this.classList.add('active');
                
                // Load data for selected period
                loadTopProducts(period);
            });
        });
        
        // Handle sales vs expenses period change
        document.querySelectorAll('#salesExpensesDropdown + .dropdown-menu .dropdown-item').forEach(item => {
            item.addEventListener('click', function() {
                const period = this.getAttribute('data-period');
                document.getElementById('salesExpensesDropdown').textContent = 
                    period === '3' ? 'Last 3 Months' : 
                    period === '6' ? 'Last 6 Months' : 'Last 12 Months';
                
                // Update active state
                document.querySelectorAll('#salesExpensesDropdown + .dropdown-menu .dropdown-item').forEach(el => {
                    el.classList.remove('active');
                });
                this.classList.add('active');
                
                // Load data for selected period
                loadSalesExpensesData(period);
            });
        });
        
        // Handle load more activity button
        document.getElementById('loadMoreActivity').addEventListener('click', function() {
            const currentCount = document.querySelectorAll('.system-activity-item').length;
            loadMoreActivity(currentCount);
        });
    });
    
    // Performance Chart
    function initPerformanceChart() {
        const ctx = document.getElementById('performanceChart').getContext('2d');
        
        // Create gradient for sales
        const salesGradient = ctx.createLinearGradient(0, 0, 0, 400);
        salesGradient.addColorStop(0, 'rgba(54, 162, 235, 0.6)');
        salesGradient.addColorStop(1, 'rgba(54, 162, 235, 0.1)');
        
        // Create gradient for expenses
        const expensesGradient = ctx.createLinearGradient(0,