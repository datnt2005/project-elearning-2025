<?php
require_once "model/CourseModel.php";
require_once "view/helpers.php";
require_once "model/FavouriteModel.php";
require_once "model/OrderModel.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class FavouriteController
{
    private $courseModel;
    private $favouriteModel;
    private $orderModel;

    public function __construct()
    {
        $this->courseModel = new Course();
        $this->favouriteModel = new FavouriteModel();
        $this->orderModel = new OrderModel();
    }

    public function index()
{
    $userId = $_SESSION['user']['id'] ?? null;

    $courses = $this->courseModel->getAllCourses();
    $favourites = $this->favouriteModel->getFavouritesByUserId($userId);
    $favourites = array_column($favourites, 'course_id'); // Chỉ lấy danh sách ID khóa học
    $favouriteList = $this->favouriteModel->getFavouritesByUserId($userId);
    $favouriteList = array_column($favouriteList, 'id');
    $orders = $this->orderModel->getOrderByUserId($userId);
    $orders = array_column($orders, 'course_id'); // Lấy danh sách ID khóa học đã mua
    

    

    

    renderViewUser("view/users/home.php", [
        "courses" => $courses,
        "favourites" => $favourites,
        "favouriteList" => $favouriteList,
        "orders" => $orders
    ]);         
}


    public function addFavourite()
    {
        header('Content-Type: application/json; charset=utf-8');

        if (!isset($_SESSION['user']['id'])) {
            echo json_encode(["status" => "error", "message" => "Bạn cần đăng nhập"]);
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $data = json_decode(file_get_contents("php://input"), true);
        $courseId = $data['course_id'] ?? null;

        if (!$courseId) {
            echo json_encode(["status" => "error", "message" => "Thiếu course_id"]);
            exit;
        }

        if ($this->favouriteModel->isFavourite($userId, $courseId)) {
            echo json_encode(["status" => "error", "message" => "Đã tồn tại"]);
            exit;
        }

        $this->favouriteModel->addFavourite($userId, $courseId);
        echo json_encode(["status" => "added", "course_id" => $courseId]);
        exit;
    }

    public function removeFavourite()
    {
        header('Content-Type: application/json; charset=utf-8');

        if (!isset($_SESSION['user']['id'])) {
            echo json_encode(["status" => "error", "message" => "Bạn cần đăng nhập"]);
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $courseId = json_decode(file_get_contents("php://input"), true)['course_id'] ?? null;

        if (!$courseId) {
            echo json_encode(["status" => "error", "message" => "Thiếu course_id"]);
            exit;
        }

        $this->favouriteModel->removeFavourite($userId, $courseId);
        echo json_encode(["status" => "removed", "course_id" => $courseId]);
        exit;
    }
}
