<?php
require_once "Database.php";

class CouponModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllCoupons() {
        $query = "SELECT * FROM coupons";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCouponById($id) {
        $query = "SELECT * FROM coupons WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createCoupon($code, $description, $discount_percent, $start_date, $end_date, $status) {
        $query = "INSERT INTO coupons (code, description, discount_percent, start_date, end_date, status) VALUES (:code, :description, :discount_percent, :start_date, :end_date, :status)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':discount_percent', $discount_percent);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    public function updateCoupon($id, $code, $description, $discount_percent, $start_date, $end_date, $status) {
        $query = "UPDATE coupons SET code = :code, description = :description, discount_percent = :discount_percent, start_date = :start_date, end_date = :end_date, status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':discount_percent', $discount_percent);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    public function deleteCoupon($id) {
        $query = "DELETE FROM coupons WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    public function checkCouponExists($coupon_code) {
        // Truy vấn lấy mã giảm giá
        $sql = "SELECT * FROM coupons WHERE code = :coupon_code AND status = 'active' AND start_date <= NOW() AND end_date >= NOW()";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':coupon_code', $coupon_code, PDO::PARAM_STR);
        $stmt->execute();

        $coupon = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($coupon) {
            return [
                'id' => $coupon['id'],
                'discount_percent' => $coupon['discount_percent'], // Lấy phần trăm giảm giá
            ];
        }

        return false;
    }

    public function getCouponDetailsByCode($coupon_code) {
        $sql = "SELECT id, code, description, discount_percent, start_date, end_date, status FROM coupons WHERE code = :coupon_code";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':coupon_code', $coupon_code);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
  

    public function getDiscountByCode($coupon_code) {
        $sql = "SELECT id, code, description, discount_percent, start_date, end_date, status 
                FROM coupons 
                WHERE code = :coupon_code AND status = 'active'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':coupon_code', $coupon_code);
        $stmt->execute();
        $coupon = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($coupon) {
            // Kiểm tra xem mã giảm giá có còn hiệu lực không
            $current_date = date('Y-m-d H:i:s');
            if (strtotime($coupon['start_date']) > strtotime($current_date)) {
                return ['error' => 'Mã giảm giá chưa bắt đầu.'];
            } elseif (strtotime($coupon['end_date']) < strtotime($current_date)) {
                return ['error' => 'Mã giảm giá đã hết hạn.'];
            }
    
            // Trả về thông tin mã giảm giá hợp lệ
            return $coupon;
        }
    
        return null; // Nếu không tìm thấy mã giảm giá hợp lệ
    }
    
    public function applyDiscount($coupon_code, $course_price) {
        $coupon = $this->checkCouponExists($coupon_code);
    
        if ($coupon) {
            $discount_value = $coupon['discount_value']; // Lấy giá trị giảm giá
            $discount_price = $course_price - ($course_price * ($discount_value / 100)); // Tính giá sau giảm giá
            return $discount_price;
        }
    
        return false; // Nếu mã giảm giá không hợp lệ hoặc không có
    }
}
