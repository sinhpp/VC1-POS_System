<?php
require_once 'Database/Database.php';

class Order {
    private static $db; // Added to maintain a single DB connection

    // Private constructor to prevent instantiation
    private function __construct() {}

    // Initialize database connection
    private static function initDb(): PDO {
        if (isset(self::$db)) {
            // self::$db = Database::getConnection();
        }
        return self::$db;
    }

    public static function createOrder($userId, $cartItems) {
        $db = null;
        try {
            $db = self::initDb();
            
            // Calculate total considering quantity if present
            $total = array_sum(array_map(
                function($item) {
                    return isset($item['quantity']) 
                        ? $item['price'] * $item['quantity'] 
                        : $item['price'];
                }, 
                $cartItems
            ));

            $db->beginTransaction();
            
            $stmt = $db->prepare(
                "INSERT INTO orders (user_id, total_amount, status, created_at) 
                VALUES (?, ?, 'Pending', NOW())"
            );
            $stmt->execute([$userId, $total]);
            $orderId = $db->lastInsertId();

            // Insert order items if cartItems contains detailed information
            if (!empty($cartItems)) {
                $itemStmt = $db->prepare(
                    "INSERT INTO order_items (order_id, product_id, quantity, price) 
                    VALUES (?, ?, ?, ?)"
                );
                
                foreach ($cartItems as $item) {
                    $itemStmt->execute([
                        $orderId,
                        $item['product_id'] ?? null,
                        $item['quantity'] ?? 1,
                        $item['price']
                    ]);
                }
            }

            $db->commit();
            return $orderId;
        } catch (PDOException $e) {
            if ($db && $db->inTransaction()) {
                $db->rollBack();
            }
            throw new Exception("Failed to create order: " . $e->getMessage());
        }
    }

    public static function getOrderDetails($orderId) {
        try {
            $db = self::initDb();
            
            $stmt = $db->prepare(
                "SELECT o.*, 
                    GROUP_CONCAT(
                        JSON_OBJECT(
                            'product_id', oi.product_id,
                            'quantity', oi.quantity,
                            'price', oi.price
                        )
                    ) as items
                FROM orders o
                LEFT JOIN order_items oi ON o.id = oi.order_id
                WHERE o.id = ?
                GROUP BY o.id"
            );
            $stmt->execute([$orderId]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($order && $order['items']) {
                $order['items'] = json_decode('[' . $order['items'] . ']', true);
            }
            
            return $order ?: null;
        } catch (PDOException $e) {
            throw new Exception("Failed to get order details: " . $e->getMessage());
        }
    }

    public static function getAllOrders() {
        try {
            $db = self::initDb();
            
            $stmt = $db->prepare(
                "SELECT o.*, 
                    COUNT(oi.id) as item_count
                FROM orders o
                LEFT JOIN order_items oi ON o.id = oi.order_id
                GROUP BY o.id
                ORDER BY o.created_at DESC"
            );
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to get orders: " . $e->getMessage());
        }
    }
}
?>