<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Interface</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/views/assets/css/order-summary.css">
</head>
<body>
    <div class="container my-5">
        <div class="row">
            <!-- Payment Method Section -->
            <div class="col-md-7">
                <div class="card p-4 shadow-sm">
                    <h5 class="mb-4">Payment method</h5>
                    <!-- Card Preview -->
                    <div class="card-preview mb-4 p-3 text-white">
                        <div class="d-flex justify-content-between">
                            <span>N BANK</span>
                            <span>**** **** **** 5432</span>
                        </div>
                        <div class="mt-3">
                            <span>AL HOLDER</span>
                        </div>
                    </div>
                    <!-- Payment Form -->
                    <form>
                        <div class="mb-3">
                            <label for="savedCard" class="form-label">Use saved card:</label>
                            <select class="form-select" id="savedCard">
                                <option>Mastercard</option>
                                <option>Visa</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="cardName" class="form-label">Name on card:</label>
                            <input type="text" class="form-control" id="cardName" value="Esther Howard" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="cardNumber" class="form-label">Card number:</label>
                            <input type="text" class="form-control" id="cardNumber" value="1234-5678-9" readonly>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="expiryDate" class="form-label">Expiry date:</label>
                                <input type="text" class="form-control" id="expiryDate" placeholder="MM / YY">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cvv" class="form-label">CVV</label>
                                <input type="text" class="form-control" id="cvv" placeholder="***">
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Visa.svg" alt="Visa" width="40" class="me-2">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" width="40">
                        </div>
                    </form>
                </div>
            </div>

            <!-- Order Summary Section -->
            <div class="col-md-5">
                <div class="card p-4 shadow-sm">
                    <h5 class="mb-4">Order summary</h5>
                    <!-- Order Items -->
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://via.placeholder.com/50" alt="Secret Concealer" class="me-3">
                        <div>
                            <p class="mb-0"><strong>Secret Concealer</strong></p>
                            <p class="text-muted small">Covers dark circles</p>
                        </div>
                        <span class="ms-auto">$21.45</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://via.placeholder.com/50" alt="Vegan Powder" class="me-3">
                        <div>
                            <p class="mb-0"><strong>Vegan Powder</strong></p>
                            <p class="text-muted small">Cloud Set Baked Setting & Smoothing Talc</p>
                        </div>
                        <span class="ms-auto">$15.00</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://via.placeholder.com/50" alt="Brow Pencil" class="me-3">
                        <div>
                            <p class="mb-0"><strong>Brow Pencil</strong></p>
                            <p class="text-muted small">Angled Mechanical</p>
                        </div>
                        <span class="ms-auto">$12.45</span>
                    </div>
                    <!-- Totals -->
                    <hr>
                    <div class="d-flex justify-content-between">
                        <p>Product total</p>
                        <p>$124.50</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p>Discount %6</p>
                        <p>($12.25)</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p>Delivery fee</p>
                        <p>Free</p>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <h6>Total</h6>
                        <h6>$112.25</h6>
                    </div>
                    <!-- Order Button -->
                    <button class="btn btn-warning w-100 mt-3">Order</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>