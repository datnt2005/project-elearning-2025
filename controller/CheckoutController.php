<?php
require_once "model/OrderModel.php";
require_once "model/CourseModel.php";
require_once "view/helpers.php";

class CheckoutController {
    private $orderModel;
    private $courseModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
        $this->courseModel = new Course();
    }

    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra xem người dùng đã đăng nhập chưa
            if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
                // Nếu chưa đăng nhập, hiển thị thông báo yêu cầu đăng nhập
                echo "<script>alert('Vui lòng đăng nhập để tiếp tục thanh toán.');</script>";
                echo "<script>window.location.href = '/login';</script>";  // Chuyển hướng đến trang đăng nhập
                exit;
            }
            $total_price = $_POST['totalPrice'];
            $payment_method = 'vnpay';
            $order_code = uniqid('order_');  // Tạo mã đơn hàng duy nhất
            $user_id = $_SESSION['user']['id'];
            $course_id = $_POST['course_id'];  // Cần lấy ID khóa học từ form
            $discount_id = null;  // Nếu có mã giảm giá
            $discount_price = null;  // Nếu có giảm giá
            // $discount_id = $_POST['discount_id'] ?? null;  // Nếu có mã giảm giá
            // $discount_price = $_POST['discount_price'] ?? 0;  // Nếu có giảm giá
            $status = 'pending';  // Trạng thái đơn hàng
            $payment_status = 'completed'; // Trạng thái thanh toán
            $total_amount = $_POST['totalAmount'];
    
            // Gọi phương thức tạo đơn hàng với đầy đủ các tham số
            $isCreate = $this->orderModel->createOrder($user_id, $course_id, $order_code, $total_price , $total_amount, $discount_id, $discount_price, $total_amount, $status, $payment_method, $payment_status );
    
            if (!$isCreate) {
                $_SESSION['error_message'] = "Không thể tạo đơn hàng!";
                header("Location: /checkout");
                exit;
            }
    
            $this->processPayment();
        }
    }
    
    
    public function processPayment() {
        
        $vnp_TmnCode = "BKW22DPU"; 
        $vnp_HashSecret = "BGTKQK8L785CZKUM9HSLX5EUX70RQAIN"; 
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html"; 
        $vnp_Returnurl = "http://localhost:8000/thank-you"; 

        $vnp_TxnRef = time(); 
        $vnp_OrderInfo = "Thanh toán khóa học";
        $vnp_OrderType = "billpayment";
        $vnp_Amount = $_POST['totalAmount'] * 100; 
        $vnp_Locale = "vn";
        $vnp_BankCode = "NCB";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );

        // Sắp xếp dữ liệu
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        // Tạo URL thanh toán
        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

       
        header('Location: ' . $vnp_Url);
        exit();
    
        
    }
}
    