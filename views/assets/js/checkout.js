function toggleOrderDetails() {
    const orderDetails = document.getElementById('orderDetails');
    const toggleButton = document.querySelector('.toggle-details');
    
    if (orderDetails.classList.contains('hidden')) {
        orderDetails.classList.remove('hidden');
        toggleButton.innerText = 'Hide Order Details';
        toggleButton.querySelector('i').classList.remove('fa-chevron-down');
        toggleButton.querySelector('i').classList.add('fa-chevron-up');
    } else {
        orderDetails.classList.add('hidden');
        toggleButton.innerText = 'Show Order Details';
        toggleButton.querySelector('i').classList.remove('fa-chevron-up');
        toggleButton.querySelector('i').classList.add('fa-chevron-down');
    }
}