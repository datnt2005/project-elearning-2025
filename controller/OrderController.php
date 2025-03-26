<?php
require_once "model/OrderModel.php"; // Change to OrderModel
require_once "view/helpers.php";

class OrderController {
    private $orderModel; // Change to orderModel

    public function __construct() {
        $this->orderModel = new OrderModel(); // Change to OrderModel
    }

    public function index() {
        $orders = $this->orderModel->getAllOrders(); // Change method name to getAllOrders
        //compact: gom bien dien thanh array
        renderViewAdmin("view/admin/orders/orders_list.php", compact('orders'), "Orders List");
    }

    public function show($id) {
        $order = $this->orderModel->getOrderById($id); // Change method name to getOrderById
        renderViewAdmin("view/admin/orders/orders_detail.php", compact('order'), "Order Detail");
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status']; // You can update order status, for example

            $this->orderModel->updateOrder($id, $status); // Change method to updateOrder
            header("Location: /admin/orders");
        } else {
            $order = $this->orderModel->getOrderById($id); // Change to getOrderById
            renderViewAdmin("view/admin/orders/orders_edit.php", compact('order'), "Edit Order");
        }
    }

    public function delete($id) {
        $this->orderModel->deleteOrder($id); // Change to deleteOrder
        header("Location: /admin/orders");
    }
}
