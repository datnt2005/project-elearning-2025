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
require_once "controller/OrderController.php";
require_once "controller/FavouriteController.php";
require_once "controller/ReviewController.php";
require_once "controller/ReportController.php";
require_once "controller/PostCategoryController.php";
require_once "controller/PostController.php";
require_once "controller/CommentPostController.php";
require_once "controller/NotesController.php";

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
$reviewController = new ReviewController();
$orderController = new OrderController();
$reportController = new ReportController(); 
$postCategoryController = new PostCategoryController();
$postController = new PostController();
$commentPostController = new CommentPostController();
$notesController = new NotesController();


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
$router->addRoute("/profile/update", [$userController, "updateProfile"]);    
$router->addRoute("/profile/update-password", [$userController, "updatePassword"]);

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
$router->addRoute("/admin/quizAnswers", [$AdminQuizAnswerController, "index"]);
$router->addRoute("/admin/quizAnswers/create", [$AdminQuizAnswerController, "create"]);
$router->addRoute("/admin/quizAnswers/delete/{id}", [$AdminQuizAnswerController, "delete"]);
$router->addRoute("/admin/quizAnswers/update/{id}", [$AdminQuizAnswerController, "update"]);
$router->addRoute("/quizzes/section/{section_id}", [$adminQuizzeController,"show"]);
//show course user
$router->addRoute("/courses/show/{course_id}", [$courseController, "show"]);
$router->addRoute("/courses/learning/{course_id}", [$courseController, "detailCourse"],['isUser']);

$router->addRoute("/courses/review/add", [$reviewController, "addReview"], ['isUser']);
$router->addRoute("/courses/review/get", [$reviewController, "getReviews"]);
$router->addRoute("/courses/review/update", [$reviewController, "editReview"], ['isUser']);
$router->addRoute("/courses/review/delete", [$reviewController, "deleteReview"], ['isUser']);
$router->addRoute("/courses/review/like", [$reviewController, "toggleLikeReview"], ['isUser']);

$router->addRoute("/courses/review/reply", [$reviewController, "replyReview"], ['isAdmin']);




$router->addRoute("/update_progress", [$courseController, "updateProgress"], ['isUser']);
$router->addRoute("/calculate_progress", [$courseController, "calculateProgress"], ['isUser']);
$router->addRoute("/update_enrollment", [$courseController, "updateEnrollment"], ['isUser']);
$router->addRoute("/certificate", [$courseController, "showCertificate"], ['isUser']);
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
$router->addRoute('/apply-coupon', [$couponController, 'applyCoupon'], ['isUser']);


// Checkout routes
$router->addRoute("/checkout", [$checkoutController, "checkout"]);
$router->addRoute("/checkout/vnpay-return", [$checkoutController, "vnpayReturn"]);
$router->addRoute("/orderList", [$checkoutController, "getOrderByUserId"], ['isUser']);

// Post category
$router->addRoute("/admin/postCategory", [$postCategoryController, "index"]);
$router->addRoute("/admin/postCategory/create", [$postCategoryController, "create"]);
$router->addRoute("/admin/postCategory/edit/{id}", [$postCategoryController, "edit"]);
$router->addRoute("/admin/postCategory/delete/{id}", [$postCategoryController, "delete"]);

// post
$router->addRoute("/admin/post", [$postController, "index"]);
$router->addRoute("/admin/post/create", [$postController, "create"]);
$router->addRoute("/admin/post/edit/{id}", [$postController, "edit"]);
$router->addRoute("/admin/post/delete/{id}", [$postController, "delete"]);

$router->addRoute("/posts", [$postController, "show"]);
$router->addRoute("/posts/detail/{id}", [$postController, "detail"]);

 

// comment

$router->addRoute("/add-comment", [$commentPostController, "addComment"]);
$router->addRoute("/get-comments/{post_id}", [$commentPostController, "getComments"]);
$router->addRoute("/delete-comment/{id}", [$commentPostController, "deleteComment"], 'DELETE');

$router->addRoute("/admin/reports", [$reportController, "index"], ['isAdmin']);
$router->addRoute("/admin/reports/completed-orders-by-date", [$reportController, "completedOrdersByDateChart"], ['isAdmin']);
$router->addRoute("/admin/reports/completed-orders-by-month", [$reportController, "completedOrdersByMonthChart"], ['isAdmin']);
$router->addRoute("/admin/reports/completed-orders-by-year", [$reportController, "completedOrdersByYearChart"], ['isAdmin']);
$router->addRoute("/admin/reports/completed-orders-summary", [$reportController, "getSummaryOrders"], ['isAdmin']);

$router->addRoute("/admin/reports/completed-orders-date", [$reportController, "showDateChartPage"], ['isAdmin']);
$router->addRoute("/admin/reports/completed-orders-month", [$reportController, "showMonthChartPage"], ['isAdmin']);
$router->addRoute("/admin/reports/completed-orders-year", [$reportController, "showYearChartPage"], ['isAdmin']);


$router->addRoute("/admin/reports/completed-orders-detail-date", [$reportController, "completedOrdersDetailByDate"], ['isAdmin']);
$router->addRoute("/admin/reports/completed-orders-detail-month", [$reportController, "completedOrdersDetailByMonth"], ['isAdmin']);
$router->addRoute("/admin/reports/completed-orders-detail-year", [$reportController, "completedOrdersDetailByYear"], ['isAdmin']);

$router->addRoute("/admin/orders", [$orderController, "index"], ['isAdmin']);
$router->addRoute("/admin/orders/edit/{id}", [$orderController, "edit"], ['isAdmin']);
$router->addRoute("/admin/orders/update/{id}", [$orderController, "update"], ['isAdmin']);
$router->addRoute("/admin/orders/delete/{id}", [$orderController, "delete"], ['isAdmin']);

$router->addRoute("/notes", [$notesController, "create"]);
$router->addRoute("/notes/get", [$notesController, "getNotesByCourse"], ['isUser']);
$router->addRoute("/notes/edit/{id}", [$notesController, "edit"], ['isUser']);
$router->addRoute("/notes/update", [$notesController, "update"], ['isUser']);
$router->addRoute("/notes/delete/{id}", [$notesController, "delete"], ['isUser']);



$router->addRoute("/thank-you", function() {
    include __DIR__ . "/view/thank-you.php";

});


$router->dispatch();
?>