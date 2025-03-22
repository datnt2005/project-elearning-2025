<?php
require_once "model/AuthModel.php";
require_once "view/helpers.php";
require_once __DIR__ . '/../vendor/autoload.php'; // Load Composer autoload

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use Google\Client;
use Google\Service\Oauth2;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;


class AuthController
{
    private $AuthModel;
    private $googleClient;

    public function __construct()
    {
        $this->AuthModel = new AuthModel();

        // Load biến môi trường từ .env
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        // Cấu hình Google Client
        $this->googleClient = new Client();
        $this->googleClient->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $this->googleClient->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $this->googleClient->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
    }
    public function index()
    {
        $users = $this->AuthModel->getAllUsers();
        //compact: gom bien dien thanh array
        renderViewAdmin("view/users/user_list.php", compact('users'), "User List");
    }

    public function profile()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $user = $this->AuthModel->getUserById($userId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $password_confirmation = trim($_POST['password_confirmation']);
            $image = $user['image'];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image = $this->uploadImage($_FILES['image']);
            }

            // Xử lý mật khẩu (không mã hóa)
            if (!empty($password)) {
                if ($password !== $password_confirmation) {
                    $_SESSION['error'] = "Mật khẩu không khớp!";
                    header("Location: /profile");
                    exit;
                }
            } else {
                $password = $user['password'];  
            }

            $this->AuthModel->updateUser($userId, $name, $email, $password, $image);

            // Cập nhật session
            $_SESSION['user'] = $this->AuthModel->getUserById($userId);
            $_SESSION['success'] = "Cập nhật thông tin thành công!";
            header("Location: /profile");
            exit;
        }

        renderViewUser("view/users/profile.php", compact('user'), "Cập nhật thông tin cá nhân");
    }


    public function dashboard()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }
        $user = $_SESSION['user'];
        if ($user['role'] === 'admin') {
            renderViewAdmin("view/layouts/master_admin.php", compact('user'), "Admin Dashboard");
        } else {
            renderViewUser("view/layouts/master_user.php", compact('user'), "User Dashboard");
        }
    }

    public function adminDashboard()
    {
        $users = $this->AuthModel->getAllUsers();
        //compact: gom bien dien thanh array
        renderViewAdmin("view/layouts/dashboard_admin.php", compact('users'), "User List");
    }


    public function adminUsers()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: /shop");
            exit;
        }
        $users = $this->AuthModel->getAllUsers();
        renderViewAdmin("view/admin/user_list.php", compact('users'), "User Management");
    }

    public function show($id)
    {
        $user = $this->AuthModel->getUserById($id);
        renderViewUser("view/users/user_detail.php", compact('user'), "User Detail");
    }

    public function edit($id)
    {
        // Lấy thông tin người dùng
        $user = $this->AuthModel->getUserById($id);
        if (!$user) {
            $_SESSION['error'] = "User not found.";
            header("Location: /users");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $image = $user['image'];

            // Kiểm tra upload ảnh
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image = $this->uploadImage($_FILES['image']);
            }


            // Cập nhật thông tin người dùng
            $this->AuthModel->editUser($id, $name, $email, $image);

            $_SESSION['success'] = "Cập nhật thông tin người dùng thành công!";
            header("Location: /users");
            exit;
        }

        renderViewUser("view/users/user_edit.php", compact('user'), "Edit User");
    }


    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $image = $this->uploadImage($_FILES['image']); // xử lí upload ảnh
            $role = $_POST['role'] ?? 'student';
            $status = $_POST['status'] ?? 'active';
            $this->AuthModel->createUser($name, $email, $password, $image, $role, $status);
            header("Location: /users");
        } else {
            renderViewAdmin("view/users/user_create.php", [], "Create User");
        }
    }

    public function register()
{
    $error1 = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if (!preg_match("/^[a-zA-ZÀ-Ỹà-ỹ\s]{3,50}$/u", $name)) {
            $error1 = "Tên không hợp lệ! Chỉ nhập chữ cái và ít nhất 3 ký tự.";
        }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error1 = "Email không hợp lệ!";
        }
        elseif (strlen($password) < 6) {
            $error1 = "Mật khẩu phải có ít nhất 6 ký tự!";
        }
        elseif ($password !== $confirm_password) {
            $error1 = "Mật khẩu nhập lại không trùng khớp!";
        }
        // Kiểm tra email đã tồn tại chưa
        elseif ($this->AuthModel->isEmailExists($email)) {
            $error1 = "Email đã được sử dụng!";
        } else {
            if ($this->AuthModel->register($name, $email, $password)) {
                $_SESSION['success_message1'] = "Đăng ký thành công! Hãy đăng nhập.";
            } else {
                $error1 = "Đăng ký thất bại. Email có thể đã được sử dụng.";
            }
        }
    }
    renderViewUser("view/auth/register.php", compact('error1'), "Register");
}

