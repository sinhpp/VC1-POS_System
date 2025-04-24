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
        padding: 5px;
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
                    <div class="card-header border-0 pb-0">
                        <h4 class="card-title">Sales Overview (Last 7 Days)</h4>
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
    </div>
</div>
<!--**********************************
    Content body end
***********************************-->

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const salesLabels = <?php echo json_encode(isset($salesLastSevenDays) ? $salesLastSevenDays['labels'] : []); ?>;
        const salesData = <?php echo json_encode(isset($salesLastSevenDays) ? $salesLastSevenDays['data'] : []); ?>;
        
        const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: salesLabels,
                datasets: [{
                    label: 'Sales ($)',
                    data: salesData,
                    backgroundColor: 'rgba(43, 193, 85, 0.2)',
                    borderColor: 'rgba(43, 193, 85, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '$' + context.parsed.y;
                            }
                        }
                    }
                }
            }
        });
        
        // Expenses Chart
        const expensesCtx = document.getElementById('expensesChart').getContext('2d');
        const expensesLabels = <?php echo json_encode(isset($expensesLastSevenDays) ? $expensesLastSevenDays['labels'] : []); ?>;
        const expensesData = <?php echo json_encode(isset($expensesLastSevenDays) ? $expensesLastSevenDays['data'] : []); ?>;
        
        const expensesChart = new Chart(expensesCtx, {
            type: 'bar',
            data: {
                labels: expensesLabels,
                datasets: [{
                    label: 'Expenses ($)',
                    data: expensesData,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '$' + context.parsed.y;
                            }
                        }
                    }
                }
            }
        });
        
        // Load recent orders via AJAX
        loadRecentOrders();
        
        // Refresh dashboard data every 5 minutes
        setInterval(refreshDashboardData, 300000);
    });
    
    function loadRecentOrders() {
        fetch('/order/getRecentOrders')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                displayRecentOrders(data);
            })
            .catch(error => {
                document.getElementById('recentOrdersContainer').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> Failed to load recent orders: ${error.message}
                    </div>
                `;
            });
    }
    
    function displayRecentOrders(orders) {
        const container = document.getElementById('recentOrdersContainer');
        
        if (!orders || orders.length === 0) {
            container.innerHTML = '<div class="alert alert-info">No recent orders found.</div>';
            return;
        }
        
        let html = `
            <div class="table-responsive">
                <table class="table table-responsive-md">
                    <thead>
                        <tr>
                            <th><strong>Order ID</strong></th>
                            <th><strong>Customer</strong></th>
                            <th><strong>Amount</strong></th>
                            <th><strong>Status</strong></th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        orders.forEach(order => {
            let statusClass = '';
            switch(order.status) {
                case 'completed':
                    statusClass = 'badge badge-success';
                    break;
                case 'pending':
                    statusClass = 'badge badge-warning';
                    break;
                case 'cancelled':
                    statusClass = 'badge badge-danger';
                    break;
                default:
                    statusClass = 'badge badge-info';
            }
            
            html += `
                <tr>
                    <td>#${order.id}</td>
                    <td>${order.customer_name || 'Walk-in Customer'}</td>
                    <td>$${parseFloat(order.total_amount).toFixed(2)}</td>
                    <td><span class="${statusClass}">${order.status}</span></td>
                </tr>
            `;
        });
        
        html += `
                    </tbody>
                </table>
            </div>
        `;
        
        container.innerHTML = html;
    }
    
    function refreshDashboardData() {
        fetch('/dashboard/getDashboardData')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Update summary cards
                document.querySelector('.bg-success .invoice-num').textContent = '$' + parseFloat(data.totalSalesToday).toFixed(2);
                document.querySelector('.bg-info .invoice-num').textContent = data.ordersToday;
                document.querySelector('.bg-danger .invoice-num').textContent = data.lowStockCount;
                document.querySelector('.bg-secondary .invoice-num').textContent = '$' + parseFloat(data.expensesToday).toFixed(2);
                
                // Update charts
                if (window.salesChart) {
                    window.salesChart.data.labels = data.salesLastSevenDays.labels;
                    window.salesChart.data.datasets[0].data = data.salesLastSevenDays.data;
                    window.salesChart.update();
                }
                
                if (window.expensesChart) {
                    window.expensesChart.data.labels = data.expensesLastSevenDays.labels;
                    window.expensesChart.data.datasets[0].data = data.expensesLastSevenDays.data;
                    window.expensesChart.update();
                }
                
                // Reload recent orders
                loadRecentOrders();
            })
            .catch(error => {
                console.error('Error refreshing dashboard data:', error);
            });
    }
</script>
