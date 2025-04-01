document.addEventListener('DOMContentLoaded', function() {
    // Toggle filters on mobile
    const toggleFiltersBtn = document.querySelector('.toggle-filters');
    const filtersContainer = document.getElementById('filters-container');
    
    if (toggleFiltersBtn && filtersContainer) {
        toggleFiltersBtn.addEventListener('click', function() {
            const isExpanded = toggleFiltersBtn.getAttribute('aria-expanded') === 'true';
            
            if (isExpanded) {
                filtersContainer.style.display = 'none';
                toggleFiltersBtn.setAttribute('aria-expanded', 'false');
            } else {
                filtersContainer.style.display = '';
                toggleFiltersBtn.setAttribute('aria-expanded', 'true');
            }
        });
    }
    
    // Tab navigation
    const tabs = document.querySelectorAll('.tab');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Here you would typically show/hide content based on the selected tab
            // For now, we're just toggling the active state
        });
    });
    
    // Action buttons
    const actionButtons = document.querySelectorAll('.action-btn');
    
    actionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            
            // Here you would typically show a dropdown menu or take some action
            console.log('Action clicked for product ID:', productId);
            
            // Example: Show a simple alert for demonstration
            alert('Actions for product ID: ' + productId);
        });
    });
    
    // Handle responsive behavior based on screen size
    function handleResponsiveLayout() {
        const productTable = document.querySelector('.product-table');
        const productCards = document.querySelector('.product-cards');
        
        if (window.innerWidth >= 768) {
            if (productTable) productTable.style.display = 'block';
            if (productCards) productCards.style.display = 'none';
        } else {
            if (productTable) productTable.style.display = 'none';
            if (productCards) productCards.style.display = 'grid';
        }
    }
    
    // Initial call
    handleResponsiveLayout();
    
    // Listen for window resize
    window.addEventListener('resize', handleResponsiveLayout);
});
