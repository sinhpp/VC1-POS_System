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
        const productName = document.getElementById('product-name').value.toLowerCase();
        const quantityMin = parseInt(document.getElementById('quantity-min').value) || 0;
        const quantityMax = parseInt(document.getElementById('quantity-max').value) || 999;
        const subtractStock = document.getElementById('subtract-stock').value;
        const status = document.getElementById('status').value;
        
        // Filter the rows
        const rows = document.querySelectorAll('.table-row');
        rows.forEach(row => {
            const name = row.querySelector('.product-name span').textContent.toLowerCase();
            const quantity = parseInt(row.querySelector('.quantity').textContent);
            const subtract = row.querySelector('.subtract-stock').textContent;
            const productStatus = row.querySelector('.status-pill').textContent;
            
            // Check if the row matches all filter criteria
            const nameMatch = name.includes(productName);
            const quantityMatch = quantity >= quantityMin && quantity <= quantityMax;
            const subtractMatch = subtractStock === '' || subtract.toLowerCase().includes(subtractStock.toLowerCase());
            const statusMatch = status === '' || productStatus.toLowerCase().includes(status.toLowerCase());
            
            // Show or hide the row based on filter matches
            if (nameMatch && quantityMatch && subtractMatch && statusMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
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
