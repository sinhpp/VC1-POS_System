<?php
session_start();
require_once 'Database/Database.php';
require_once 'Models/ProductModel.php';
require_once 'Models/OrderModel.php';

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
            $product = $this->productModel->getProductByBarcode($barcode);
    
            if ($product) {
                if ($product['stock'] > 0) {
                    // Reduce stock in the database
                    $this->productModel->updateStock($barcode, 1);
    
                    // Automatically add to order
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
    
                    $_SESSION['product'] = $product;
                } else {
                    $_SESSION['error'] = "Product is out of stock!";
                }
            } else {
                $_SESSION['error'] = "Product not found!";
            }
        }
        header("Location: /order");
        exit();
    }

    public function add()
    {
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

        $discountRate = 0.06; // Example discount rate
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
        if (!isset($_POST['checkout']) || empty($_SESSION['order'])) {
            $_SESSION['error'] = "Invalid checkout request";
            header("Location: /views/order/checkout.php");
            exit();
        }

        // Store order in the database
        $this->storeOrder();
        header("Location: /order");
        exit();
    }

    private function storeOrder()
    {
        try {
            $this->db->beginTransaction();

            // Assuming customer information is available in POST
            $phone = filter_input(INPUT_POST, 'contactDetails', FILTER_SANITIZE_STRING);
            $customerName = filter_input(INPUT_POST, 'customerName', FILTER_SANITIZE_STRING);
            $customerEmail = filter_input(INPUT_POST, 'customerEmail', FILTER_SANITIZE_EMAIL);

            // Check if customer exists or create new customer
            $stmt = $this->db->prepare("SELECT id FROM customers WHERE phone = :phone");
            $stmt->execute([':phone' => $phone]);
            $existingCustomer = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingCustomer) {
                $customerId = $existingCustomer['id'];
                if ($customerEmail) {
                    // Update email if necessary
                    $stmt = $this->db->prepare("UPDATE customers SET email = :email WHERE id = :id");
                    $stmt->execute([':email' => $customerEmail, ':id' => $customerId]);
                }
            } else {
                // Insert new customer
                $stmt = $this->db->prepare("INSERT INTO customers (name, phone, email) VALUES (:name, :phone, :email)");
                $stmt->execute([':name' => $customerName, ':phone' => $phone, ':email' => $customerEmail]);
                $customerId = $this->db->lastInsertId();
            }

            // Calculate totals
            $subtotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $_SESSION['order']));
            $taxAmount = $subtotal * ($this->db->query("SELECT tax_rate FROM tax_config WHERE tax_name = 'VAT'")->fetchColumn() / 100 ?: 0.15);
            $discountAmount = $subtotal * 0.06; // Example discount rate
            $totalAmount = $subtotal + $taxAmount - $discountAmount;

            // Insert order
            $stmt = $this->db->prepare("INSERT INTO orders (customer_id, total_amount, payment_status) VALUES (:customer_id, :total, 'pending')");
            $stmt->execute([':customer_id' => $customerId, ':total' => $totalAmount]);
            $orderId = $this->db->lastInsertId();

            // Insert order items and update stock
            foreach ($_SESSION['order'] as $item) {
                $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price, total_price) VALUES (:order_id, :product_id, :quantity, :unit_price, :total_price)");
                $stmt->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $item['id'],
                    ':quantity' => $item['quantity'],
                    ':unit_price' => $item['price'],
                    ':total_price' => $item['price'] * $item['quantity']
                ]);

                // Update stock
                $this->productModel->updateStock($item['barcode'], $item['quantity']);
            }

            $this->db->commit();
            $_SESSION['success'] = "Order saved successfully! Order ID: $orderId";
            unset($_SESSION['order']); // Clear the order session after storing
        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = "Failed to save order: " . $e->getMessage();
        }
    }
}