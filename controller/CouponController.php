<?php
require_once "model/CouponModel.php";
require_once "view/helpers.php";

class CouponController {
    private $couponModel;

    public function __construct() {
        $this->couponModel = new CouponModel();
    }

    public function index() {
        $coupons = $this->couponModel->getAllCoupons();
        renderViewAdmin("view/admin/coupons/coupons_list.php", compact('coupons'), "Coupons List");
    }

    public function show($id) {
        $coupon = $this->couponModel->getCouponById($id);
        renderViewAdmin("view/coupons/coupons_detail.php", compact('coupon'), "Coupon Detail");
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['code'];
            $description = $_POST['description'];
            $discount_percent = $_POST['discount_percent'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $status = $_POST['status'];
            
            $this->couponModel->createCoupon($code, $description, $discount_percent, $start_date, $end_date, $status);
            header("Location: admin/coupons");
        } else {
            renderViewAdmin("view/admin/coupons/coupons_create.php", [], "Create Coupon");
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['code'];
            $description = $_POST['description'];
            $discount_percent = $_POST['discount_percent'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $status = $_POST['status'];
            
            $this->couponModel->updateCoupon($id, $code, $description, $discount_percent, $start_date, $end_date, $status);
            header("Location: /coupons");
        } else {
            $coupon = $this->couponModel->getCouponById($id);
            renderViewAdmin("view/admin/coupons/coupons_edit.php", compact('coupon'), "Edit Coupon");
        }
    }

    public function delete($id) {
        $this->couponModel->deleteCoupon($id);
        header("Location: admin/coupons");
    }
    public function applyCoupon() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $coupon_code = $_POST['coupon_code'] ?? '';
            $course_id = $_POST['course_id'] ?? '';
    
            if ($coupon_code && $course_id) {
                $coupon = $this->couponModel->getCouponByCode($coupon_code);

                
                if ($coupon) {
                    $course = $this->couponModel->getCoursePrice($course_id);
                    $discount = ($course['discount_price'] * $coupon['discount_percent']) / 100;
                    $new_price = max(0, $course['discount_price'] - $discount);
                    
    
                    echo json_encode(['success' => true, 'new_price' => number_format($new_price)]);
                    return;
                }
            }
            echo json_encode(['success' => false]);
        }
    }
    
}

