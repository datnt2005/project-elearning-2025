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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
                // Nếu chưa đăng nhập, hiển thị thông báo yêu cầu đăng nhập
                echo "<script>alert('Vui lòng đăng nhập để tiếp tục thanh toán.');</script>";
                echo "<script>window.location.href = '/login';</script>";  // Chuyển hướng đến trang đăng nhập
                exit;
            }


            $total_price = $_POST['amount'];
            $payment_method = $_POST['payment_method'];
            $orderCode = uniqid('order_'); 
            $user_id = $_SESSION['user']['id'];
            $course_id = $_POST['course_id'];  
            $discount_id = $_POST['discount_id'] ?? null;  
            $discount_price = $_POST['discount_price'] ?? 0; 
            $status = 'pending';  
            $payment_status = 'pending'; 
            $total_amount = $total_price - $discount_price;
    
           
            $order_id = $this->orderModel->createOrder($user_id, $course_id, $total_price , $total_amount, $discount_id, $discount_price, $status, $payment_method, $payment_status );
    
            if (!$order_id) {
                $_SESSION['error_message'] = "Không thể tạo đơn hàng!";
                header("Location: /checkout");
                exit;
            }
    
            // Xử lý thanh toán
            $this->processPayment($orderCode, $total_amount);
        }
    }
    
    public function processPayment($orderCode, $total_amount) {
        $vnp_TmnCode = "BKW22DPU"; 
        $vnp_HashSecret = "BGTKQK8L785CZKUM9HSLX5EUX70RQAIN"; 
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html"; 
        $vnp_Returnurl = "http://localhost:8000/thank-you"; 
    
        $vnp_TxnRef = time(); 
        $vnp_OrderInfo = "Thanh toán khóa học";
        $vnp_OrderType = "billpayment";
        $vnp_Amount = $total_amount * 100;  
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

    public function getOrderByUserId() {
        if (!isset($_SESSION['user']['id'])) {
            echo "<script>alert('Vui lòng đăng nhập để xem khóa học đã mua!');</script>";
            echo "<script>window.location.href = '/login';</script>";
            exit;
        }
    
        $user_id = $_SESSION['user']['id'];
        $orders = $this->orderModel->getOrderByUserId($user_id);
        
        renderViewUser("view/users/orderList.php", compact('orders'), "Order List");
    }
    
    
}