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
    public function findOrderById($orderId)
    {
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
    public function getOrderByOrderCode($orderCode)
    {
        $sql = "SELECT * FROM orders WHERE order_code = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$orderCode]);
        return $stmt->fetch();
    }



    public function updateOrderStatus($order_id, $status)
    {
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$status, $order_id]);
    }

    public function updateOrderPaymentStatus($orderCode, $payment_status)
    {
        $sql = "UPDATE orders SET payment_status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$payment_status, $orderCode]);
    }


    public function createOrder($user_id, $course_id, $total_price, $total_amount, $discount_id, $discount_price, $status, $payment_method, $payment_status)
    {
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

    public function getOrderByUserId($user_id)
    {
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
    public function getSummaryOrders()
    {
        $sql = "SELECT COUNT(*) AS total_orders, COALESCE(SUM(total_amount), 0) AS total_revenue FROM orders WHERE status = 'completed'";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC) ?? ["total_orders" => 0, "total_revenue" => 0];
    
        // Đảm bảo JSON trả về là một mảng chứa dữ liệu
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(["data" => [$result]]);
        exit;
    }
    
    public function getCompletedOrdersByDate()
    {
        $sql = "
            SELECT 
                MIN(DATE(created_at)) AS start_date, 
                MAX(DATE(created_at)) AS end_date 
            FROM orders 
            WHERE status = 'completed'
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $dates = $stmt->fetch(PDO::FETCH_ASSOC);
    
        $start_date = $dates['start_date'] ?? null;
        $end_date = $dates['end_date'] ?? null;
    
        if (!$start_date || !$end_date) {
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode(["error" => "Không có đơn hàng nào hoàn thành"]);
            exit;
        }
    
        // Lấy danh sách đơn hàng theo ngày
        $sql = "
            SELECT 
                DATE(created_at) AS period, 
                COUNT(*) AS total 
            FROM orders 
            WHERE status = 'completed'
            GROUP BY DATE(created_at)
            ORDER BY DATE(created_at) ASC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Tính tổng đơn hàng
        $totalCompletedOrders = array_sum(array_column($orders, 'total'));
    
        // Tạo danh sách ngày từ start_date đến end_date
        $allDates = [];
        $current_date = $start_date;
    
        while (strtotime($current_date) <= strtotime($end_date)) {
            $allDates[$current_date] = 0; // Mặc định 0 đơn hàng
            $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
        }
    
        foreach ($orders as $order) {
            $allDates[$order['period']] = (int)$order['total'];
        }
    
        // Format lại dữ liệu để trả về JSON
        $result = [];
        foreach ($allDates as $date => $total) {
            $result[] = ["period" => $date, "total" => $total];
        }
    
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            "total_orders" => $totalCompletedOrders,
            "data" => $result
        ]);
        exit;
    }
    public function getCompletedOrdersDetailByDate($date)
    {
        $sql = "
            SELECT 
                o.order_code, 
                u.name AS customer_name,  -- Lấy tên từ bảng users
                o.total_amount, 
                o.payment_method, 
                o.created_at 
            FROM orders o
            JOIN users u ON o.user_id = u.id  -- Liên kết với bảng users
            WHERE o.status = 'completed' 
            AND DATE(o.created_at) = :date
            ORDER BY o.created_at ASC
        ";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(["date" => $date, "orders" => $orders]);
        exit;
    }
    

    public function getCompletedOrdersByMonth()
    {
        $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS period, COUNT(*) AS total 
            FROM orders 
            WHERE status = 'completed' 
            GROUP BY period 
            ORDER BY period ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getCompletedOrdersDetailByMonth($month)
    {
        $sql = "
            SELECT 
                o.order_code, 
                u.name AS customer_name,  
                o.total_amount, 
                o.payment_method, 
                o.created_at 
            FROM orders o
            JOIN users u ON o.user_id = u.id  
            WHERE o.status = 'completed' 
            AND DATE_FORMAT(o.created_at, '%Y-%m') = :month
            ORDER BY o.created_at ASC
        ";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':month', $month, PDO::PARAM_STR);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(["month" => $month, "orders" => $orders]);
        exit;
    }
    
    
    public function getCompletedOrdersByYear()
    {
        $sql = "SELECT DATE_FORMAT(created_at, '%Y') AS period, COUNT(*) AS total 
            FROM orders 
            WHERE status = 'completed' 
            GROUP BY period 
            ORDER BY period ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getCompletedOrdersDetailByYear($year)
{
    $sql = "
        SELECT 
            o.order_code, 
            u.name AS customer_name,  
            o.total_amount, 
            o.payment_method, 
            o.created_at 
        FROM orders o
        JOIN users u ON o.user_id = u.id  
        WHERE o.status = 'completed' 
        AND DATE_FORMAT(o.created_at, '%Y') = :year
        ORDER BY o.created_at ASC
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':year', $year, PDO::PARAM_STR);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(["year" => $year, "orders" => $orders]);
    exit;
}


}