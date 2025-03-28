<?php
$loggedIn = isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;
$userName = $loggedIn ? $_SESSION['user_name'] : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://cdn1.iconfinder.com/data/icons/keyboard-shortcut/24/f8-1024.png">
    <title>F8 - Học Lập Trình Để Đi Làm</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body>
<nav class="bg-white shadow-sm fixed w-full z-10">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <a href="/" class="text-2xl font-bold text-[#f05123]">P6</a>
                <span class="ml-2 text-gray-700">Học online không khó, chỉ sợ không có tiền🙂</span>
            </div>
            <div class="flex items-center">
                <div class="relative mx-4">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <i class="fas fa-search text-gray-400"></i>
                    </span>
                    <input type="text" placeholder="Tìm kiếm khóa học, bài viết, video, ..." 
                           class="pl-10 pr-4 py-2 w-[420px] rounded-full border border-gray-300 focus:outline-none focus:border-[#f05123]">
                </div>

                <div x-data="{ loggedIn: <?= json_encode($loggedIn) ?>, dropdownOpen: false, userName: '<?= $userName ?>' }">
                    <!-- chua login -->
                    <template x-if="!loggedIn">
                        <div>
                            <a href="/register" class="px-4 py-2 text-[#f05123] font-medium hover:text-[#f05123]/90 no-underline">Đăng ký</a>
                            <a href="/login" class="px-4 py-2 bg-[#f05123] text-white rounded-full font-medium hover:bg-[#f05123]/90 no-underline">Đăng nhập</a>
                        </div>
                    </template>

                    <!-- da login -->
                    <template x-if="loggedIn">
                        <div class="relative">
                            <button @click="dropdownOpen = !dropdownOpen"
                                class="flex items-center px-4 py-2 text-gray-700 font-medium focus:outline-none">
                                <img src="http://localhost:8000/<?= !empty($user['image']) ? 'uploads/' . $user['image'] : 'uploads/avatar/default-avatar.png' ?>" alt="User Avatar" class="w-8 h-8 rounded-full">
                                <span class="ml-2" x-text="userName"></span>
                            </button>
                            <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                                class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg">
                                <a href="/profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Trang cá nhân</a>
                                <a href="/orderList" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Khóa học đã mua</a>
                                <a href="settings" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Cài đặt</a>
                                <a href="/logout" class="block px-4 py-2 text-red-500 hover:bg-gray-100">Đăng xuất</a>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</nav>


<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js"></script>

    <!-- Sidebar -->
    <div class="fixed left-0 top-16 w-[96px] h-screen bg-white border-r border-gray-200">
        <div class="flex flex-col items-center py-6 space-y-8">
            <a href="/" class="flex flex-col items-center text-gray-700 hover:text-[#f05123]">
                <i class="fas fa-home text-xl"></i>
                <span class="mt-1 text-xs">Trang chủ</span>
            </a>
            <a href="#" class="flex flex-col items-center text-gray-700 hover:text-[#f05123]">
                <i class="fas fa-road text-xl"></i>
                <span class="mt-1 text-xs">Lộ trình</span>
            </a>
            <a href="posts" class="flex flex-col items-center text-gray-700 hover:text-[#f05123]">
                <i class="fas fa-newspaper text-xl"></i>
                <span class="mt-1 text-xs">Bài viết</span>
            </a>
        </div>
    </div>
<main class="contain">
    <?= $content ?>
</main>

</body>

</html>