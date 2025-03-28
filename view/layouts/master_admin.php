<?php
if (!isset($_SESSION['loggedIn']) || $_SESSION['user_role'] !== 'admin') {
    echo "<div style='text-align: center; padding: 50px;'>
                <h2 style='color: red;'>Bạn không có quyền truy cập trang này</h2>
                <button onclick='window.history.back()' 
                    style='padding: 10px 20px; font-size: 16px; background: #f05123; color: white; border: none; border-radius: 5px; cursor: pointer;'>
                    Quay lại
                </button>
              </div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "Fashion.vn" ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            display: flex;
            background: rgb(255, 255, 255);
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: rgb(248, 248, 248);
            color: white;
            padding: 20px;
            position: fixed;
            transition: 0.3s;
        }

        .sidebar a {
            color: rgb(71, 164, 5);
            display: block;
            padding: 12px;
            margin: 10px 0;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: rgb(161, 231, 112);
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar.collapsed a span {
            display: none;
        }

        .content {
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 20px;
            transition: 0.3s;
        }

        .collapsed+.content {
            margin-left: 70px;
            width: calc(100% - 70px);
        }

        .card {
            border: none;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
            transition: 0.3s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .h3 {
            color: rgb(97, 188, 31);
        }

        .btn-list {
            background: rgb(97, 188, 31);
        }

        .btn-list:hover {
            background: rgb(161, 231, 112);
            color: black;
        }

        .dropdown {
            position: relative;
            display: block;
            width: 100%;
        }

        .dropdown-toggle {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        .dropdown-menu {
            display: none;
            background-color: rgb(233, 233, 233);
            width: 100%;
        }

        .dropdown-menu a {
            display: block;
            padding: 10px;
            color: green;
            text-decoration: none;
        }

        .dropdown-menu a:hover {
            background-color: rgb(206, 206, 206);
        }
    </style>
</head>

<body>

    <!-- Sidebar Menu -->
    <div class="sidebar" id="sidebar">
        <h3 class="text-center h3">Admin Panel</h3>
        <!-- <?php
        var_dump($_SESSION['user_role']);
        echo "role cua user la " . ($_SESSION['user_role'] ?? 'Not Set') . "<br>";
        ?> -->
        <?php if (isset($_SESSION['loggedIn']) && $_SESSION['user_role'] === 'admin'): ?>
            <div class="text-center mb-3">

                <img src="http://localhost:8000/uploads/<?php echo $_SESSION['user_image'] ?? 'avatar/default-avatar.png'; ?>" alt="User Avatar"
                    class="rounded-circle " width="60" height="60">

                <p class="mt-2 fw-bold text-dark"><?= $_SESSION['user_name'] ?? 'Admin' ?>(admin)</p>
            </div>
        <?php endif; ?>

        <i class="fas fa-bars"></i>
        </button>
        <i class="fas fa-bars"></i>
        </button>
        <a href="/admin/reports"><i class="fas fa-chart-line"></i> <span>Dashboard</span></a>
        <div class="dropdown">
            <a href="#" class="dropdown-toggle" onclick="toggleDropdown(event, 'courseDropdown')">
                <i class="fas fa-book-open"></i> <span>Khóa học</span>
            </a>
            <div class="dropdown-menu" id="courseDropdown">
                <a href="/admin/courses"><i class="fas fa-book"></i> <span>Danh sách khóa học</span></a>
                <a href="/admin/sections"><i class="fas fa-layer-group"></i> <span>Phần bài học</span></a>
                <a href="/admin/lessons"><i class="fas fa-play-circle"></i> <span>Bài học</span></a>
            </div>
        </div>

        <div class="dropdown">
            <a href="#" class="dropdown-toggle" onclick="toggleDropdown(event, 'postDropdown')">
                <i class="fas fa-book-open"></i> <span>Bài viết</span>
            </a>
            <div class="dropdown-menu" id="postDropdown">
                <a href="/admin/postCategory"><i class="fas fa-book"></i> <span>Danh mục bài viết</span></a>
                <a href="/admin/post"><i class="fas fa-play-circle"></i> <span>Bài viết</span></a>
            </div>
        </div>
        <a href="/admin/categories"><i class="fas fa-th-list"></i> <span>Danh mục</span></a>
        <a href="/admin/subcategories"><i class="fas fa-list-ul"></i> <span>Danh mục phụ</span></a>
        <a href="/admin/quizzes"><i class="fas fa-puzzle-piece"></i> <span>Bài kiểm tra</span></a>
        <a href="/admin/quizQuests"><i class="fas fa-question-circle"></i> <span>Câu hỏi</span></a>
        <a href="/admin/quizAnswers"><i class="fas fa-check-circle"></i> <span>Câu trả lời</span></a>

        <a href="/admin/coupons"><i class="fas fa-tag"></i> <span>Mã giảm giá</span></a>
        <a href="/admin/orders"><i class="fas fa-ticket"></i> <span>Đơn hàng</span"></a>
        <a href="/admin/user"><i class="fas fa-ticket"></i> <span>Người dùng</span></a>
        <a href="/admin/postCategory"><i class="fas fa-ticket"></i> <span>Bài viết</span></a>
        <a href="/logout"><i class="fas fa-sign-out-alt"></i> <span>Đăng xuất</span></a>

        

    </div>

    <!-- Nội dung chính -->
    <div class="content" id="content">


        <?= $content ?>

    </div>
    <!-- <main class="container my-4">
      
    </main> -->




</body>

</html>
<script>
    function toggleDropdown(event, dropdownId) {
        event.preventDefault();  
        let dropdown = document.getElementById(dropdownId);
        if (dropdown.style.display === "block") {
            dropdown.style.display = "none";
        } else {
            dropdown.style.display = "block";
        }
    }

       function toggleDropdown(event, dropdownId) {
        event.preventDefault();  
        let dropdown = document.getElementById(dropdownId);
        if (dropdown.style.display === "block") {
            dropdown.style.display = "none";
        } else {
            dropdown.style.display = "block";
        }
    }
</script>