<?php
require_once __DIR__ . "/../Database/Database.php";


class OrderModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance(); // Get PDO instance
    }

    /**
     * Retrieve all orders from the database.
     */
    public function getOrders()
    {
        $stmt = $this->db->prepare("SELECT * FROM orders");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve a single order by ID.
     */
    public function getOrderById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Add a new order to the database.
     */
    public function addOrder($customerName, $orderDetails)
    {
        $stmt = $this->db->prepare("INSERT INTO orders (customer_name, order_details) VALUES (:customer_name, :order_details)");
        return $stmt->execute([
            ':customer_name' => $customerName,
            ':order_details' => $orderDetails
        ]);
    }

    /**
     * Update an existing order.
     */
    public function updateOrder($id, $customerName, $orderDetails)
    {
        $stmt = $this->db->prepare("UPDATE orders SET customer_name = :customer_name, order_details = :order_details WHERE id = :id");
        return $stmt->execute([
            ':customer_name' => $customerName,
            ':order_details' => $orderDetails,
            ':id' => $id
        ]);
    }

    /**
     * Delete an order from the database.
     */
    public function deleteOrder($id)
    {
        $stmt = $this->db->prepare("DELETE FROM orders WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
