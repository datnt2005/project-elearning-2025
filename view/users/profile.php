<?php
$loggedIn = isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;
$userName = $loggedIn ? $_SESSION['user_name'] : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>P6 - Hồ Sơ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        @media (max-width: 1024px) {
            .navbar-sidebar {
                display: none;
            }
            .main-content {
                margin-left: 0 !important;
            }
        }
        @media (max-width: 640px) {
            .search-input {
                display: none;
            }
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm fixed w-full top-0 z-10" x-data="{ menuOpen: false }">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-[#f05123]">P6</a>
                    <span class="ml-2 text-gray-700 hidden lg:inline">Học online không khó, chỉ sợ không có tiền🙂</span>
                </div>
                <div class="flex items-center">
                    <button class="text-gray-700 focus:outline-none lg:hidden" @click="menuOpen = !menuOpen">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="hidden lg:flex lg:items-center" 
                         :class="{ 'block absolute top-16 left-0 w-full bg-white shadow-lg p-4': menuOpen, 'hidden lg:flex lg:static lg:w-auto lg:bg-transparent lg:shadow-none lg:p-0': !menuOpen }">
                        <div class="relative mx-0 lg:mx-4 w-full lg:w-[420px] search-input">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <i class="fas fa-search text-gray-400"></i>
                            </span>
                            <input type="text" placeholder="Tìm kiếm khóa học, bài viết, video, ..." 
                                   class="pl-10 pr-4 py-2 w-full rounded-full border border-gray-300 focus:outline-none focus:border-[#f05123]">
                        </div>
                        <div class="mt-4 lg:mt-0 lg:ml-4" x-data="{ loggedIn: <?= json_encode($loggedIn) ?>, dropdownOpen: false, userName: '<?= $userName ?>' }">
                            <template x-if="!loggedIn">
                                <div class="flex flex-col lg:flex-row space-y-2 lg:space-y-0 lg:space-x-4">
                                    <a href="/register" class="px-4 py-2 text-[#f05123] font-medium hover:text-[#f05123]/90 no-underline">Đăng ký</a>
                                    <a href="/login" class="px-4 py-2 bg-[#f05123] text-white rounded-full font-medium hover:bg-[#f05123]/90 no-underline">Đăng nhập</a>
                                </div>
                            </template>
                            <template x-if="loggedIn">
                                <div class="relative">
                                    <button @click="dropdownOpen = !dropdownOpen" class="flex items-center px-4 py-2 text-gray-700 font-medium focus:outline-none">
                                        <img src="http://localhost:8000/<?= !empty($user['image']) ? 'uploads/' . $user['image'] : 'uploads/avatar/default-avatar.png' ?>" alt="User Avatar" class="w-8 h-8 rounded-full">
                                        <span class="ml-2" x-text="userName"></span>
                                    </button>
                                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg">
                                        <a href="/profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Trang cá nhân</a>
                                        <a href="/orderList" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Khóa học đã mua</a>
                                        <a href="/settings" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Cài đặt</a>
                                        <a href="/logout" class="block px-4 py-2 text-red-500 hover:bg-gray-100">Đăng xuất</a>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div class="mt-4 lg:hidden">
                            <a href="/posts" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Bài viết</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="navbar-sidebar fixed left-0 top-16 w-[96px] h-screen bg-white border-r border-gray-200 hidden lg:block">
        <div class="flex flex-col items-center py-6 space-y-8">
            <a href="/" class="flex flex-col items-center text-gray-700 hover:text-[#f05123]">
                <i class="fas fa-home text-xl"></i>
                <span class="mt-1 text-xs">Trang chủ</span>
            </a>
            <a href="#" class="flex flex-col items-center text-gray-700 hover:text-[#f05123]">
                <i class="fas fa-road text-xl"></i>
                <span class="mt-1 text-xs">Lộ trình</span>
            </a>
            <a href="/posts" class="flex flex-col items-center text-gray-700 hover:text-[#f05123]">
                <i class="fas fa-newspaper text-xl"></i>
                <span class="mt-1 text-xs">Bài viết</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content container mx-auto px-4 pt-20 lg:ml-[96px] pb-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <!-- Profile Sidebar -->
            <div class="md:col-span-3">
                <div class="bg-white shadow-md rounded-lg">
                    <ul class="p-4 space-y-3">
                        <li>
                            <a href="#" id="menuProfile" onclick="showSection('profileSection', 'menuProfile')"
                               class="block p-2 font-semibold text-red-500 text-sm md:text-base">Hồ Sơ</a>
                        </li>
                        <li>
                            <a href="#" id="menuChangePassword" onclick="showSection('changePasswordSection', 'menuChangePassword')"
                               class="block p-2 text-gray-600 hover:text-red-500 text-sm md:text-base">Đổi Mật Khẩu</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="md:col-span-9">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        <strong class="font-bold">Thành công!</strong>
                        <span class="block sm:inline"><?= $_SESSION['success']; ?></span>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <strong class="font-bold">Lỗi!</strong>
                        <span class="block sm:inline"><?= $_SESSION['error']; ?></span>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <div class="bg-white shadow-md rounded-lg p-6">
                    <!-- Profile Section -->
                    <div id="profileSection">
                        <h2 class="text-lg md:text-xl font-semibold text-gray-700">Hồ Sơ Của Tôi</h2>
                        <p class="text-sm text-gray-500">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
                        <form action="/profile/update" method="POST" enctype="multipart/form-data" class="mt-4">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <div class="md:col-span-8 space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600">Email</label>
                                        <input type="email"
                                               class="w-full px-4 py-2 border rounded-md bg-gray-100 cursor-not-allowed text-sm md:text-base"
                                               value="<?= htmlspecialchars($user['email']) ?>" readonly>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600">Tên</label>
                                        <input type="text" name="name"
                                               class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-red-300 text-sm md:text-base"
                                               value="<?= htmlspecialchars($user['name']) ?>" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600">Số điện thoại</label>
                                        <input type="number" name="phone"
                                               class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-red-300 text-sm md:text-base"
                                               value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                                    </div>
                                    <button type="submit"
                                            class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 text-sm md:text-base">Lưu</button>
                                </div>
                                <div class="md:col-span-4 flex flex-col items-center mt-4 md:mt-0">
                                    <img src="http://localhost:8000/<?= !empty($user['image']) ? 'uploads/' . $user['image'] : 'uploads/avatar/default-avatar.png' ?>" 
                                         alt="Avatar" class="w-20 h-20 md:w-24 md:h-24 rounded-full border">
                                    <input type="file" name="avatar" class="mt-3 w-full text-sm">
                                    <p class="text-xs text-gray-500 text-center mt-1">Dụng lượng file tối đa 1MB. Định dạng: JPEG, PNG</p>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Change Password Section -->
                    <div id="changePasswordSection" class="hidden">
                        <h3 class="text-lg md:text-xl font-semibold text-gray-700">Đổi Mật Khẩu</h3>
                        <form action="/profile/update-password" method="POST" class="mt-4">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Mật khẩu hiện tại</label>
                                    <input type="password" name="current_password"
                                           class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-red-300 text-sm md:text-base" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Mật khẩu mới</label>
                                    <input type="password" name="new_password"
                                           class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-red-300 text-sm md:text-base" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Xác nhận mật khẩu mới</label>
                                    <input type="password" name="confirm_password"
                                           class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-red-300 text-sm md:text-base" required>
                                </div>
                                <button type="submit"
                                        class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 text-sm md:text-base">Cập Nhật</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

<script>
    function showSection(sectionId, menuId) {
        localStorage.setItem("activeSection", sectionId);
        localStorage.setItem("activeMenu", menuId);

        document.getElementById('profileSection').classList.add('hidden');
        document.getElementById('changePasswordSection').classList.add('hidden');

        document.getElementById(sectionId).classList.remove('hidden');

        document.getElementById('menuProfile').classList.remove('font-semibold', 'text-red-500');
        document.getElementById('menuProfile').classList.add('text-gray-600');

        document.getElementById('menuChangePassword').classList.remove('font-semibold', 'text-red-500');
        document.getElementById('menuChangePassword').classList.add('text-gray-600');

        document.getElementById(menuId).classList.add('font-semibold', 'text-red-500');
        document.getElementById(menuId).classList.remove('text-gray-600');
    }

    document.addEventListener("DOMContentLoaded", function () {
        const activeSection = localStorage.getItem("activeSection") || "profileSection";
        const activeMenu = localStorage.getItem("activeMenu") || "menuProfile";
        showSection(activeSection, activeMenu);
    });

</script>