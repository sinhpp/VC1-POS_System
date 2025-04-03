<?php
session_start();
require_once 'Database/Database.php';
require_once 'Models/ProductModel.php';
require_once 'Models/OrderModel.php';
require_once __DIR__ . '../../views/vendor/fpdf186/fpdf.php';


class ProductScanController
{
    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        if (!isset($_SESSION['order'])) {
            $_SESSION['order'] = [];
        }
        $this->productModel = new ProductModel();
        // $this->orderModel = new OrderModel();
    }

    public function index()
    {
        $data = [
            'order' => $_SESSION['order'],
            'products' => [],
            'error' => null
        ];

        if (isset($_SESSION['product'])) {
            $data['products'] = [$_SESSION['product']];
        }

        if (isset($_SESSION['error'])) {
            $data['error'] = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        require_once __DIR__ . '/../views/order/product-scanning.php';
    }

    public function scan()
    {
        if (isset($_POST['scan'])) {
            $barcode = filter_input(INPUT_POST, 'barcode', FILTER_SANITIZE_STRING);

            if (empty($barcode)) {
                $_SESSION['error'] = "Please provide a valid barcode!";
                header("Location: /order");
                exit();
            }

            // Fetch product by barcode
            $product = $this->productModel->getProductByBarcode($barcode);

            if ($product) {
                if ($product['stock'] > 0) {
                    // Reduce stock in the database
                    $this->productModel->updateStock($barcode, 1);

                    // Check if the product already exists in the order
                    $found = false;
                    foreach ($_SESSION['order'] as &$item) {
                        if ($item['barcode'] === $barcode) {
                            if ($item['quantity'] + 1 <= $product['stock'] + $item['quantity']) { // Check against original stock
                                $item['quantity'] += 1; // Increment quantity
                                $found = true;
                            } else {
                                $_SESSION['error'] = "Cannot add more items; stock limit reached!";
                            }
                            break;
                        }
                    }

                    // If the product is new, add it to the order
                    if (!$found) {
                        $product['quantity'] = 1; // Initialize quantity
                        $_SESSION['order'][] = $product; // Add full product data to the order
                    }

                    // Store the latest scanned product for display (optional)
                    $_SESSION['product'] = $product;
                } else {
                    $_SESSION['error'] = "Product is out of stock!";
                }
            } else {
                $_SESSION['error'] = "Product not found!";
            }
        }
        header("Location: /order"); // Redirect to refresh the order list
        exit();
    }


    public function add() {
        if (isset($_POST['add'])) {
            $barcode = filter_input(INPUT_POST, 'barcode', FILTER_SANITIZE_STRING);
            $product = $this->productModel->getProductByBarcode($barcode);
    
            if ($product && $product['stock'] > 0) {
                $found = false;
    
                foreach ($_SESSION['order'] as &$item) {
                    if ($item['barcode'] === $barcode) {
                        if ($item['quantity'] + 1 <= $product['stock']) {
                            $item['quantity'] += 1; // Increment quantity
                            $found = true;
                        } else {
                            $_SESSION['error'] = "Cannot add more items; stock limit reached!";
                        }
                        break;
                    }
                }
    
                if (!$found) {
                    $product['quantity'] = 1;
                    $_SESSION['order'][] = $product; // Add new product to order
                }
            } else {
                $_SESSION['error'] = "Product is out of stock!";
            }
        }
        header("Location: /order");
        exit();
    }

    public function delete()
    {
        if (isset($_POST['delete'])) {
            $index = filter_input(INPUT_POST, 'index', FILTER_VALIDATE_INT);
            if ($index !== false && isset($_SESSION['order'][$index])) {
                unset($_SESSION['order'][$index]);
                $_SESSION['order'] = array_values($_SESSION['order']);
            }
        }
        header("Location: /order");
        exit();
    }

    public function checkout()
    {
        if (empty($_SESSION['order'])) {
            header("Location: /order");
            exit();
        }

        $subtotal = 0;
        foreach ($_SESSION['order'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $stmt = $this->db->prepare("SELECT tax_rate FROM tax_config WHERE tax_name = 'VAT'");
        $stmt->execute();
        $taxRate = $stmt->fetchColumn() / 100 ?: 0.15; // Default to 15% if not found
        $taxAmount = $subtotal * $taxRate;

        $discountRate = 0.06;
        $discountAmount = $subtotal * $discountRate;
        $finalTotal = $subtotal + $taxAmount - $discountAmount;

        $data = [
            'order' => $_SESSION['order'],
            'subtotal' => $subtotal,
            'taxAmount' => $taxAmount,
            'discountAmount' => $discountAmount,
            'finalTotal' => $finalTotal,
            'deliveryDate' => date('Y-m-d', strtotime('+7 days'))
        ];

        require_once __DIR__ . '/../views/order/checkout.php';
    }

    public function processCheckout()
    {
        if (!isset($_POST['checkout']) || empty($_SESSION['order']) || !isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Invalid checkout request";
            header("Location: /views/order/checkout.php");
            exit();
        }
        header("Location: /order");
        exit();
    }

    public function printReceipt(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = "Invalid request method";
            header("Location: /order");
            exit();
        }

        $isPrintReceipt = isset($_POST['print_receipt']);
        $isCompleteOrder = isset($_POST['complete_order']);

        if (!$isPrintReceipt && !$isCompleteOrder) {
            $_SESSION['error'] = "No valid action specified";
            header("Location: /order");
            exit();
        }

        try {
            // Log incoming data
            error_log("POST data: " . print_r($_POST, true));
            error_log("Session order: " . print_r($_SESSION['order'], true));

            // Prevent re-processing on refresh
            if ($isPrintReceipt && isset($_SESSION['receipt_printed'])) {
                unset($_SESSION['receipt_printed']);
                header("Location: /order");
                exit();
            }

            $this->db->beginTransaction();
            $orderId = null;
            $totalAmount = 0;
            $subtotal = 0;
            $discount = 0;

            if ($isCompleteOrder) {
                if (empty($_SESSION['order'])) {
                    $_SESSION['error'] = "No items in order";
                    error_log("No items in order");
                    header("Location: /order");
                    exit();
                }

                // Process customer
                $phone = filter_input(INPUT_POST, 'contactDetails', FILTER_SANITIZE_STRING);
                $customerName = filter_input(INPUT_POST, 'customerName', FILTER_SANITIZE_STRING) ?: 'Unknown';
                if (!$phone) {
                    throw new Exception("Contact details are required");
                }

                $stmt = $this->db->prepare("SELECT id FROM customers WHERE phone = :phone");
                $stmt->execute([':phone' => $phone]);
                $customerId = $stmt->fetchColumn();

                if (!$customerId) {
                    $stmt = $this->db->prepare("INSERT INTO customers (name, phone) VALUES (:name, :phone)");
                    $stmt->execute([
                        ':name' => $customerName,
                        ':phone' => $phone
                    ]);
                    $customerId = $this->db->lastInsertId();
                    error_log("New customer inserted with ID: $customerId");
                } else {
                    error_log("Existing customer found with ID: $customerId");
                }

                // Calculate totals (convert price from VARCHAR to float)
                $subtotal = array_sum(array_map(function ($item) {
                    return (float)$item['price'] * $item['quantity'];
                }, $_SESSION['order']));
                // $discountRate = 0.06;
                // $discount = $subtotal * $discountRate;
                // $totalAmount = $subtotal - $discount;

                // Insert order
                $stmt = $this->db->prepare("INSERT INTO orders (user_id, customer_id, total_amount, payment_status) VALUES (:user_id, :customer_id, :total, 'paid')");
                $stmt->execute([
                    ':user_id' => $_SESSION['user_id'] ?? 1,
                    ':customer_id' => $customerId,
                    ':total' => $totalAmount
                ]);
                $orderId = $this->db->lastInsertId();
                error_log("Order inserted with ID: $orderId");

                // Insert order items and update stock
                foreach ($_SESSION['order'] as $item) {
                    $price = (float)$item['price']; // Convert VARCHAR to float
                    $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price, total_price) VALUES (:order_id, :product_id, :quantity, :unit_price, :total_price)");
                    $stmt->execute([
                        ':order_id' => $orderId,
                        ':product_id' => $item['id'],
                        ':quantity' => $item['quantity'],
                        ':unit_price' => $price,
                        ':total_price' => $price * $item['quantity']
                    ]);
                    error_log("Order item inserted for product ID: {$item['id']}");

                    // Update stock
                    $newStock = $this->productModel->updateStock($item['barcode'], $item['quantity']);
                    if ($newStock === false) {
                        throw new Exception("Failed to update stock for barcode: {$item['barcode']}");
                    }
                }

                // Insert payment (validate payment_method)
                $paymentMethod = filter_input(INPUT_POST, 'paymentMethod', FILTER_SANITIZE_STRING) ?: 'cash';
                $validMethods = ['cash', 'card', 'digital_wallet'];
                if (!in_array($paymentMethod, $validMethods)) {
                    throw new Exception("Invalid payment method: $paymentMethod");
                }
                $stmt = $this->db->prepare("INSERT INTO payments (order_id, payment_method, amount) VALUES (:order_id, :method, :amount)");
                $stmt->execute([
                    ':order_id' => $orderId,
                    ':method' => $paymentMethod,
                    ':amount' => $totalAmount
                ]);
                error_log("Payment inserted for order ID: $orderId");

                $this->db->commit();
                error_log("Transaction committed successfully");
            }

            // Handle printing
            if ($isPrintReceipt || $isCompleteOrder) {
                if (empty($_SESSION['order']) && $isPrintReceipt) {
                    $stmt = $this->db->prepare("SELECT id, total_amount FROM orders WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 1");
                    $stmt->execute([':user_id' => $_SESSION['user_id'] ?? 1]);
                    $order = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$order) {
                        $_SESSION['error'] = "No recent order found to print";
                        header("Location: /order");
                        exit();
                    }

                    $orderId = $order['id'];
                    $totalAmount = $order['total_amount'];
                    $stmt = $this->db->prepare("SELECT p.name, p.price, oi.quantity FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = :order_id");
                    $stmt->execute([':order_id' => $orderId]);
                    $_SESSION['order'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    error_log("Fetched latest order ID: $orderId for printing");
                }

                $subtotal = array_sum(array_map(function ($item) {
                    return (float)$item['price'] * $item['quantity'];
                }, $_SESSION['order']));
                // $discountRate = 0.06;
                // $discount = $subtotal * $discountRate;
                // $totalAmount = $subtotal - $discount;
                $orderId = $orderId ?? time();

                // Generate PDF
                $pdf = new FPDF('P', 'mm', 'A4');
                $pdf->AddPage();
                $pdf->SetAutoPageBreak(true, 10);

                $pdf->SetFont('Arial', 'B', 16);
                $pdf->Cell(0, 10, 'SREYTOCH SHOP', 0, 1, 'C');
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(0, 6, '271, Street Number 06/B, Sen Sok, Teuktha, KH', 0, 1, 'C');
                $pdf->Cell(0, 6, 'Tel: (+855) 16 872 177', 0, 1, 'C');
                $pdf->Cell(0, 6, 'Email: sreytoch@gmail.com', 0, 1, 'C');

                $pdf->Ln(5);
                $pdf->SetFont('Arial', 'B', 14);
                $pdf->SetTextColor(255, 0, 0);
                $pdf->Cell(0, 10, 'RECEIPT', 0, 1, 'C');
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(0, 6, 'Date: ' . date('F d, Y H:i:s'), 0, 1, 'C');
                $pdf->Cell(0, 6, 'Order #: ' . $orderId, 0, 1, 'C');

                $pdf->Ln(5);
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->Cell(0, 8, 'Customer Information', 0, 1);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(50, 6, 'Name:', 0, 0);
                $pdf->Cell(0, 6, filter_input(INPUT_POST, 'customerName', FILTER_SANITIZE_STRING), 0, 1);
                $pdf->Cell(50, 6, 'Contact:', 0, 0);
                $pdf->Cell(0, 6, filter_input(INPUT_POST, 'contactDetails', FILTER_SANITIZE_STRING), 0, 1);
                $pdf->Cell(50, 6, 'Shipping Address:', 0, 0);
                $pdf->MultiCell(0, 6, filter_input(INPUT_POST, 'shippingAddress', FILTER_SANITIZE_STRING));

                $pdf->Ln(5);
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->Cell(0, 8, 'Order Details', 0, 1);
                $pdf->SetFillColor(200, 200, 200);
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(80, 8, 'Description', 1, 0, 'L', true);
                $pdf->Cell(30, 8, 'Unit Price', 1, 0, 'R', true);
                $pdf->Cell(20, 8, 'Qty', 1, 0, 'C', true);
                $pdf->Cell(40, 8, 'Subtotal', 1, 1, 'R', true);

                $pdf->SetFont('Arial', '', 10);
                foreach ($_SESSION['order'] as $item) {
                    $price = (float)$item['price'];
                    $pdf->Cell(80, 7, $item['name'], 1, 0, 'L');
                    $pdf->Cell(30, 7, '$' . number_format($price, 2), 1, 0, 'R');
                    $pdf->Cell(20, 7, $item['quantity'], 1, 0, 'C');
                    $pdf->Cell(40, 7, '$' . number_format($price * $item['quantity'], 2), 1, 1, 'R');
                }

                $pdf->Ln(5);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(130, 7, 'Subtotal:', 0, 0, 'R');
                $pdf->Cell(40, 7, '$' . number_format($subtotal, 2), 0, 1, 'R');
                // $pdf->Cell(130, 7, 'Discount (6%):', 0, 0, 'R');
                // $pdf->Cell(40, 7, '-$' . number_format($discount, 2), 0, 1, 'R');
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->SetFillColor(255, 0, 0);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->Cell(130, 10, 'Total Amount:', 'T', 0, 'R', true);
                $pdf->Cell(40, 10, '$' . number_format($subtotal, 2), 'T', 1, 'R', true);
                $pdf->SetTextColor(0, 0, 0);

                $pdf->Ln(5);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(0, 7, 'Payment Method: ' . ucfirst($paymentMethod), 0, 1);

                $pdf->Ln(10);
                $pdf->SetFont('Arial', 'I', 10);
                $pdf->Cell(0, 6, 'Thank you for shopping with us!', 0, 1, 'C');

                $tempDir = __DIR__ . '/../../temp';
                if (!is_dir($tempDir)) {
                    mkdir($tempDir, 0755, true);
                }
                $tempFile = $tempDir . '/receipt_' . $orderId . '.pdf';
                $pdf->Output('F', $tempFile);

                ob_clean();
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="receipt_' . $orderId . '.pdf"');
                readfile($tempFile);
                unlink($tempFile);

                // Clear products after printing
                unset($_SESSION['order']);
                error_log("Order cleared from session");

                if ($isPrintReceipt) {
                    $_SESSION['receipt_printed'] = true;
                }

                echo '<script>
                window.print();
                setTimeout(() => {
                    window.location.href = "/order";
                }, 1000);
            </script>';
                exit();
            }
        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = "Error: " . $e->getMessage();
            error_log("Database error: " . $e->getMessage());
            header("Location: /order");
            exit();
        }

        header("Location: /order");
        exit();
    }

    // Helper method to draw a circle (not natively supported by FPDF)
    function Circle($pdf, $x, $y, $r, $style = 'D')
    {
        $pdf->Ellipse($x, $y, $r, $r, 0, 0, 360, $style);
    }
}
