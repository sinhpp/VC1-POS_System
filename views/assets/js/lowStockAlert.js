document.addEventListener('DOMContentLoaded', function() {
    // Tab Navigation
    const tabs = document.querySelectorAll('.tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Filter Button
    const filterBtn = document.querySelector('.filter-btn');
    filterBtn.addEventListener('click', function() {
        const productName = document.getElementById('product-name').value;
        const quantityMin = document.getElementById('quantity-min').value;
        const quantityMax = document.getElementById('quantity-max').value;
        const subtractStock = document.getElementById('subtract-stock').value;
        const status = document.getElementById('status').value;
        
        // Here you would implement the actual filtering logic
        console.log('Filtering with:', {
            productName,
            quantityMin,
            quantityMax,
            subtractStock,
            status
        });
        
        // For demonstration purposes, we'll just show an alert
        alert('Filter applied!');
    });

    // Save Button
    const saveBtn = document.querySelector('.save-btn');
    saveBtn.addEventListener('click', function() {
        // Here you would implement the save functionality
        alert('Changes saved successfully!');
    });

    // Help Button
    const helpBtn = document.querySelector('.help-btn');
    helpBtn.addEventListener('click', function() {
        // Here you would implement the help functionality
        alert('This is the Low Stock Alert system. It helps you manage products with low inventory levels.');
    });

    // Action Buttons
    const actionBtns = document.querySelectorAll('.action-btn');
    actionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Here you would implement the action menu functionality
            alert('Action menu for this product');
        });
    });
});
