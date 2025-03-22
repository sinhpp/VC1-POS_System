<?php
require_once __DIR__ . '/../Database/Database.php';
require_once __DIR__ . '/../Models/ProductModel.php';
require_once __DIR__ . '/../Models/OrderModel.php';

class ProductScanController extends BaseController {
    private $productModel;
    private $orderModel;

    public function __construct() {
        session_start();
        if (!isset($_SESSION['order'])) {
            $_SESSION['order'] = [];
        }
        $this->productModel = new ProductModel();
        $this->orderModel = new OrderModel();
    }

    // Display the product scanner page
    public function index() {
        $data = [
            'order' => $_SESSION['order'],
            'products' => [], // Default to empty array
            'error' => null   // Default to null
        ];

        if (isset($_SESSION['product'])) {
            $data['products'] = [$_SESSION['product']]; // Wrap in array for the view
            // Do not unset $_SESSION['product'] here to keep it visible until a new scan
        }

        if (isset($_SESSION['error'])) {
            $data['error'] = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        $this->view('order/product-scanning', $data);
    }

    // Handle product scanning (just display the product)
    public function scan() {
        if (isset($_POST['scan'])) {
            $barcode = filter_input(INPUT_POST, 'barcode');
            $product = $this->productModel->getProductByBarcode($barcode);

            if ($product) {
                $_SESSION['product'] = $product;
            } else {
                $_SESSION['error'] = "Product not found!";
            }
        }
        header("Location: 
        /order");
        exit();
    }

    // Add product to order
    public function add() {
        if (isset($_POST['add'])) {
            $barcode = filter_input(INPUT_POST, 'barcode');
            $product = $this->productModel->getProductByBarcode($barcode);

            if ($product) {
                $found = false;
                foreach ($_SESSION['order'] as &$item) {
                    if ($item['barcode'] === $barcode) {
                        if ($item['quantity'] + 1 <= $product['stock']) {
                            $item['quantity'] += 1;
                        } else {
                            $_SESSION['error'] = "Cannot add more items; stock limit reached!";
                        }
                        $found = true;
                        break;
                    }
                }
                if (!$found && $product['stock'] > 0) {
                    $product['quantity'] = 1;
                    $_SESSION['order'][] = $product;
                } elseif ($product['stock'] <= 0) {
                    $_SESSION['error'] = "Product out of stock!";
                }
                // Keep the product in $_SESSION['product'] to display it
                $_SESSION['product'] = $product;
            } else {
                $_SESSION['error'] = "Product not found!";
            }
        }
        header("Location: /order");
        exit();
    }

    // Delete product from order
    public function delete() {
        if (isset($_POST['delete'])) {
            $index = filter_input(INPUT_POST, 'index', FILTER_VALIDATE_INT);
            if ($index !== false && isset($_SESSION['order'][$index])) {
                unset($_SESSION['order'][$index]);
                $_SESSION['order'] = array_values($_SESSION['order']);
            }
        }
        header("Location: 
        /order");
        exit();
    }

    // Display checkout page
    public function checkout() {
        if (empty($_SESSION['order'])) {
            header("Location: 
            /order");
            exit();
        }

        $totalPrice = 0;
        foreach ($_SESSION['order'] as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        $data = [
            'order' => $_SESSION['order'],
            'totalPrice' => $totalPrice,
            'deliveryDate' => date('Y-m-d', strtotime('+7 days'))
        ];

        $this->view('product/checkout', $data);
    }

    // Process checkout and generate PDF
    public function processCheckout() {
        if (isset($_POST['checkout'])) {
            $customerName = htmlspecialchars($_POST['customerName'] ?? '', ENT_QUOTES, 'UTF-8');
            $shippingAddress = htmlspecialchars($_POST['shippingAddress'] ?? '', ENT_QUOTES, 'UTF-8');
            $billingAddress = htmlspecialchars($_POST['billingAddress'] ?? '', ENT_QUOTES, 'UTF-8');
            $contactDetails = htmlspecialchars($_POST['contactDetails'] ?? '', ENT_QUOTES, 'UTF-8');
            $paymentMethod = htmlspecialchars($_POST['paymentMethod'] ?? '', ENT_QUOTES, 'UTF-8');

            $totalPrice = 0;
            foreach ($_SESSION['order'] as $item) {
                $totalPrice += $item['price'] * $item['quantity'];
            }

            try {
                // Save order to database
                $orderId = $this->orderModel->saveOrder(
                    $customerName,
                    $shippingAddress,
                    $billingAddress,
                    $contactDetails,
                    $paymentMethod,
                    $totalPrice,
                    $_SESSION['order']
                );

                // Update stock for each product
                foreach ($_SESSION['order'] as $item) {
                    $this->productModel->updateStock($item['id'], $item['quantity']);
                }

                // Generate PDF
                require_once __DIR__ . '/../vendor/fpdf/fpdf.php';
                $pdf = new FPDF();
                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 16);
                $pdf->Cell(0, 10, 'Order Receipt', 0, 1, 'C');
                $pdf->Ln(5);

                $pdf->SetFont('Arial', 'B', 14);
                $pdf->Cell(0, 10, 'Product Information', 0, 1);
                $pdf->SetFont('Arial', '', 12);
                foreach ($_SESSION['order'] as $item) {
                    $pdf->Cell(0, 10, "Name: {$item['name']}", 0, 1);
                    $pdf->Cell(0, 10, "Price: \${$item['price']}", 0, 1);
                    $pdf->Cell(0, 10, "Quantity: {$item['quantity']}", 0, 1);
                    $pdf->Cell(0, 10, "SKU: {$item['barcode']}", 0, 1);
                    $pdf->Ln(5);
                }

                $pdf->SetFont('Arial', 'B', 14);
                $pdf->Cell(0, 10, 'Customer Information', 0, 1);
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, "Name: $customerName", 0, 1);
                $pdf->Cell(0, 10, "Shipping Address: $shippingAddress", 0, 1);
                $pdf->Cell(0, 10, "Billing Address: $billingAddress", 0, 1);
                $pdf->Cell(0, 10, "Contact: $contactDetails", 0, 1);
                $pdf->Ln(5);

                $pdf->SetFont('Arial', 'B', 14);
                $pdf->Cell(0, 10, 'Payment Information', 0, 1);
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, "Payment Method: $paymentMethod", 0, 1);
                $pdf->Cell(0, 10, "Payment Status: Completed", 0, 1);
                $pdf->Ln(5);

                $pdf->SetFont('Arial', 'B', 14);
                $pdf->Cell(0, 10, 'Order Summary', 0, 1);
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, "Total Price: \$$totalPrice", 0, 1);
                $pdf->Cell(0, 10, "Estimated Delivery: " . date('Y-m-d', strtotime('+7 days')), 0, 1);

                $pdf->Output('D', 'order_receipt.pdf');
                unset($_SESSION['order']);
                unset($_SESSION['product']); // Clear the scanned product after checkout
                exit();
            } catch (Exception $e) {
                $_SESSION['error'] = "Error processing checkout: " . $e->getMessage();
                header("Location: /order");
                exit();
            }
        }
    }
}