<?php
require_once "model/UserModel.php";
require_once "view/helpers.php";
require_once __DIR__ . '/../vendor/autoload.php'; // Load Composer autoload


use Google\Client;
use Google\Service\Oauth2;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;


class UserController {
    private $userModel;
    private $googleClient;
    public function __construct() {
        $this->userModel = new UserModel();
    }
    

    public function index() {
        $users = $this->userModel->getAllUsers();
        //compact: gom bien dien thanh array
        renderViewUser("view/users/home.php", compact('users'), "User List");
    }

    public function show() {
        $users = $this->userModel->getAllUsers();
        //compact: gom bien dien thanh array
        renderViewUser("view/users/about.php", compact('users'), "User List");
    }

    public function courses() {
        $users = $this->userModel->getAllUsers();
        //compact: gom bien dien thanh array
        renderViewUser("view/users/courses.php", compact('users'), "User List");
    }

    public function trainers() {
        $users = $this->userModel->getAllUsers();
        //compact: gom bien dien thanh array
        renderViewUser("view/users/trainers.php", compact('users'), "User List");
    }

    public function events() {
        $users = $this->userModel->getAllUsers();
        //compact: gom bien dien thanh array
        renderViewUser("view/users/events.php", compact('users'), "User List");
    }

    public function pricing() {
        $users = $this->userModel->getAllUsers();
        //compact: gom bien dien thanh array
        renderViewUser("view/users/pricing.php", compact('users'), "User List");
    }

    public function contact() {
        $users = $this->userModel->getAllUsers();
        //compact: gom bien dien thanh array
        renderViewUser("view/users/contact.php", compact('users'), "User List");
    }

    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");  
            exit();
        }
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        if (!$user) {
            die("User not found");
        }
    
        renderViewUser("view/users/profile.php", compact('user'), "User Profile");
    }
    
    public function updateProfile() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");  
            exit();
        }

        $user = $this->userModel->getUserById($_SESSION['user_id']);
        if (!$user) {
            die("User not found");
        }
        
        $data = $_POST;
        $data['image'] = $user['image'];
        
        if (!preg_match("/^[a-zA-ZÀ-Ỹà-ỹ\s]+$/u", $data['name'])) {
            $_SESSION['error'] = "Tên không được chứa kí tự đặc biệt hoặc số !";
            header("Location: /profile");
            exit();
        }
    
        if (!empty($data['phone'])) { 
            if (!preg_match("/^(0|\+84)(3[2-9]|5[2689]|7[0-9]|8[1-9]|9[0-9])[0-9]{7}$/", $data['phone'])) {
                $_SESSION['error'] = "Số điện thoại không hợp lệ!";
                header("Location: /profile");
                exit();
            }
        } else {
            $data['phone'] = $user['phone'];
        }

        if (isset($_FILES['avatar']) && $_FILES['avatar']['size'] > 0) {
            $image = $_FILES['avatar'];
            $uploadDir = __DIR__ . "/../uploads/avatar/";  
            $imageName = time() . '-' . basename($image['name']);
            $uploadPath = $uploadDir . $imageName;
        
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); 
            }
        
            // Kiểm tra và di chuyển file
            if (move_uploaded_file($image['tmp_name'], $uploadPath)) {
                $data['image'] = "avatar/" . $imageName;  
            } else {
                $_SESSION['error'] = "Lỗi khi tải ảnh lên!";
                header("Location: /profile");
                exit();
            }
        }
        
        if ($this->userModel->updateUser($user['id'], $data)) {
            $_SESSION['success'] = "Cập nhật hồ sơ thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại.";
        }

        header("Location: /profile");
        exit();
    }

    public function updatePassword() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");  
            exit();
        }

        $user = $this->userModel->getUserById($_SESSION['user_id']);
        if (!$user) {
            die("User not found");
        }

        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if (!password_verify($currentPassword, $user['password'])) {
            $_SESSION['error'] = "Mật khẩu cũ không chính xác!";
            header("Location: /profile");
            exit();
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = "Mật khẩu mới không khớp!";
            header("Location: /profile");
            exit();
        }
        
        if($currentPassword == $newPassword){
            $_SESSION['error'] = "Mật khẩu mới không được trùng với mật khẩu cũ!";
            header("Location: /profile");
            exit();
        }

        if(strlen($newPassword) < 6){
            $_SESSION['error'] = "Mật khẩu mới phải có ít nhất 6 ký tự!";
            header("Location: /profile");
            exit();
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        if ($this->userModel->updatePassword($user['id'], $hashedPassword)) {
            $_SESSION['success'] = "Đổi mật khẩu thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại.";
        }

        header("Location: /profile");
        exit();
    }
}