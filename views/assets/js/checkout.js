document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const quantityInputs = document.querySelectorAll(".order-item input[type='number']");
    const totalPriceElement = document.getElementById("total-price");
    const discountElement = document.getElementById("discount");
    const finalTotalElement = document.getElementById("final-total");

    // Live Update Total Price
    function updateTotal() {
        let total = 0;
        quantityInputs.forEach(input => {
            const price = parseFloat(input.dataset.price);
            const quantity = parseInt(input.value) || 0;
            total += price * quantity;
        });

        let discount = total * 0.06; // 6% discount
        let finalTotal = total - discount;

        totalPriceElement.textContent = `$${total.toFixed(2)}`;
        discountElement.textContent = `- $${discount.toFixed(2)}`;
        finalTotalElement.textContent = `$${finalTotal.toFixed(2)}`;
    }

    // Attach event listeners to quantity inputs
    quantityInputs.forEach(input => {
        input.addEventListener("input", updateTotal);
    });

    // Form Validation
    form.addEventListener("submit", function (event) {
        const requiredFields = form.querySelectorAll("input[required], select[required]");
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.style.border = "2px solid red";
                isValid = false;
            } else {
                field.style.border = "1px solid #ccc";
            }
        });

        if (!isValid) {
            event.preventDefault();
            alert("Please fill in all required fields.");
        }
    });

    // Initialize total price calculation
    updateTotal();
});
