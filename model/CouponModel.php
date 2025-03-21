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
    public function checkCouponExists($coupon_code)
    {
        $sql = "SELECT id, discount_value FROM coupons WHERE code = :coupon_code AND status = 'active'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':coupon_code', $coupon_code);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}
