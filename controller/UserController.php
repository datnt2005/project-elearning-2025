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
    

    // public function index() {
    //     $users = $this->userModel->getAllUsers();
    //     //compact: gom bien dien thanh array
    //     renderViewAdmin("view/users/home.php", compact('users'), "User List");
    // }

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
            header("Location: /login.php");  
            exit();
        }
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        if (!$user) {
            die("User not found");
        }
    
        renderViewUser("view/users/profile.php", compact('user'), "User Profile");
    }
    
}