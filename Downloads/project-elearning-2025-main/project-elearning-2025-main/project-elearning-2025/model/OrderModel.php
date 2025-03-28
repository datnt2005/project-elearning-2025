<?php
class OrderModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database(); // Assuming Database class handles the DB connection
        $this->conn = $database->getConnection();
    }

    // Get all orders
    public function getAllOrders()
    {
        $query = "SELECT orders.*, users.name AS user_name
                  FROM orders
                  LEFT JOIN users ON orders.user_id = users.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch orders and user names
    }

    public function getOrderById($id)
    {
        $query = "SELECT orders.*, users.name AS user_name
              FROM orders
              LEFT JOIN users ON orders.user_id = users.id
              WHERE orders.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Return order with user name
    }
    public function findOrderById($orderId) {
        $sql = "SELECT * FROM orders WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$orderId]);
        return $stmt->fetch();
    }
    
    public function getUserById($user_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :user_id LIMIT 1");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);  // Trả về mảng thông tin người dùng
    }


    public function updateOrder($id, $status)
    {
        $sql = "UPDATE orders SET status = :status, updated_at = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }


    public function getCouponById($discount_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM coupons WHERE id = ? LIMIT 1");
        $stmt->execute([$discount_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Delete order
    public function deleteOrder($id)
    {
        $sql = "DELETE FROM orders WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute(); // Return true/false depending on success
    }

    // Phương thức cập nhật payment_status trong OrderModel
    public function getOrderByOrderCode($orderCode) {
        $sql = "SELECT * FROM orders WHERE order_code = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$orderCode]);
        return $stmt->fetch();
    }
    
    

    public function updateOrderStatus($order_id, $status) {
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$status, $order_id]);
    }
    
    public function updateOrderPaymentStatus($orderCode, $payment_status) {
        $sql = "UPDATE orders SET payment_status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$payment_status, $orderCode]);
    }
    
    
    public function createOrder($user_id, $course_id, $total_price, $total_amount, $discount_id, $discount_price, $status, $payment_method, $payment_status) {
        $orderCode = uniqid('order_');
    
        $sql = "INSERT INTO orders (user_id, course_id, order_code, total_price, discount_id, discount_price, total_amount, status, payment_method, payment_status, created_at, updated_at) 
                VALUES (:user_id, :course_id, :order_code, :total_price, :discount_id, :discount_price, :total_amount, :status, :payment_method, :payment_status, NOW(), NOW())";
    
        $stmt = $this->conn->prepare($sql);
    
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->bindParam(':order_code', $orderCode);
        $stmt->bindParam(':total_price', $total_price);
        $stmt->bindParam(':discount_id', $discount_id);
        $stmt->bindParam(':discount_price', $discount_price);
        $stmt->bindParam(':total_amount', $total_amount);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':payment_method', $payment_method);
        $stmt->bindParam(':payment_status', $payment_status);
    
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
    
        return false;
    }
    



    public function getOrderByUserAndCourse($user_id, $course_id)
    {

        $query = "SELECT * FROM orders WHERE user_id = :user_id AND course_id = :course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrderByUserId($user_id) {
        $query = "SELECT *, u.name AS instructor_name, u.image AS instructor_image, c.image AS course_image FROM orders o
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
