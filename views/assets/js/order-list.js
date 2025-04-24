    // 🔍 Search by customer name
    document.getElementById("searchInput").addEventListener("keyup", function () {
        const value = this.value.toLowerCase();
        const rows = document.querySelectorAll("#orderTable tbody tr");

        rows.forEach(row => {
            const name = row.cells[1].textContent.toLowerCase();
            row.style.display = name.includes(value) ? "" : "none";
        });
    });

    // 🔃 Sort by total price (toggle high <-> low)
    let sortAsc = false; // default: high → low

    function sortByPrice() {
        const tbody = document.querySelector("#orderTable tbody");
        const rows = Array.from(tbody.querySelectorAll("tr"));

        rows.sort((a, b) => {
            const aPrice = parseFloat(a.cells[2].textContent.replace('$', ''));
            const bPrice = parseFloat(b.cells[2].textContent.replace('$', ''));
            return sortAsc ? aPrice - bPrice : bPrice - aPrice;
        });

        sortAsc = !sortAsc;
        tbody.innerHTML = "";
        rows.forEach(row => tbody.appendChild(row));

        const sortBtn = document.getElementById("sortPriceBtn");
        sortBtn.textContent = sortAsc ? "Sort by Price ↑" : "Sort by Price ↓";
    }

    // 📤 Export table to Excel
    function exportToExcel() {
        let table = document.getElementById("orderTable").outerHTML;
        let dataType = 'application/vnd.ms-excel';
        let tableHTML = `
            <html xmlns:o="urn:schemas-microsoft-com:office:office"
                  xmlns:x="urn:schemas-microsoft-com:office:excel"
                  xmlns="http://www.w3.org/TR/REC-html40">
            <head><meta charset="UTF-8"></head><body>${table}</body></html>
        `;

        const blob = new Blob([tableHTML], { type: dataType });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'orders.xls';
        a.click();
    }
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
    
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to delete this order?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form via AJAX
                    fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this)
                    })
                    .then(response => {
                        if (response.ok) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'The order has been deleted.',
                                icon: 'success',
                                timer: 1500, // Auto-close after 1.5 seconds
                                showConfirmButton: false
                            });
                            // Remove the row from the table
                            this.closest('tr').remove();
                        } else {
                            Swal.fire('Error!', 'Failed to delete the order.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'An error occurred while deleting.', 'error');
                    });
                }
            });
        });
    });