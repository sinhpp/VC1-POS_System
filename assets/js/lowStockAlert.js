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
    
    // Add new function to update product status based on quantity
    function updateProductStatus() {
        // Get all product rows in table view
        const tableRows = document.querySelectorAll('.table-row');
        
        tableRows.forEach(row => {
            const quantityCell = row.querySelector('.cell.quantity');
            const statusCell = row.querySelector('.cell.status .status-pill');
            
            if (quantityCell && statusCell) {
                const quantity = parseInt(quantityCell.textContent.trim(), 10);
                
                if (quantity <= 0) {
                    // Set status to disabled with red color
                    statusCell.className = 'status-pill disabled';
                    statusCell.textContent = 'Disabled';
                }
            }
        });
        
        // Get all product cards in card view
        const productCards = document.querySelectorAll('.product-card');
        
        productCards.forEach(card => {
            const quantityValue = card.querySelector('.card-row:nth-child(1) .card-value');
            const statusPill = card.querySelector('.status-pill');
            
            if (quantityValue && statusPill) {
                const quantity = parseInt(quantityValue.textContent.trim(), 10);
                
                if (quantity <= 0) {
                    // Set status to disabled with red color
                    statusPill.className = 'status-pill disabled';
                    statusPill.textContent = 'Disabled';
                }
            }
        });
    }
    
    // Call the function on page load
    updateProductStatus();
    
    // Add event listener for filter button to update statuses after filtering
    const filterBtn = document.querySelector('.filter-btn');
    if (filterBtn) {
        filterBtn.addEventListener('click', function() {
            // Assuming there's some filtering logic here
            // After filtering is complete, update statuses
            setTimeout(updateProductStatus, 100);
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
    }
    
    // Initial call
    handleResponsiveLayout();
    
    // Listen for window resize
    window.addEventListener('resize', handleResponsiveLayout);
});
