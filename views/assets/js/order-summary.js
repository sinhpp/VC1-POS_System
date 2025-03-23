$(document).ready(function() {
    // Handle "Add to Order" button click
    $('.add-to-order').on('click', function() {
        const barcode = $(this).data('barcode');

        $.ajax({
            url: '/order/add',
            method: 'POST',
            data: { barcode: barcode, add: true },
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    // Display error message
                    $('#productDetails').prepend('<div class="alert alert-danger">' + response.error + '</div>');
                    setTimeout(() => $('.alert-danger').remove(), 3000); // Remove after 3 seconds
                } else {
                    // Clear existing table rows
                    $('#orderItems').empty();

                    // Add updated order items to the table
                    response.order.forEach(function(item, index) {
                        const row = `
                            <tr data-index="${index}">
                                <td><img src="${item.image}" width="50" alt="${item.name}"></td>
                                <td>${item.name}</td>
                                <td>${item.barcode}</td>
                                <td>$${item.price}</td>
                                <td>${item.quantity}</td>
                                <td>${item.stock}</td>
                                <td>${item.created_at}</td>
                                <td>
                                    <form action="/product/delete" method="POST">
                                        <input type="hidden" name="index" value="${index}">
                                        <button type="submit" name="delete" class="btn btn-danger btn-sm">Cancel</button>
                                    </form>
                                </td>
                            </tr>
                        `;
                        $('#orderItems').append(row);
                    });
                }
            },
            error: function() {
                alert('An error occurred while adding the product.');
            }
        });
    });
});