public function login()
{
    $error = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $user = $this->AuthModel->login($email, $password);
        if ($user) {
            $_SESSION['user'] = $user;
            $_SESSION['user_id'] = $user['id'];  
            $_SESSION['user_role'] = $user['role']; 
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_phone'] = $user['phone'];
            $_SESSION['success_message'] = "Đăng nhập thành công!";
            $_SESSION['loggedIn'] = true;
            $redirectUrl = ($user['role'] === 'admin') ? '/admin/categories' : '/login';
            header("Location: $redirectUrl");
            exit;
        } else {
            $error = "Email hoặc mật khẩu không chính xác.";
        }
    }
    renderViewUser("view/auth/login.php", compact('error'), "Login");
}


    public function logout()
    {
        session_destroy();
        header("Location: /login");
        exit;
    }

    private function uploadImage($file)
    {
        $uploadDir = 'uploads/'; //thư mục lưu ảnh
        $fileName = uniqid() . '.' . basename($file['name']); //tạo tên ảnh
        $targetFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            return $targetFilePath;
        }

        return null; //trả về null nếu upload thất bại
    }

    public function delete($id)
    {
        $user = $this->AuthModel->getUserById($id);
        if ($user) {
            if (!empty($user['image']) && file_exists($user['image'])) {
                unlink($user['image']); // Xóa ảnh khỏi thư mục uploads
            }

            $this->AuthModel->deleteUser($id);
            header("Location: /users");
            exit;
        } else {
            echo "User not found.";
        }
    }

    private function sendMail($to, $subject, $message)
    {
        $mail = new PHPMailer(true);

        try {
            // Cấu hình SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD']; // Mật khẩu ứng dụng
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['MAIL_PORT'];

            // Cấu hình email
            $mail->setFrom($_ENV['MAIL_USERNAME'], 'Hạt Ngon');
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = "<p>$message</p>";

            return $mail->send();
        } catch (Exception $e) {
            return false;
        }
    }

    public function forgotPassword()
    {
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];

            $user = $this->AuthModel->getUserByEmail($email);
            if ($user) {
                $otp = rand(100000, 999999);
                $this->AuthModel->saveOTP($email, $otp);

                if ($this->sendMail($email, "Reset Password OTP", "Your OTP is: $otp. This code is valid for 10 minutes.")) {
                    $success = "An OTP has been sent to your email.";
                } else {
                    $error = "Failed to send OTP. Please try again.";
                }

                header("Location: /reset_password");
            } else {
                $error = "No account found with this email.";
            }
        }

        renderViewUser("view/auth/forgot_Password.php", compact('error', 'success'), "Forgot Password");
    }

    public function resetPassword()
    {
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $otp = $_POST['otp'];
            $password = $_POST['password'];
            $passwordConfirm = $_POST['passwordConfirm'];

            $storedOTP = $this->AuthModel->getOTP($email);
            if ($storedOTP != $otp) {
                $error = "Invalid OTP. Please try again.";
            } elseif ($password !== $passwordConfirm) {
                $error = "Passwords do not match.";
            } else {
                $this->AuthModel->updatePassword($email, $password);
                $success = "Password reset successfully.";

                // Clear OTP from the database after successful reset
                $this->AuthModel->deleteOTP($email);
                session_destroy();
                header("Location: /login");
            }
        }

        renderViewUser("view/auth/reset_Password.php", compact('error', 'success'), "Reset Password");
    }

    public function googleLogin()
    {
        $loginUrl = $this->googleClient->createAuthUrl();
        header("Location: $loginUrl");
        exit;
    }

    public function googleCallback()
    {
        if (!isset($_GET['code'])) {
            header("Location: /login");
            exit;
        }

        try {
            // Lấy mã truy cập từ mã ủy quyền
            $token = $this->googleClient->fetchAccessTokenWithAuthCode($_GET['code']);

            if (isset($token['error'])) {
                throw new Exception('Google authentication failed: ' . $token['error_description']);
            }

            $this->googleClient->setAccessToken($token['access_token']);

            // Lấy thông tin người dùng từ Google
            $googleService = new Oauth2($this->googleClient);
            $googleUser = $googleService->userinfo->get();

            if (empty($googleUser->email) || empty($googleUser->name)) {
                throw new Exception('Google returned incomplete user information.');
            }

            $email = $googleUser->email;
            $name = $googleUser->name;

            // Kiểm tra người dùng trong cơ sở dữ liệu
            $user = $this->AuthModel->getUserByEmail($email);
            if (!$user) {
                // Nếu chưa có tài khoản, tạo mới
                $password = uniqid(); // Tạo mật khẩu ngẫu nhiên
                $this->AuthModel->createUser($name, $email, $password, '', 'user');
                $user = $this->AuthModel->getUserByEmail($email);
            }

            // Lưu thông tin người dùng vào session
            $_SESSION['user'] = $user;
            header("Location: /");
            exit;
        } catch (Exception $e) {
            error_log("Google Login Error: " . $e->getMessage());
            $_SESSION['error_message'] = "An error occurred during Google login. Please try again.";
        }

        header("Location: /login");
        exit;
    }
}
