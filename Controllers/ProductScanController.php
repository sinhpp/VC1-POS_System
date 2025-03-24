<?php
session_start();
require_once 'Database/Database.php';
require_once 'Models/ProductModel.php';
require_once 'Models/OrderModel.php';
require_once __DIR__ . '../../views/vendor/fpdf186/fpdf.php';

class ProductScanController {
    private $productModel;
    private $orderModel;
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
        if (!isset($_SESSION['order'])) {
            $_SESSION['order'] = [];
        }
        $this->productModel = new ProductModel();
        $this->orderModel = new OrderModel();
    }

    public function index() {
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

    public function scan() {
        if (isset($_POST['scan'])) {
            $barcode = filter_input(INPUT_POST, 'barcode', FILTER_SANITIZE_STRING);
            $product = $this->productModel->getProductByBarcode($barcode);
            if ($product) {
                $_SESSION['product'] = $product;
            } else {
                $_SESSION['error'] = "Product not found!";
            }
        }
        header("Location: /order");
        exit();
    }

    public function add() {
        if (isset($_POST['add'])) {
            $barcode = filter_input(INPUT_POST, 'barcode', FILTER_SANITIZE_STRING);
            $product = $this->productModel->getProductByBarcode($barcode);
            if ($product) {
                $found = false;
                foreach ($_SESSION['order'] as &$item) {
                    if ($item['barcode'] === $barcode) {
                        if ($item['quantity'] + 1 <= $product['stock']) {
                            $item['quantity'] += 1;
                            $item['stock'] -= 1; // Update session stock
                        } else {
                            $_SESSION['error'] = "Cannot add more items; stock limit reached!";
                        }
                        $found = true;
                        break;
                    }
                }
                if (!$found && $product['stock'] > 0) {
                    $product['quantity'] = 1;
                    $product['stock'] -= 1; // Update session stock
                    $_SESSION['order'][] = $product;
                }
                $_SESSION['product'] = $product;
            }
        }
        header("Location: /order");
        exit();
    }

    public function delete() {
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

    public function checkout() {
        if (empty($_SESSION['order'])) {
            header("Location: /order");
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

        require_once __DIR__ . '/../views/order/checkout.php';
    }

    public function processCheckout() {
        if (!isset($_POST['checkout']) || empty($_SESSION['order']) || !isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Invalid checkout request";
            header("Location: /views/order/checkout.php");
            exit();
        }

        try {
            $this->db->beginTransaction();

            // Add customer
            $query = "INSERT INTO customers (name, phone, email) 
                     VALUES (:name, :phone, :email)";
            $stmt = $this->db->prepare($query);
            $phone = filter_var($_POST['contactDetails'], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['customerName'] . '@example.com', FILTER_SANITIZE_EMAIL);
            $stmt->execute([
                ':name' => filter_var($_POST['customerName'], FILTER_SANITIZE_STRING),
                ':phone' => $phone,
                ':email' => $email
            ]);
            $customer_id = $this->db->lastInsertId();

            // Calculate totals with discount
            $subtotal = array_sum(array_map(function($item) {
                return $item['price'] * $item['quantity'];
            }, $_SESSION['order']));
            $discount = $subtotal * 0.06;
            $total_amount = $subtotal - $discount;

            // Create order
            $query = "INSERT INTO orders (user_id, customer_id, total_amount, payment_status) 
                     VALUES (:user_id, :customer_id, :total, 'paid')";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':user_id' => $_SESSION['user_id'],
                ':customer_id' => $customer_id,
                ':total' => $total_amount
            ]);
            $order_id = $this->db->lastInsertId();

            // Process order items and update inventory
            foreach ($_SESSION['order'] as $item) {
                if (!$this->productModel->updateStock($item['id'], $item['quantity'])) {
                    throw new Exception("Insufficient stock for " . $item['name']);
                }

                // Log inventory transaction
                $query = "INSERT INTO inventory_transactions (product_id, change_type, quantity) 
                         VALUES (:product_id, 'sale', :quantity)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    ':product_id' => $item['id'],
                    ':quantity' => $item['quantity']
                ]);

                $query = "INSERT INTO order_items (order_id, product_id, quantity, unit_price, total_price) 
                         VALUES (:order_id, :product_id, :quantity, :unit_price, :total_price)";
                $stmt = $this->db->prepare($query);
                $total_price = $item['price'] * $item['quantity'];
                $stmt->execute([
                    ':order_id' => $order_id,
                    ':product_id' => $item['id'],
                    ':quantity' => $item['quantity'],
                    ':unit_price' => $item['price'],
                    ':total_price' => $total_price
                ]);
            }

            // Record payment
            $query = "INSERT INTO payments (order_id, payment_method, amount) 
                     VALUES (:order_id, :method, :amount)";
            $stmt = $this->db->prepare($query);
            $payment_method = $_POST['paymentMethod'] === 'Mastercard' ? 'card' : 'card';
            $stmt->execute([
                ':order_id' => $order_id,
                ':method' => $payment_method,
                ':amount' => $total_amount
            ]);

            // Record sale
            $query = "INSERT INTO sales (user_id, total_amount, payment_method) 
                     VALUES (:user_id, :total, :method)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':user_id' => $_SESSION['user_id'],
                ':total' => $total_amount,
                ':method' => strtolower($_POST['paymentMethod'])
            ]);
            $sale_id = $this->db->lastInsertId();

            // Add discount
            $query = "INSERT INTO discounts (sale_id, discount_type, discount_value) 
                     VALUES (:sale_id, 'percentage', :value)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':sale_id' => $sale_id,
                ':value' => 6.00
            ]);

            // Add sale items
            foreach ($_SESSION['order'] as $item) {
                $query = "INSERT INTO sales_items (sale_id, product_id, quantity, price, subtotal) 
                         VALUES (:sale_id, :product_id, :quantity, :price, :subtotal)";
                $stmt = $this->db->prepare($query);
                $subtotal = $item['price'] * $item['quantity'];
                $stmt->execute([
                    ':sale_id' => $sale_id,
                    ':product_id' => $item['id'],
                    ':quantity' => $item['quantity'],
                    ':price' => $item['price'],
                    ':subtotal' => $subtotal
                ]);
            }

            // Generate PDF
            ob_start();
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, 'Order Receipt', 0, 1, 'C');
            $pdf->Ln(5);

            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(100, 10, 'Customer: ' . $_POST['customerName']);
            $pdf->Ln();
            $pdf->Cell(100, 10, 'Shipping: ' . $_POST['shippingAddress']);
            $pdf->Ln();
            $pdf->Cell(100, 10, 'Billing: ' . $_POST['billingAddress']);
            $pdf->Ln();
            $pdf->Cell(100, 10, 'Contact: ' . $_POST['contactDetails']);
            $pdf->Ln();
            $pdf->Cell(100, 10, 'Payment: ' . $_POST['paymentMethod']);
            $pdf->Ln(10);

            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(90, 10, 'Item', 1);
            $pdf->Cell(30, 10, 'Qty', 1);
            $pdf->Cell(30, 10, 'Price', 1);
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 12);
            foreach ($_SESSION['order'] as $item) {
                $pdf->Cell(90, 10, $item['name'], 1);
                $pdf->Cell(30, 10, $item['quantity'], 1);
                $pdf->Cell(30, 10, '$' . number_format($item['price'] * $item['quantity'], 2), 1);
                $pdf->Ln();
            }

            $pdf->Ln(5);
            $pdf->Cell(150, 10, 'Subtotal: $' . number_format($subtotal, 2), 0, 1, 'R');
            $pdf->Cell(150, 10, 'Discount (6%): -$' . number_format($discount, 2), 0, 1, 'R');
            $pdf->Cell(150, 10, 'Total: $' . number_format($total_amount, 2), 0, 1, 'R');

            $this->db->commit();
            
            // Clear session
            unset($_SESSION['order']);
            unset($_SESSION['product']);

            ob_end_clean();
            $pdf->Output('D', 'order_receipt_' . $order_id . '.pdf');
            exit();
        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = "Error processing checkout: " . $e->getMessage();
            header("Location: /views/order/checkout.php");
            exit();
        }
    }
}

// Route handling
$controller = new ProductScanController();
switch ($_SERVER['REQUEST_URI']) {
    case '/order':
        $controller->index();
        break;
    case '/productDetails':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') $controller->scan();
        break;
    case '/order/add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') $controller->add();
        break;
    case '/product/delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') $controller->delete();
        break;
    case '/views/order/checkout.php':
        $controller->checkout();
        break;
    case '/product/process-checkout':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') $controller->processCheckout();
        break;
}
?>