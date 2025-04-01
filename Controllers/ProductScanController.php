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
            $product = $this->productModel->getProductByBarcode($barcode);
    
            if ($product) {
                if ($product['stock'] > 0) {
                    // Reduce stock in the database
                    $this->productModel->updateStock($barcode, 1);
    
                    // Automatically add to order
                    $found = false;
    
                    // Check if the product already exists in the order
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
    
                    // If the product is new in the order list
                    if (!$found) {
                        $product['quantity'] = 1;
                        $_SESSION['order'][] = $product; // Add new product to order
                    }
    
                    // Optionally, store the product in the session for displaying details
                    $_SESSION['product'] = $product;
    
                } else {
                    $_SESSION['error'] = "Product is out of stock!"; // Set error message
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

                // Check if the product already exists in the order
                foreach ($_SESSION['order'] as &$item) {
                    if ($item['barcode'] === $barcode) {
                        if ($item['quantity'] +1 <= $product['stock']) {
                            $item['quantity'] -= 1;

                            // Reduce stock in the database
                            $this->productModel->updateStock($barcode, 1);
                        } else {
                            $_SESSION['error'] = "Cannot add more items; stock limit reached!";
                        }
                        $found = true;
                        break;
                    }
                }

                // If the product is new in the order list
                if (!$found) {
                    $product['quantity'] = 1;

                    // Reduce stock in the database
                    $this->productModel->updateStock($barcode, 1);

                    $_SESSION['order'][] = $product;
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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['checkout']) || empty($_SESSION['order'])) {
            $_SESSION['error'] = "Invalid checkout request";
            header("Location: /order");
            exit();
        }

        try {
            $this->db->beginTransaction();

            // Check if customer with this phone number already exists
            $phone = filter_input(INPUT_POST, 'contactDetails', FILTER_SANITIZE_STRING);
            $stmt = $this->db->prepare("SELECT id FROM customers WHERE phone = :phone");
            $stmt->execute([':phone' => $phone]);
            $existingCustomer = $stmt->fetch(PDO::FETCH_ASSOC);

            $customerId = null;
            $customerEmail = !empty($_POST['customerEmail']) ? filter_input(INPUT_POST, 'customerEmail', FILTER_SANITIZE_EMAIL) : null;

            if ($existingCustomer) {
                $customerId = $existingCustomer['id'];

                // Check if the provided email is already used by another customer
                if ($customerEmail) {
                    $stmt = $this->db->prepare("SELECT id FROM customers WHERE email = :email AND id != :id");
                    $stmt->execute([
                        ':email' => $customerEmail,
                        ':id' => $customerId
                    ]);
                    $emailOwner = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($emailOwner) {
                        // Email is already used by another customer, use that customer instead
                        $customerId = $emailOwner['id'];
                    } else {
                        // Update the email for the current customer
                        $stmt = $this->db->prepare("UPDATE customers SET email = :email WHERE id = :id");
                        $stmt->execute([
                            ':email' => $customerEmail,
                            ':id' => $customerId
                        ]);
                    }
                }
            } else {
                // Check if the email already exists (for new customer)
                if ($customerEmail) {
                    $stmt = $this->db->prepare("SELECT id FROM customers WHERE email = :email");
                    $stmt->execute([':email' => $customerEmail]);
                    $emailOwner = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($emailOwner) {
                        // Email exists, use that customer
                        $customerId = $emailOwner['id'];
                    } else {
                        // Insert new customer
                        $stmt = $this->db->prepare("INSERT INTO customers (name, phone, email) VALUES (:name, :phone, :email)");
                        $stmt->execute([
                            ':name' => filter_input(INPUT_POST, 'customerName', FILTER_SANITIZE_STRING),
                            ':phone' => $phone,
                            ':email' => $customerEmail
                        ]);
                        $customerId = $this->db->lastInsertId();
                    }
                } else {
                    // No email provided, insert new customer
                    $stmt = $this->db->prepare("INSERT INTO customers (name, phone, email) VALUES (:name, :phone, :email)");
                    $stmt->execute([
                        ':name' => filter_input(INPUT_POST, 'customerName', FILTER_SANITIZE_STRING),
                        ':phone' => $phone,
                        ':email' => null
                    ]);
                    $customerId = $this->db->lastInsertId();
                }
            }

            // Calculate totals
            $subtotal = array_sum(array_map(
                fn($item) => $item['price'] * $item['quantity'],
                $_SESSION['order']
            ));
            $stmt = $this->db->prepare("SELECT tax_rate FROM tax_config WHERE tax_name = 'VAT'");
            $stmt->execute();
            $taxRate = $stmt->fetchColumn() / 100 ?: 0.15; // Default to 15% if not found
            $tax = $subtotal * $taxRate;
            $discountRate = 0.06;
            $discount = $subtotal * $discountRate;
            $totalAmount = $subtotal + $tax - $discount;

            // Insert order
            $stmt = $this->db->prepare("INSERT INTO orders (user_id, customer_id, total_amount, payment_status) VALUES (:user_id, :customer_id, :total, 'paid')");
            $stmt->execute([
                ':user_id' => $_SESSION['user_id'] ?? 1, // Fallback user_id if not set
                ':customer_id' => $customerId,
                ':total' => $totalAmount
            ]);
            $orderId = $this->db->lastInsertId();

            // Insert order items and update stock
            foreach ($_SESSION['order'] as $item) {
                // Insert order item
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

                // Log inventory transaction
                $stmt = $this->db->prepare("INSERT INTO inventory_transactions (product_id, change_type, quantity) VALUES (:product_id, 'sale', :quantity)");
                $stmt->execute([
                    ':product_id' => $item['id'],
                    ':quantity' => $item['quantity']
                ]);
            }

            // Insert payment
            $paymentMethod = filter_input(INPUT_POST, 'paymentMethod', FILTER_SANITIZE_STRING);
            $stmt = $this->db->prepare("INSERT INTO payments (order_id, payment_method, amount) VALUES (:order_id, :method, :amount)");
            $stmt->execute([
                ':order_id' => $orderId,
                ':method' => $paymentMethod,
                ':amount' => $totalAmount
            ]);

            $this->db->commit();

            // Generate PDF receipt
            $pdf = new FPDF('P', 'mm', 'A4');
            $pdf->AddPage();
            $pdf->SetAutoPageBreak(true, 10);

            // Header
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, 'SREYTOCH SHOP', 0, 1, 'C');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 6, '271, Street Number 06/B, Sen Sok, Teuktha, KH', 0, 1, 'C');
            $pdf->Cell(0, 6, 'Tel: (+855) 16 872 177', 0, 1, 'C');
            $pdf->Cell(0, 6, 'Email: sreytoch@gmail.com', 0, 1, 'C');

            // Receipt Info
            $pdf->Ln(5);
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->SetTextColor(255, 0, 0);
            $pdf->Cell(0, 10, 'RECEIPT', 0, 1, 'C');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 6, 'Date: ' . date('F d, Y H:i:s'), 0, 1, 'C');
            $pdf->Cell(0, 6, 'Order #: ' . $orderId, 0, 1, 'C');

            // Customer Info
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

            // Order Details
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
                $pdf->Cell(80, 7, $item['name'], 1, 0, 'L');
                $pdf->Cell(30, 7, '$' . number_format($item['price'], 2), 1, 0, 'R');
                $pdf->Cell(20, 7, $item['quantity'], 1, 0, 'C');
                $pdf->Cell(40, 7, '$' . number_format($item['price'] * $item['quantity'], 2), 1, 1, 'R');
            }

            // Totals
            $pdf->Ln(5);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(130, 7, 'Subtotal:', 0, 0, 'R');
            $pdf->Cell(40, 7, '$' . number_format($subtotal, 2), 0, 1, 'R');
            $pdf->Cell(130, 7, 'Tax VAT (' . ($taxRate * 100) . '%):', 0, 0, 'R');
            $pdf->Cell(40, 7, '$' . number_format($tax, 2), 0, 1, 'R');
            $pdf->Cell(130, 7, 'Discount (6%):', 0, 0, 'R');
            $pdf->Cell(40, 7, '-$' . number_format($discount, 2), 0, 1, 'R');
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetFillColor(255, 0, 0);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell(130, 10, 'Total Amount:', 'T', 0, 'R', true);
            $pdf->Cell(40, 10, '$' . number_format($totalAmount, 2), 'T', 1, 'R', true);
            $pdf->SetTextColor(0, 0, 0);

            // Payment Method
            $pdf->Ln(5);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 7, 'Payment Method: ' . ucfirst($paymentMethod), 0, 1);

            // Footer
            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'I', 10);
            $pdf->Cell(0, 6, 'Thank you for shopping with us!', 0, 1, 'C');

            // Ensure the temp directory exists
            $tempDir = __DIR__ . '/../../temp';
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $tempFile = $tempDir . '/receipt_' . $orderId . '.pdf';
            $pdf->Output('F', $tempFile);

            // Handle receipt delivery based on user choice
            $receiptDelivery = $_POST['receiptDelivery'] ?? 'print';
            if ($receiptDelivery === 'email') {
                if (empty($customerEmail) || !filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
                    unlink($tempFile);
                    $_SESSION['error'] = "A valid email address is required for email delivery.";
                    header("Location: /views/order/checkout.php");
                    exit();
                }

                // Send email with receipt
                // $mail = new PHPMailer(true);
                // try {
                //     // Server settings
                //     $mail->isSMTP();
                //     $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
                //     $mail->SMTPAuth = true;
                //     $mail->Username = 'your-email@gmail.com'; // Replace with your email
                //     $mail->Password = 'your-app-password'; // Replace with your app password
                //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                //     $mail->Port = 587;

                //     // Recipients
                //     $mail->setFrom('your-email@gmail.com', 'Sreytoch Shop');
                //     $mail->addAddress($customerEmail);

                //     // Attachments
                //     $mail->addAttachment($tempFile, 'receipt_' . $orderId . '.pdf');

                //     // Content
                //     $mail->isHTML(true);
                //     $mail->Subject = 'Your Order Receipt - Order #' . $orderId;
                //     $mail->Body = 'Dear ' . filter_input(INPUT_POST, 'customerName', FILTER_SANITIZE_STRING) . ',<br><br>Thank you for your purchase at Sreytoch Shop! Please find your receipt attached.<br><br>Best regards,<br>Sreytoch Shop Team';
                //     $mail->AltBody = 'Dear ' . filter_input(INPUT_POST, 'customerName', FILTER_SANITIZE_STRING) . ",\n\nThank you for your purchase at Sreytoch Shop! Please find your receipt attached.\n\nBest regards,\nSreytoch Shop Team";

                //     $mail->send();
                //     $_SESSION['success'] = "Receipt has been emailed to $customerEmail.";
                // } catch (Exception $e) {
                //     unlink($tempFile);
                //     $_SESSION['error'] = "Failed to send email. Error: " . $mail->ErrorInfo;
                //     header("Location: /views/order/checkout.php");
                //     exit();
                // }

                // Clean up the temp file
                // unlink($tempFile);
            } else {
                // Print the receipt
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="receipt_' . $orderId . '.pdf"');
                readfile($tempFile);
                echo '<script>window.print(); setTimeout(() => window.location.href = "/order", 1000);</script>';

                // Clean up the temp file
                unlink($tempFile);
            }

            // Clean up session data
            unset($_SESSION['order']);
            unset($_SESSION['product']);
            exit();
        } catch (Exception $e) {
            // $this->db->rollBack();
            $_SESSION['error'] = "Checkout error: " . $e->getMessage();
            header("Location: /order");
            exit();
        }
    }

    // Helper method to draw a circle (not natively supported by FPDF)
    function Circle($pdf, $x, $y, $r, $style = 'D')
    {
        $pdf->Ellipse($x, $y, $r, $r, 0, 0, 360, $style);
    }

}