<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Load biến môi trường từ file .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once __DIR__ . '/vendor/autoload.php';
require_once "router/Router.php";
require_once "middleware.php";
require_once "controller/AuthController.php";
require_once "controller/CategoryController.php";
require_once "controller/SubcategoryController.php";
require_once "controller/UserController.php";
require_once "controller/CourseController.php";
require_once "controller/QuizzeController.php";
require_once "controller/QuizQuestionController.php";
require_once "controller/QuizAnswerController.php";
require_once "controller/SectionController.php";
require_once "controller/LessonController.php";
require_once "controller/CouponController.php";
require_once "controller/CheckoutController.php";
require_once "controller/FavouriteController.php";

require_once "router/Router.php";
require_once "middleware.php";


$router = new Router();
$authController = new AuthController();
$categoryController = new CategoryController();
$subcategoryController = new SubcategoryController();
$userController = new UserController();
$courseController = new CourseController();
$adminQuizzeController = new AdminQuizzeController();
$AdminQuiQuestionController = new AdminQuiQuestionController();
$AdminQuizAnswerController = new AdminQuizAnswerController();
$sectionController = new SectionController();
$lessonController = new LessonController();
$couponController = new CouponController();
$checkoutController = new CheckoutController();
$favouriteController = new FavouriteController();


// $router->addMiddleware('logRequest');

$router->addRoute("/", [$courseController, 'home']);

//auth
$router->addRoute("/login", [$authController, "login"]);

$router->addRoute("/logout", [$authController, "logout"]);
$router->addRoute("/register", [$authController, "register"]);
$router->addRoute("/google/login", [$authController, "googleLogin"]);
$router->addRoute("/google/callback", [$authController, "googleCallback"]);
$router->addRoute("/forgot_password", [$authController, "forgotPassword"]);
$router->addRoute("/reset_password", [$authController, "resetPassword"]);


//users
$router->addRoute("/users", [$userController, "index"]);

$router->addRoute("/users/create", [$userController, "create"], ['isUser']);
$router->addRoute("/about", [$userController, "show"], ['isUser']);
$router->addRoute("/course", [$userController, "courses"]);
$router->addRoute("/trainers", [$userController, "trainers"], ['isUser']);
$router->addRoute("/events", [$userController, "events"]);
$router->addRoute("/pricing", [$userController, "pricing"]);
$router->addRoute("/contact", [$userController, "contact"]);
$router->addRoute("/profile", [$userController, "profile"]);


//Categories
$router->addRoute("/admin/categories", [$categoryController, "index"], ['isAdmin']);
$router->addRoute("/admin/categories/create", [$categoryController, "create"], ['isAdmin']);
$router->addRoute("/admin/categories/edit/{id}", [$categoryController, "edit"], ['isAdmin']);
$router->addRoute("/admin/categories/delete{id}", [$categoryController, "delete"], ['isAdmin']);


//Subcategories

$router->addRoute("/admin/subcategories", [$subcategoryController, "index"]); // Danh sách subcategories
$router->addRoute("/admin/subcategories/create", [$subcategoryController, "create"]); // Thêm mới
$router->addRoute("/admin/subcategories/edit/{id}", [$subcategoryController, "edit"]); // Chỉnh sửa
$router->addRoute("/admin/subcategories/delete/{id}", [$subcategoryController, "delete"]); // Xóa

// Course routes
$router->addRoute("/admin/courses", [$courseController, "index"], ['isAdmin']);
$router->addRoute("/admin/courses/create", [$courseController, "createForm"], ['isAdmin']);
$router->addRoute("/admin/courses/store", [$courseController, "store"], ['isAdmin']);
$router->addRoute("/admin/courses/edit/{id}", [$courseController, "editForm"], ['isAdmin']);
$router->addRoute("/admin/courses/update/{id}", [$courseController, "update"], ['isAdmin']);
$router->addRoute("/admin/courses/delete/{id}", [$courseController, "destroy"], ['isAdmin']);



// Favourite Courses
$router->addRoute("/favourite/add", [$favouriteController, "addFavourite"], ['isUser']);
$router->addRoute("/favourite/remove", [$favouriteController, "removeFavourite"], ['isUser']);
$router->addRoute("/", [$favouriteController, "index"], ['isUser']);


