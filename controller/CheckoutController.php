<?php

require_once "model/OrderModel.php";
require_once "model/CourseModel.php";
require_once "model/CouponModel.php";
require_once "model/UserModel.php";
require_once "view/helpers.php";
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PayPal\Checkout\Orders\OrdersCreateRequest;
use PayPal\Checkout\Orders\OrdersCaptureRequest;
use PayPal\Http\PayPalHttpClient;
use PayPal\Environment\SandboxEnvironment; // Đúng class cho sandbox environment
use PayPal\Environment\LiveEnvironment; // Đúng class cho live environment

class CheckoutController
{
    private $orderModel;
    private $courseModel;
    private $couponModel;
    private $userModel;
    private $paypalClient;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->courseModel = new Course();
        $this->couponModel = new CouponModel();
        $this->userModel = new UserModel();
    }




    public function checkout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
                echo "<script>alert('Vui lòng đăng nhập để tiếp tục thanh toán.');</script>";
                echo "<script>window.location.href = '/login';</script>";
                exit;
            }

            $user_id = $_SESSION['user']['id'];
            $course_id = $_POST['course_id'];

            // Kiểm tra nếu người dùng đã có đơn hàng với khóa học này
            $existingOrder = $this->orderModel->getOrderByUserAndCourse($user_id, $course_id);
            if ($existingOrder && is_array($existingOrder) && $existingOrder['status'] == 'completed') {
                echo $this->generatePopup("Bạn đã hoàn tất việc mua khóa học này rồi.");
                exit;
            }

            $total_amount = $_POST['amount'];
            $status = 'pending';
            $payment_status = 'pending';

            $order_id = $this->orderModel->createOrder(
                $user_id,
                $course_id,
                $total_amount, // Giá gốc, không áp dụng mã giảm giá
                $total_amount, // Giá sau khi giảm (không thay đổi)
                null, // Không có mã giảm giá
                0, // Không có giảm giá
                $status,
                $_POST['payment_method'],
                $payment_status
            );

            if (!$order_id) {
                $_SESSION['error_message'] = "Không thể tạo đơn hàng!";
                header("Location: /checkout");
                exit;
            }
            // Xử lý thanh toán
            $this->processPayment($order_id, $total_amount);

            // Gửi email xác nhận sau khi thanh toán thành công
            $this->sendConfirmationEmail($user_id, $course_id, $order_code, $total_amount);
        }
    }


    public function sendConfirmationEmail($user_id, $course_id, $order_code, $total_amount)
    {
        // Lấy thông tin người dùng và khóa học
        $user = $this->userModel->getUserById($user_id);  // Lấy thông tin người dùng từ DB
        $course = $this->courseModel->getCourseById($course_id);
        $order = $this->orderModel->getOrderByOrderCode($order_code);

        // Lấy email từ thông tin người dùng
        $user_email = $user['email'];

        // Kiểm tra email hợp lệ
        if (empty($user_email) || !filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            echo "Địa chỉ email không hợp lệ.";
            exit;
        }

        // Tạo nội dung email
        $subject = "Xác nhận đơn hàng khóa học: " . $course['title'];
        $body = "
            <h1>Cảm ơn bạn đã đăng ký khóa học " . $course['title'] . "</h1>
            <p>Chúc môn, " . $user['name'] . ".</p>
            <p>mã đơn hàng: " . $order['order_code'] . "</p>
            <p>Chúng tôi đã nhận được đơn hàng của bạn với tổng số tiền là: " . number_format($total_amount, 0, ',', '.') . " VND.</p>
            <p>Vui lòng kiểm tra lại đơn hàng của bạn trong tài khoản.</p>
            <p>Chúc bạn học tốt!</p>
        ";

        // Gửi email qua PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Thay bằng máy chủ SMTP của bạn
            $mail->SMTPAuth = true;
            $mail->Username = 'adsasiki777@gmail.com';  // Thay bằng email của bạn
            $mail->Password = 'akhh ibru frvw xfza';  // Thay bằng mật khẩu email của bạn
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('admi@gmail.com', 'Hệ thống khóa học');
            $mail->addAddress($user_email, $user['name']);  // Sử dụng email lấy từ người dùng
            $mail->CharSet = "UTF-8";
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
        } catch (Exception $e) {
            echo "Không thể gửi email. Lỗi: {$mail->ErrorInfo}";
        }
    }

    

    

    public function processPayment($order_id, $total_amount)
    {
        $vnp_TmnCode = "PN770RI5";
        $vnp_HashSecret = "3PBZJEL9XBWPCANGTFDHDSQEHT1UILWO";
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://localhost:8000/checkout/vnpay-return";


        $vnp_TxnRef = $order_id;
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

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnp_SecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnp_SecureHash;
            
        }

        header('Location: ' . $vnp_Url);
        exit();
    }
    public function vnpayReturn()
    {
        $vnp_HashSecret = "BGTKQK8L785CZKUM9HSLX5EUX70RQAIN";
        $inputData = [];

        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        ksort($inputData);

        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($key != "vnp_SecureHash" && $key != "vnp_SecureHashType") {
                $hashData .= ($hashData ? '&' : '') . urlencode($key) . '=' . urlencode($value);
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $vnp_SecureHash = $_GET['vnp_SecureHash'];

        if ($secureHash !== $vnp_SecureHash) {
            die("Lỗi bảo mật: Hash không khớp!");
        }

        if ($_GET['vnp_ResponseCode'] == '00') {
            $order_id = (int) $_GET['vnp_TxnRef'];

            $order = $this->orderModel->getOrderById($order_id);
            if (!$order) {
                die("Không tìm thấy đơn hàng trong DB.");
            }

            $result1 = $this->orderModel->updateOrderStatus($order_id, 'completed');
            $result2 = $this->orderModel->updateOrderPaymentStatus($order_id, 'completed');

            if ($result1 && $result2) {
                echo "Cập nhật đơn hàng thành công!<br>";

                // Lấy thông tin đơn hàng để gửi mail
                $user_id = $order['user_id'];
                $course_id = $order['course_id'];
                $order_code = $order['order_code'];
                $total_amount = $order['total_amount'];

                $this->sendConfirmationEmail($user_id, $course_id, $order_code, $total_amount);

                header("Location: /");
                exit;
            } else {
                echo "Lỗi khi cập nhật đơn hàng!";
            }
        }
    }

    


    // Phương thức tạo popup
    public function generatePopup($message)
    {
        return "
        <div id='popupModal' class='popup-modal'>
            <div class='popup-content'>
                <span id='closePopup' class='close'>&times;</span>
                <p>{$message}</p>
                <a href='/' class='popup-button'>Quay lại trang chủ</a>
            </div>
        </div>
        <style>
            .popup-modal {
                display: block;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.7);
                z-index: 9999;
                text-align: center;
            }
            .popup-content {
                background-color: white;
                border-radius: 8px;
                padding: 20px;
                position: relative;
                top: 50%;
                transform: translateY(-50%);
                width: 50%;
                margin: 0 auto;
            }
            .close {
                position: absolute;
                top: 10px;
                right: 20px;
                font-size: 30px;
                cursor: pointer;
            }
            .popup-button {
                background-color: #4CAF50;
                color: white;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 5px;
            }
            .popup-button:hover {
                background-color: #45a049;
            }
        </style>
        <script>
            document.getElementById('closePopup').onclick = function() {
                document.getElementById('popupModal').style.display = 'none';
                window.location.href = '/';
            };
        </script>
        ";
    }

    public function createPayment()
    {
        if (!isset($_SESSION['user'])) {
            echo json_encode(['status' => 'error', 'message' => 'Bạn cần đăng nhập để thanh toán.']);
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $amount = (int)$_POST['amount'];
        $course_id = $_POST['course_id'];

        // ✅ Thông tin Momo Sandbox (Test)
        $partnerCode = "MOMOBKUN20180529";
        $accessKey = "klm05TvNBzhg7h7j";
        $secretKey = "at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa";
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        // ✅ URL không để trống
        $redirectUrl = "http://localhost:8000/payment/momo/result";
        $ipnUrl = "http://localhost:8000/payment/momo/ipn-handler";

        // ✅ Tạo order_code trước khi tạo đơn hàng
        $order_code = 'ORD' . time() . "_" . $user_id;

        // ✅ Tạo requestId
        $requestId = time() . "_" . rand(1000, 9999);
        $requestType = "captureWallet";
        $extraData = "";
        $orderInfo = "Thanh toán khóa học #$course_id";

        // ✅ Tạo rawHash đúng thứ tự chuẩn của Momo
        $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$order_code&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";

        // ✅ Tạo chữ ký đúng
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        $expireTime = time() + 1800;

        // ✅ Lưu thông tin đơn hàng vào database bằng hàm createOrder trong model
        $order = $this->orderModel->createOrderMomo(
            $user_id,
            $course_id,
            $order_code,
            $amount,
            $amount,
            null,
            0,
            "pending",
            "momo",
            "pending"
        );

        // Kiểm tra xem đơn hàng có được tạo thành công không
        if (!$order) {
            echo json_encode(["status" => "error", "message" => "Lỗi khi tạo đơn hàng"]);
            exit;
        }

        // ✅ Gửi dữ liệu đến Momo
        $requestData = [
            'partnerCode' => $partnerCode,
            'accessKey' => $accessKey,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $order_code,  // Sử dụng order_code đã lấy từ database
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature,
            'lang' => 'vi',
            'expireTime' => $expireTime
        ];

        // Gửi request đến Momo
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

        $response = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($response, true);

        // ✅ Kiểm tra phản hồi từ Momo
        if (!isset($result['payUrl'])) {
            echo json_encode(["status" => "error", "message" => "Thanh toán Momo thất bại", "error" => $result]);
            exit;
        }

        // ✅ Chuyển hướng đến trang thanh toán Momo (dùng tài khoản test)
        header("Location: " . $result['payUrl']);
        exit;
    }


    public function paymentResult()
    {
        // Kiểm tra kết quả trả về từ Momo
        if (!isset($_GET['resultCode'])) {
            echo "Không tìm thấy kết quả thanh toán.";
            exit;
        }

        $resultCode = $_GET['resultCode'];

        if ($resultCode == 0) {
            // ✅ Thanh toán thành công
            $order_code = $_GET['orderId'];  // Lấy orderId từ URL trả về của Momo

            $order = $this->orderModel->getOrderByOrderCode($order_code); // Tìm kiếm trong database theo order_code

            if (!$order) {
                echo "Không tìm thấy đơn hàng với mã đơn hàng: $order_code";
                exit;
            }

            // Kiểm tra trạng thái đơn hàng đã được xử lý chưa
            if ($order['status'] == 'completed') {
                echo "Đơn hàng đã được xử lý rồi.";
                exit;
            }

            $result1 = $this->orderModel->updateOrderMomoStatus($order_code, 'completed');  // Cập nhật trạng thái đơn hàng
            $result2 = $this->orderModel->updateOrderPaymentMomoStatus($order_code, 'completed');  // Cập nhật trạng thái thanh toán

            if ($result1 && $result2) {
                // Gửi email xác nhận thanh toán
                $user_id = $order['user_id'];
                $course_id = $order['course_id'];
                $total_amount = $order['total_amount'];

                // Gửi email thông báo thanh toán thành công
                $this->sendConfirmationEmail($user_id, $course_id, $order_code, $total_amount);

                // Thông báo thanh toán thành công
                echo "<script>alert('Thanh toán thành công!'); window.location.href='/';</script>";
            } else {
                echo "Lỗi khi cập nhật trạng thái đơn hàng.";
            }
        } else {
            echo "<script>alert('Thanh toán thất bại!'); window.location.href='/checkout';</script>";
        }
    }

    public function getOrderByUserId()
    {

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
