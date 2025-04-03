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
    
    // ENHANCED FILTER FUNCTIONALITY
    const filterBtn = document.querySelector('.filter-btn');
    const statusSelect = document.getElementById('status');
    const productNameInput = document.getElementById('product-name');
    const quantityMinInput = document.getElementById('quantity-min');
    const quantityMaxInput = document.getElementById('quantity-max');
    const subtractStockSelect = document.getElementById('subtract-stock');
    
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
    
    // Filter products by all criteria
    function filterProducts() {
        console.log("Filtering products...");
        
        // Get filter values
        const status = statusSelect ? statusSelect.value : '';
        const productName = productNameInput ? productNameInput.value.toLowerCase() : '';
        const quantityMin = quantityMinInput && quantityMinInput.value ? parseInt(quantityMinInput.value) : null;
        const quantityMax = quantityMaxInput && quantityMaxInput.value ? parseInt(quantityMaxInput.value) : null;
        const subtractStock = subtractStockSelect ? subtractStockSelect.value : '';
        
        console.log("Filter criteria:", {
            productName,
            quantityMin,
            quantityMax,
            subtractStock,
            status
        });
        
        // First reset all visibility
        resetAllProductVisibility();
        
        // Filter table rows
        document.querySelectorAll('.table-row').forEach(row => {
            let shouldShow = true;
            
            // Filter by product name
            if (productName) {
                const nameElement = row.querySelector('.product-name span');
                if (nameElement) {
                    const name = nameElement.textContent.toLowerCase();
                    if (!name.includes(productName)) {
                        shouldShow = false;
                    }
                }
            }
            
            // Filter by quantity
            if ((quantityMin !== null || quantityMax !== null) && shouldShow) {
                const quantityElement = row.querySelector('.quantity');
                if (quantityElement) {
                    const quantity = parseInt(quantityElement.textContent);
                    
                    if (quantityMin !== null && quantity < quantityMin) {
                        shouldShow = false;
                    }
                    
                    if (quantityMax !== null && quantity > quantityMax) {
                        shouldShow = false;
                    }
                }
            }
            
            // Filter by subtract stock
            if (subtractStock && shouldShow) {
                const subtractElement = row.querySelector('.subtract-stock');
                if (subtractElement) {
                    const subtractText = subtractElement.textContent.trim();
                    if ((subtractStock === 'yes' && subtractText !== 'Yes') || 
                        (subtractStock === 'no' && subtractText !== 'No')) {
                        shouldShow = false;
                    }
                }
            }
            
            // Filter by status
            if (status && shouldShow) {
                const statusElement = row.querySelector('.status .status-pill');
                if (statusElement) {
                    const statusText = statusElement.textContent.trim();
                    if ((status === 'enabled' && statusText !== 'Enabled') || 
                        (status === 'disabled' && statusText !== 'Disabled')) {
                        shouldShow = false;
                    }
                }
            }
            
            // Apply visibility
            row.style.display = shouldShow ? '' : 'none';
        });
        
        // Filter product cards (mobile view)
        document.querySelectorAll('.product-card').forEach(card => {
            let shouldShow = true;
            
            // Filter by product name
            if (productName) {
                const nameElement = card.querySelector('.product-title');
                if (nameElement) {
                    const name = nameElement.textContent.toLowerCase();
                    if (!name.includes(productName)) {
                        shouldShow = false;
                    }
                }
            }
            
            // Filter by quantity
            if ((quantityMin !== null || quantityMax !== null) && shouldShow) {
                const quantityElements = card.querySelectorAll('.card-row');
                quantityElements.forEach(element => {
                    const label = element.querySelector('.card-label');
                    if (label && label.textContent.includes('Quantity:')) {
                        const quantityValue = element.querySelector('.card-value');
                        if (quantityValue) {
                            const quantity = parseInt(quantityValue.textContent);
                            
                            if (quantityMin !== null && quantity < quantityMin) {
                                shouldShow = false;
                            }
                            
                            if (quantityMax !== null && quantity > quantityMax) {
                                shouldShow = false;
                            }
                        }
                    }
                });
            }
            
            // Filter by subtract stock
            if (subtractStock && shouldShow) {
                const subtractElements = card.querySelectorAll('.card-row');
                subtractElements.forEach(element => {
                    const label = element.querySelector('.card-label');
                    if (label && label.textContent.includes('Subtract Stock:')) {
                        const subtractValue = element.querySelector('.card-value');
                        if (subtractValue) {
                            const subtractText = subtractValue.textContent.trim();
                            if ((subtractStock === 'yes' && subtractText !== 'Yes') || 
                                (subtractStock === 'no' && subtractText !== 'No')) {
                                shouldShow = false;
                            }
                        }
                    }
                });
            }
            
            // Filter by status
            if (status && shouldShow) {
                const statusElement = card.querySelector('.status-pill');
                if (statusElement) {
                    const statusText = statusElement.textContent.trim();
                    if ((status === 'enabled' && statusText !== 'Enabled') || 
                        (status === 'disabled' && statusText !== 'Disabled')) {
                        shouldShow = false;
                    }
                }
            }
            
            // Apply visibility
            card.style.display = shouldShow ? '' : 'none';
        });
        
        // Update pagination count
        updatePaginationCount();
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
            filterProducts();
        });
    }
    
    // Add event listeners for real-time filtering
    if (productNameInput) {
        productNameInput.addEventListener('input', function() {
            if (this.value.length >= 2 || this.value.length === 0) {
                filterProducts();
            }
        });
    }
    
    if (quantityMinInput) {
        quantityMinInput.addEventListener('change', filterProducts);
    }
    
    if (quantityMaxInput) {
        quantityMaxInput.addEventListener('change', filterProducts);
    }
    
    if (subtractStockSelect) {
        subtractStockSelect.addEventListener('change', filterProducts);
    }
    
    if (statusSelect) {
        statusSelect.addEventListener('change', filterProducts);
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
    
    // Add clear filters button functionality
    const clearFiltersBtn = document.getElementById('clear-filters');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            // Reset all filter inputs
            if (productNameInput) productNameInput.value = '';
            if (quantityMinInput) quantityMinInput.value = '';
            if (quantityMaxInput) quantityMaxInput.value = '';
            if (subtractStockSelect) subtractStockSelect.value = '';
            if (statusSelect) statusSelect.value = '';
            
            // Reset visibility
            resetAllProductVisibility();
            updatePaginationCount();
        });
    }
});
