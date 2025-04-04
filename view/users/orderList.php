<?php
$loggedIn = isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;
$userName = $loggedIn ? $_SESSION['user_name'] : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>P6 - Khóa học đã mua</title>
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
        <h2 class="text-xl md:text-2xl font-bold mb-6">Khóa học đã mua</h2>

        <?php if (empty($orders)) : ?>
            <p class="text-gray-500 text-sm md:text-base">Bạn chưa mua khóa học nào.</p>
        <?php else : ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($orders as $order) : ?>
                    <div class="bg-slate-100 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                        <a href="/courses/learning/<?php echo $order['course_id']; ?>">
                            <div class="relative aspect-video">
                                <img class="w-full h-full object-cover" src="http://localhost:8000/<?= htmlspecialchars($order['course_image']) ?>" alt="">
                            </div>
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg md:text-xl font-bold text-gray-800"><?php echo $order['title']; ?></h3>
                                </div>
                                <div class="flex items-center text-green-600 text-sm md:text-base">
                                    <div class="flex items-center justify-between mt-2 w-full">
                                        <span class="text-gray-500 line-through mx-3">
                                            <?php echo number_format($order['total_price'], 0, ',', '.'); ?> VND
                                        </span>
                                        <span><?php echo $order['payment_status'] === 'completed' ? 'Đã thanh toán' : 'Chờ thanh toán'; ?></span>
                                    </div>
                                </div>
                                <div class="instructor flex items-center mt-2">
                                    <img class="rounded-full w-6 h-6 md:w-8 md:h-8" src="http://localhost:8000/uploads/<?= htmlspecialchars($order['instructor_image']) ?>" alt="avt">
                                    <span class="mx-2 text-gray-600 text-sm md:text-base"><?php echo $order['instructor_name']; ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js" defer></script>
</body>
</html>