//Order List
$router->addRoute("/orderList", [$checkoutController, "getOrderByUserId"], ['isUser']);




// Section routes
$router->addRoute("/admin/sections", [$sectionController, "index"], ['isAdmin']);
$router->addRoute("/admin/sections/create", [$sectionController, "createForm"], ['isAdmin']);
$router->addRoute("/admin/sections/store", [$sectionController, "store"], ['isAdmin']);
$router->addRoute("/admin/sections/edit/{id}", [$sectionController, "editForm"], ['isAdmin']);
$router->addRoute("/admin/sections/update/{id}", [$sectionController, "update"], ['isAdmin']);
$router->addRoute("/admin/sections/delete/{id}", [$sectionController, "destroy"], ['isAdmin']);

// Lesson routes
$router->addRoute("/admin/lessons", [$lessonController, "index"], ['isAdmin']);
$router->addRoute("/admin/lessons/create", [$lessonController, "createForm"], ['isAdmin']);
$router->addRoute("/admin/lessons/store", [$lessonController, "store"], ['isAdmin']);
$router->addRoute("/admin/lessons/edit/{id}", [$lessonController, "editForm"], ['isAdmin']);
$router->addRoute("/admin/lessons/update/{id}", [$lessonController, "update"], ['isAdmin']);
$router->addRoute("/admin/lessons/delete/{id}", [$lessonController, "destroy"], ['isAdmin']);

// bài kiểm tra
$router->addRoute("/admin/quizzes", [$adminQuizzeController, "index"]);
$router->addRoute("/admin/quizzes/create", [$adminQuizzeController, "createQuizze"]);
$router->addRoute("/admin/quizzes/delete/{id}", [$adminQuizzeController, "deleteQuizze"]);
$router->addRoute("/admin/quizzes/update/{id}", [$adminQuizzeController, "updateQuizze"]);

// câu hỏi 
$router->addRoute("/admin/quizQuests", [$AdminQuiQuestionController, "index"]);
$router->addRoute("/admin/quizQuests/create", [$AdminQuiQuestionController, "create"]);
$router->addRoute("/admin/quizQuests/delete/{id}", [$AdminQuiQuestionController, "delete"]);
$router->addRoute("/admin/quizQuests/update/{id}", [$AdminQuiQuestionController, "update"]);

// câu trl 
$router->addRoute("/quizAnswers", [$AdminQuizAnswerController, "index"]);
$router->addRoute("/admin/quizAnswers/create", [$AdminQuizAnswerController, "create"]);
$router->addRoute("/admin/quizAnswers/delete/{id}", [$AdminQuizAnswerController, "delete"]);
$router->addRoute("/admin/quizAnswers/update/{id}", [$AdminQuizAnswerController, "update"]);

//show course user
$router->addRoute("/courses/show/{course_id}", [$courseController, "show"]);
$router->addRoute("/courses/learning/{course_id}", [$courseController, "detailCourse"],['isUser']);
// câu trl 
// $router->addRoute("/course/{course_id}/sessions", [$sessionController, "home"]);  // Route cho SessionController
// $router->addRoute("/admin/sections/create", [$sessionController, "create"]);
// $router->addRoute("/admin/sections/delete/{id}", [$sessionController, "delete"]);
// $router->addRoute("/admin/sections/update/{id}", [$sessionController, "update"]);

// bài học 
// Route cho LessionController để hiển thị thông tin bài học cụ thể
$router->addRoute("/lesson/{lesson_id}", [$lessonController, "home"]);

$router->addRoute("/admin/coupons", [$couponController, "index"]);
$router->addRoute("/admin/coupons/create", [$couponController, "create"]);
$router->addRoute("/admin/coupons/edit/{id}", [$couponController, "edit"]);
$router->addRoute("/admin/coupons/update/{id}", [$couponController, "update"]);
$router->addRoute("/admin/coupons/delete/{id}", [$couponController, "delete"]);


// Checkout routes
$router->addRoute("/checkout", [$checkoutController, "checkout"]);



$router->addRoute("/thank-you", function() {
    include __DIR__ . "/view/thank-you.php";

});


$router->dispatch();
?>