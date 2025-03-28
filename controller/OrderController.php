<?php
require_once "model/OrderModel.php"; 
require_once "view/helpers.php";

class OrderController {
    private $orderModel; 

    public function __construct() {
        $this->orderModel = new OrderModel(); 
    }

    public function index() {
        $orders = $this->orderModel->getAllOrders(); 
        renderViewAdmin("view/admin/orders/orders_list.php", compact('orders'), "Orders List");
    }
    

    public function show($id) {
        $order = $this->orderModel->getOrderById($id);
        renderViewAdmin("view/admin/orders/orders_detail.php", compact('order'), "Order Detail");
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status']; 

            $this->orderModel->updateOrder($id, $status);
            header("Location: /admin/orders");
        } else {
            $order = $this->orderModel->getOrderById($id); 
            renderViewAdmin("view/admin/orders/orders_edit.php", compact('order'), "Edit Order");
        }
    }

    public function delete($id) {
        $this->orderModel->deleteOrder($id); 
        header("Location: /admin/orders");
    }
}
