// Create or update this file for dashboard charts
let salesChart;

function initSalesChart() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    // Create gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(40, 199, 111, 0.6)');
    gradient.addColorStop(1, 'rgba(40, 199, 111, 0.1)');
    
    // Initialize with empty data - will be populated via AJAX
    salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Sales ($)',
                data: [],
                borderColor: 'rgba(40, 199, 111, 1)',
                backgroundColor: gradient,
                borderWidth: 2,
                pointBackgroundColor: 'rgba(40, 199, 111, 1)',
                pointBorderColor: '#fff',
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return `Sales: $${context.raw.toFixed(2)}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return '$' + value;
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

function fetchSalesChartData() {
    fetch('/dashboard/chart-data')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                updateSalesChart(data.labels, data.sales);
            } else {
                console.error('Error fetching chart data:', data.error);
            }
        })
        .catch(error => {
            console.error('Error fetching chart data:', error);
        });
}

function updateSalesChart(labels, sales) {
    if (salesChart) {
        salesChart.data.labels = labels;
        salesChart.data.datasets[0].data = sales;
        salesChart.update();
        
        // Show refresh notification
        const notification = document.createElement('div');
        notification.className = 'refresh-notification';
        notification.innerHTML = '<i class="fas fa-sync-alt"></i> Chart data updated';
        document.body.appendChild(notification);
        
        // Show notification
        setTimeout(() => {
            notification.style.display = 'block';
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 500);
            }, 2000);
        }, 100);
    }
}

// Initialize and set up auto-refresh
document.addEventListener('DOMContentLoaded', function() {
    initSalesChart();
    fetchSalesChartData();
    
    // Refresh chart data every 1 minute
    setInterval(fetchSalesChartData, 1 * 60 * 1000);
});