<?php
require_once "Database.php";

class OrderModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllOrders() {
        $query = "SELECT * FROM orders";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderById($id) {
        $query = "SELECT * FROM orders WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createOrder($user_id, $course_id, $order_code, $total_price, $discount_id, $discount_price, $total_amount, $status, $payment_method, $payment_status) {
        // Generate order code (unique order reference)
        $discount_id = isset($discount_id) ? $discount_id : null;
        $sql = "INSERT INTO orders (user_id, course_id, order_code, total_price, discount_id, discount_price, total_amount, status, payment_method, payment_status, created_at, updated_at) 
                VALUES (:user_id, :course_id, :order_code, :total_price, :discount_id, :discount_price, :total_amount, :status, :payment_method, :payment_status, NOW(), NOW())";
        
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->bindParam(':order_code', $order_code);
        $stmt->bindParam(':total_price', $total_price);
        $stmt->bindParam(':discount_id', $discount_id, PDO::PARAM_NULL);
        $stmt->bindParam(':discount_price', $discount_price);
        $stmt->bindParam(':total_amount', $total_amount);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':payment_method', $payment_method);
        $stmt->bindParam(':payment_status', $payment_status);
        return $stmt->execute();
    }
}
?>
