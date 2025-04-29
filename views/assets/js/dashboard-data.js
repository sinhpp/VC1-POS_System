// Create this file for dashboard data updates
document.addEventListener('DOMContentLoaded', function() {
    // Initialize data fetching
    fetchOrdersToday();
    
    // Set up auto-refresh every 1 minute
    setInterval(fetchOrdersToday, 1 * 60 * 1000);
    
    // Add event listener for refresh button if it exists
    const refreshBtn = document.getElementById('refreshDashboardData');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            this.querySelector('i').classList.add('fa-spin');
            
            // Fetch all dashboard data
            fetchOrdersToday();
            
            // If you have other data fetching functions, call them here
            
            // Stop spinning after 1 second
            setTimeout(() => {
                this.querySelector('i').classList.remove('fa-spin');
            }, 1000);
        });
    }
});

function fetchOrdersToday() {
    fetch('/dashboard/orders-today')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                updateOrdersToday(data.ordersToday);
            } else {
                console.error('Error fetching orders count:', data.error);
            }
        })
        .catch(error => {
            console.error('Error fetching orders count:', error);
        });
}

function updateOrdersToday(count) {
    const orderCountElement = document.querySelector('.card.bg-info.invoice-card .invoice-num');
    if (orderCountElement) {
        // Create a counter animation effect
        const currentCount = parseInt(orderCountElement.textContent) || 0;
        const newCount = parseInt(count);
        
        if (currentCount !== newCount) {
            // Animate the count change
            animateCounter(orderCountElement, currentCount, newCount);
            
            // Add a subtle highlight effect to show the value has changed
            orderCountElement.parentElement.classList.add('highlight-update');
            setTimeout(() => {
                orderCountElement.parentElement.classList.remove('highlight-update');
            }, 2000);
        }
    }
}

function animateCounter(element, start, end) {
    let current = start;
    const increment = end > start ? 1 : -1;
    const duration = 1000; // 1 second
    const steps = Math.abs(end - start);
    const stepTime = steps > 0 ? duration / steps : duration;
    
    const timer = setInterval(() => {
        current += increment;
        element.textContent = current;
        
        if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
            clearInterval(timer);
            element.textContent = end;
        }
    }, stepTime);
}