<?php
class OrderModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database(); //
        $this->conn = $database->getConnection();
    }


    public function getAllOrders()
    {
        $query = "SELECT orders.*, users.name AS user_name, courses.title AS course_title
        FROM orders
        LEFT JOIN users ON orders.user_id = users.id
        LEFT JOIN courses ON orders.course_id = courses.id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrderMomoById($orderId)
    {
        // Thực hiện truy vấn để tìm đơn hàng với orderId trong cơ sở dữ liệu
        $query = "SELECT * FROM orders WHERE order_code = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$orderId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
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
        return $stmt->fetch(PDO::FETCH_ASSOC);
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


    public function deleteOrder($id)
    {
        $sql = "DELETE FROM orders WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }


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

    public function updateOrderMomoStatus($order_code, $status)
    {
        // Cập nhật trạng thái đơn hàng
        $sql = "UPDATE orders SET status = :status WHERE order_code = :order_code";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':order_code', $order_code);

        // Kiểm tra và trả về kết quả
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function updateOrderPaymentMomoStatus($order_code, $payment_status)
    {
        // Cập nhật trạng thái thanh toán
        $sql = "UPDATE orders SET payment_status = :payment_status WHERE order_code = :order_code";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':payment_status', $payment_status);
        $stmt->bindParam(':order_code', $order_code);

        // Kiểm tra và trả về kết quả
        if ($stmt->execute()) {
            return true;
        }

        return false;
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

    public function createOrderMomo($user_id, $course_id, $order_code, $total_price, $total_amount, $discount_id, $discount_price, $status, $payment_method, $payment_status)
    {
        // SQL để tạo đơn hàng
        $sql = "INSERT INTO orders (user_id, course_id, order_code, total_price, discount_id, discount_price, total_amount, status, payment_method, payment_status, created_at, updated_at) 
            VALUES (:user_id, :course_id, :order_code, :total_price, :discount_id, :discount_price, :total_amount, :status, :payment_method, :payment_status, NOW(), NOW())";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->bindParam(':order_code', $order_code);  // Nhận order_code từ controller
        $stmt->bindParam(':total_price', $total_price);
        $stmt->bindParam(':discount_id', $discount_id);
        $stmt->bindParam(':discount_price', $discount_price);
        $stmt->bindParam(':total_amount', $total_amount);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':payment_method', $payment_method);
        $stmt->bindParam(':payment_status', $payment_status);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();  // Trả về ID của đơn hàng vừa tạo
        }

        return false;  // Trả về false nếu có lỗi
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

        // Lấy danh sách đơn hàng theo ngày, bao gồm tổng tiền
        $sql = "
        SELECT 
            DATE(created_at) AS period, 
            COUNT(*) AS total_orders, 
            SUM(total_price) AS total_revenue
        FROM orders 
        WHERE status = 'completed'
        GROUP BY DATE(created_at)
        ORDER BY DATE(created_at) ASC
    ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Tính tổng đơn hàng và tổng doanh thu
        $totalCompletedOrders = array_sum(array_column($orders, 'total_orders'));
        $totalRevenue = array_sum(array_column($orders, 'total_revenue'));

        // Tạo danh sách ngày từ start_date đến end_date
        $allDates = [];
        $current_date = $start_date;

        while (strtotime($current_date) <= strtotime($end_date)) {
            $allDates[$current_date] = [
                "total_orders" => 0,
                "total_revenue" => 0.0
            ];
            $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
        }

        foreach ($orders as $order) {
            $allDates[$order['period']] = [
                "total_orders" => (int)$order['total_orders'],
                "total_revenue" => (float)$order['total_revenue']
            ];
        }

        // Format lại dữ liệu để trả về JSON
        $result = [];
        foreach ($allDates as $date => $data) {
            $result[] = [
                "period" => $date,
                "total_orders" => $data["total_orders"],
                "total_revenue" => $data["total_revenue"]
            ];
        }

        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            "total_orders" => $totalCompletedOrders,
            "total_revenue" => $totalRevenue,
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
        $sql = "
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m') AS period, 
                COUNT(*) AS total_orders,
                SUM(total_amount) AS total_revenue
            FROM orders 
            WHERE status = 'completed' 
            GROUP BY period 
            ORDER BY period ASC
        ";

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
        $sql = "SELECT DATE_FORMAT(created_at, '%Y') AS period, 
                       COUNT(*) AS total_orders, 
                       SUM(total_amount) AS total_revenue
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
