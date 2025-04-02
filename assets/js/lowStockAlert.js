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
        });
    });
    
    // Action buttons
    const actionButtons = document.querySelectorAll('.action-btn');
    
    actionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            alert('Actions for product ID: ' + productId);
        });
    });
    
    // COMPLETELY REWRITTEN FILTER FUNCTIONALITY
    const filterBtn = document.querySelector('.filter-btn');
    const statusSelect = document.getElementById('status');
    
    // Make sure all products are visible on page load
    function resetAllProductVisibility() {
        document.querySelectorAll('.table-row').forEach(row => {
            row.style.display = '';
        });
        
        document.querySelectorAll('.product-card').forEach(card => {
            card.style.display = '';
        });
    }
    
    // Call this immediately to ensure all products are visible
    resetAllProductVisibility();
    
    // Filter products by status
    function filterByStatus(status) {
        console.log("Filtering by status:", status);
        
        // First reset all visibility
        resetAllProductVisibility();
        
        // If status is empty or "all", show everything (already done by reset)
        if (!status) {
            console.log("No status filter, showing all products");
            return;
        }
        
        // Otherwise, filter by the selected status
        document.querySelectorAll('.table-row').forEach(row => {
            const statusElement = row.querySelector('.status .status-pill');
            if (statusElement) {
                const statusText = statusElement.textContent.trim();
                console.log("Row status:", statusText, "Looking for:", status === 'enabled' ? 'Enabled' : 'Disabled');
                
                if ((status === 'enabled' && statusText !== 'Enabled') || 
                    (status === 'disabled' && statusText !== 'Disabled')) {
                    row.style.display = 'none';
                }
            }
        });
        
        document.querySelectorAll('.product-card').forEach(card => {
            const statusElement = card.querySelector('.status-pill');
            if (statusElement) {
                const statusText = statusElement.textContent.trim();
                
                if ((status === 'enabled' && statusText !== 'Enabled') || 
                    (status === 'disabled' && statusText !== 'Disabled')) {
                    card.style.display = 'none';
                }
            }
        });
    }
    
    // Update pagination count
    function updatePaginationCount() {
        const pagination = document.querySelector('.pagination p');
        if (pagination) {
            const visibleTableRows = Array.from(document.querySelectorAll('.table-row')).filter(row => 
                row.style.display !== 'none').length;
            const visibleCards = Array.from(document.querySelectorAll('.product-card')).filter(card => 
                card.style.display !== 'none').length;
            
            const visibleCount = window.innerWidth >= 768 ? visibleTableRows : visibleCards;
            pagination.textContent = `Showing 1 to ${visibleCount} of ${visibleCount} (1 Pages)`;
        }
    }
    
    // Add event listener to the filter button
    if (filterBtn) {
        filterBtn.addEventListener('click', function() {
            const status = statusSelect ? statusSelect.value : '';
            filterByStatus(status);
            updatePaginationCount();
        });
    }
    
    // Add event listener to the status select for immediate filtering
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            console.log("Status changed to:", this.value);
            filterByStatus(this.value);
            updatePaginationCount();
        });
    }
    
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
        
        // Update pagination after layout change
        updatePaginationCount();
    }
    
    // Initial call
    handleResponsiveLayout();
    
    // Listen for window resize
    window.addEventListener('resize', handleResponsiveLayout);
    
    // Initial pagination update
    updatePaginationCount();
});
