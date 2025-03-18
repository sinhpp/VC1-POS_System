<?php
require_once "Models/OrderModel.php";

class OrderController extends BaseController
{ 
    private $orders;

    /**
     * Constructor to initialize the order model.
     */
    public function __construct()
    {
        $this->orders = new OrderModel();
    }

    /**
     * Displays the home page with the list of orders.
     */
    public function index()
    {
        $result = $this->orders->getOrders();
        $this->view('orders/order', ['orders' => $result]);
    }

    /**
     * Displays the create order form.
     */
    public function create()
    {
        $this->view('orders/create');
    }

    /**
     * Saves a new order to the database.
     */
    public function store()
    {
        $customerName = $_POST['customer_name'];
        $orderDetails = $_POST['order_details'];
        
        $this->orders->addOrder($customerName, $orderDetails);
        $this->redirect('/order');
    }

    /**
     * Displays the edit order form.
     */
    public function edit($id)
    {
        $result = $this->orders->getOrderById($id);
        $this->view('orders/edit', ['order' => $result]);
    }

    /**
     * Updates an existing order in the database.
     */
    public function update($id)
    {
        $customerName = $_POST['customer_name'];
        $orderDetails = $_POST['order_details'];
        
        $this->orders->updateOrder($id, $customerName, $orderDetails);
        $this->redirect('/order');
    }

    /**
     * Deletes an order from the database.
     */
    public function destroy($id)
    {
        $this->orders->deleteOrder($id);
        $this->redirect('/order');
    }
}
