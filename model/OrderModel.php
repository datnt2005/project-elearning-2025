<?php
class OrderModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getOrderByOrderCode($order_code) {
        $query = "SELECT * FROM orders WHERE order_code = :order_code";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_code', $order_code, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateOrderPaymentStatus($order_code, $payment_status) {
        $sql = "UPDATE orders SET payment_status = :payment_status, updated_at = NOW() WHERE order_code = :order_code";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':payment_status', $payment_status);
        $stmt->bindParam(':order_code', $order_code);
        return $stmt->execute();
    }

    public function createOrder($user_id, $course_id, $total_price, $total_amount, $discount_id, $discount_price, $status, $payment_method, $payment_status) {
        $order_code = uniqid("order_");

        $sql = "INSERT INTO orders (user_id, course_id, order_code, total_price, discount_id, discount_price, total_amount, status, payment_method, payment_status, created_at, updated_at) 
                VALUES (:user_id, :course_id, :order_code, :total_price, :discount_id, :discount_price, :total_amount, :status, :payment_method, :payment_status, NOW(), NOW())";
        
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->bindParam(':order_code', $order_code);
        $stmt->bindParam(':total_price', $total_price);
        $stmt->bindParam(':discount_id', $discount_id);
        $stmt->bindParam(':discount_price', $discount_price);
        $stmt->bindParam(':total_amount', $total_amount);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':payment_method', $payment_method);
        $stmt->bindParam(':payment_status', $payment_status);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();  // Return the ID of the last inserted row
        }

        return false;
    }

    public function getOrderByUserId($user_id) {
        $query = "SELECT * FROM orders o
                JOIN courses c ON o.course_id = c.id
                JOIN users u ON c.instructor_id = u.id
                WHERE o.user_id = :user_id AND o.status = 'completed'
                ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}

?>