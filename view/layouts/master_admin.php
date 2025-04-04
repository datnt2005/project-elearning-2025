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
    <title><?= $title ?? "E-Learning Admin" ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <style>
        :root {
            --primary-color: #dc3545;
            --primary-hover: #bb2d3b;
            --secondary-color: #6c757d;
            --light-bg: #f8f9fa;
            --dark-bg: #343a40;
            --primary-btn: #dc3545;
            --border-color: rgba(255,255,255,.1);
        }

        .main-sidebar {
            background: var(--dark-bg);
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }

        .brand-link {
            border-bottom: 1px solid var(--border-color);
            padding: 0.8rem 1rem;
        }

        .brand-text {
            color: var(--primary-color) !important;
            font-weight: bold !important;
        }

        .user-panel {
            border-bottom: 1px solid var(--border-color);
        }

        .user-panel .image {
            padding-left: 0.8rem;
        }

        .user-panel .info {
            padding: 0 1rem;
        }

        .user-panel img {
            border: 2px solid var(--primary-color);
        }

        .nav-sidebar .nav-link {
            color: #fff !important;
        }

        .nav-sidebar .nav-link:hover,
        .nav-sidebar .nav-link.active {
            background: var(--primary-color) !important;
            color: #fff !important;
        }

        .nav-treeview .nav-link {
            padding-left: 2.5rem !important;
        }

        .content-wrapper {
            background: #f4f6f9;
        }

        .navbar-nav.ml-auto {
            margin-left: auto !important;
        }

        .nav-link {
            color: #6c757d;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: var(--primary-btn);
        }

        @media (max-width: 768px) {
            .navbar-nav.ml-auto {
                margin-right: 0.5rem;
            }
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/" role="button">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="/admin" class="brand-link text-center">
                <span class="brand-text">Admin Panel</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- User Panel -->
                <?php if (isset($_SESSION['loggedIn']) && $_SESSION['user_role'] === 'admin'): ?>
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="/uploads/<?php echo $_SESSION['user_image'] ?? 'avatar/default-avatar.png'; ?>" 
                             class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <span class="d-block text-white"><?= $_SESSION['user_name'] ?? 'Admin' ?></span>
                        <small class="text-muted">Administrator</small>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="/admin/reports" class="nav-link">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <!-- Courses Menu -->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-book-open"></i>
                                <p>
                                    Khóa học
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="/admin/courses" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách khóa học</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/admin/sections" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Phần bài học</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/admin/lessons" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Bài học</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Posts Menu -->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-newspaper"></i>
                                <p>
                                    Bài viết
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="/admin/postCategory" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh mục bài viết</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/admin/post" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Bài viết</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Other Menu Items -->
                        <li class="nav-item">
                            <a href="/admin/categories" class="nav-link">
                                <i class="nav-icon fas fa-th-list"></i>
                                <p>Danh mục</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/subcategories" class="nav-link">
                                <i class="nav-icon fas fa-list-ul"></i>
                                <p>Danh mục phụ</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/quizzes" class="nav-link">
                                <i class="nav-icon fas fa-puzzle-piece"></i>
                                <p>Bài kiểm tra</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/quizQuests" class="nav-link">
                                <i class="nav-icon fas fa-question-circle"></i>
                                <p>Câu hỏi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/quizAnswers" class="nav-link">
                                <i class="nav-icon fas fa-check-circle"></i>
                                <p>Câu trả lời</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/coupons" class="nav-link">
                                <i class="nav-icon fas fa-tag"></i>
                                <p>Mã giảm giá</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/orders" class="nav-link">
                                <i class="nav-icon fas fa-ticket"></i>
                                <p>Đơn hàng</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/users" class="nav-link">
                                <i class="nav-icon fas fa-ticket"></i>
                                <p>Người dùng</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/reviews" class="nav-link">
                                <i class="nav-icon fas fa-comment"></i>
                                <p>Bình luận</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/logout" class="nav-link">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>Đăng xuất</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content -->
            <section class="content">
                <div class="container-fluid pt-4">
                    <?= $content ?>
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2025 E-Learning.</strong>
            All rights reserved.
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>