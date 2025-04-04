<?php
$loggedIn = isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;
$userName = $loggedIn ? $_SESSION['user_name'] : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>P6 - Passion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        @media (max-width: 1024px) {
            .sidebar {
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
    <div style="max-width: 96px" class="sidebar fixed left-0 top-16 w-[96px] h-screen bg-white border-r border-gray-200 hidden lg:block">
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
    <main class="main-content container mx-auto px-4 pt-20 lg:ml-[96px] mb-5">
        <!-- Hero Section -->
        <div class="relative overflow-hidden rounded-2xl mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 md:p-8 flex flex-col md:flex-row items-center justify-between">
                <div class="max-w-xl text-center md:text-left">
                    <h1 class="text-2xl md:text-4xl font-bold text-white mb-4">Học ReactJS Miễn Phí!</h1>
                    <p class="text-white/90 mb-6 text-sm md:text-base">
                        Khóa học ReactJS từ cơ bản tới nâng cao. Kết quả của khóa học này là bạn có thể làm hầu hết các dự án thường gặp với ReactJS.
                    </p>
                    <button class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-white/90">
                        ĐĂNG KÝ NGAY
                    </button>
                </div>
                <div class="flex-shrink-0 mt-6 md:mt-0">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a7/React-icon.svg/1200px-React-icon.svg.png"
                         alt="ReactJS Logo"
                         class="w-40 h-40 md:w-64 md:h-64 object-contain">
                </div>
            </div>
        </div>

        <!-- Pro Courses Section -->
        <section>
            <h2 class="text-xl md:text-2xl font-bold mb-6 flex items-center">
                Khóa học Pro
                <span class="ml-2 px-2 py-1 bg-blue-500 text-white text-xs rounded">MỚI</span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($courses as $course) : ?>
                    <?php
                    $isFavourite = in_array($course['id'], (array) $favourites);
                    $isPurchased = in_array($course['id'], (array) $orders);
                    ?>
                    <div class="bg-slate-100 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                        <a href="<?php echo $isPurchased ? "/courses/learning/{$course['id']}" : "/courses/show/{$course['id']}"; ?>">
                            <div class="relative aspect-video">
                                <img class="w-full h-full object-cover" src="http://localhost:8000/<?= htmlspecialchars($course['image']) ?>" alt="">
                            </div>
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg md:text-xl font-bold text-gray-800"><?php echo $course['title']; ?></h3>
                                    <?php if (!$isPurchased) : ?>
                                        <button class="favorite-btn" data-course-id="<?= $course['id']; ?>" data-favorite="<?= $isFavourite ? 'true' : 'false'; ?>">
                                            <i class="fas fa-heart <?= $isFavourite ? 'text-red-500' : 'text-gray-400'; ?>"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center">
                                    <?php if ($isPurchased) : ?>
                                        <span class="text-green-600 font-bold block mt-2 text-sm md:text-base">Bạn đã mua khóa học này!</span>
                                    <?php else : ?>
                                        <div class="flex items-center justify-between mt-2 w-full">
                                            <span class="text-gray-500 line-through text-sm md:text-base">
                                                <?php echo number_format($course['price'], 0, ',', '.'); ?> VND
                                            </span>
                                            <span class="text-[#f05123] font-bold mx-2 text-sm md:text-base">
                                                <?php echo number_format($course['discount_price'], 0, ',', '.'); ?> VND
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="instructor flex items-center mt-2">
                                    <img class="rounded-full w-6 h-6 md:w-8 md:h-8" src="http://localhost:8000/uploads/<?= htmlspecialchars($course['instructor_image']) ?>" alt="avt">
                                    <span class="mx-2 text-gray-600 text-sm md:text-base"><?php echo $course['instructor_name']; ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Favourite Courses Section -->
        <section>
            <?php if (!empty($favouriteList)) : ?>
                <h2 class="text-xl md:text-2xl font-bold mb-6 flex items-center mt-8">
                    Khóa học Yêu thích
                    <span class="ml-2 px-2 py-1 bg-blue-500 text-white text-xs rounded">MỚI</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($courses as $course) : ?>
                        <?php
                        $isPurchased = in_array($course['id'], (array) $orders);
                        ?>
                        <?php if (in_array($course['id'], $favouriteList)) : ?>
                            <div class="bg-slate-100 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                                <a href="<?php echo $isPurchased ? "/courses/learning/{$course['id']}" : "/courses/show/{$course['id']}"; ?>">
                                    <div class="relative aspect-video">
                                        <img class="w-full h-full object-cover" src="http://localhost:8000/<?= htmlspecialchars($course['image']) ?>" alt="">
                                    </div>
                                    <div class="p-4">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-lg md:text-xl font-bold text-gray-800"><?php echo $course['title']; ?></h3>
                                            <?php if (!$isPurchased) : ?>
                                                <button class="favorite-btn" data-course-id="<?= $course['id']; ?>" data-favorite="true">
                                                    <i class="fas fa-heart text-red-500"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex items-center">
                                            <?php if ($isPurchased) : ?>
                                                <span class="text-green-600 font-bold block mt-2 text-sm md:text-base">Bạn đã mua khóa học này!</span>
                                            <?php else : ?>
                                                <div class="flex items-center justify-between mt-2 w-full">
                                                    <span class="text-gray-500 line-through text-sm md:text-base">
                                                        <?php echo number_format($course['price'], 0, ',', '.'); ?> VND
                                                    </span>
                                                    <span class="text-[#f05123] font-bold mx-2 text-sm md:text-base">
                                                        <?php echo number_format($course['discount_price'], 0, ',', '.'); ?> VND
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="instructor flex items-center mt-2">
                                            <img class="rounded-full w-6 h-6 md:w-8 md:h-8" src="http://localhost:8000/uploads/<?= htmlspecialchars($course['instructor_image']) ?>" alt="avt">
                                            <span class="mx-2 text-gray-600 text-sm md:text-base"><?php echo $course['instructor_name']; ?></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let favList = JSON.parse(localStorage.getItem('favourites')) || [];

            document.querySelectorAll('.favorite-btn').forEach(button => {
                let courseId = button.getAttribute('data-course-id');
                if (favList.includes(courseId)) {
                    button.querySelector('i').classList.remove('text-gray-400');
                    button.querySelector('i').classList.add('text-red-500');
                    button.setAttribute('data-favorite', 'true');
                }
            });

            document.querySelectorAll('.favorite-btn').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    let courseId = this.getAttribute('data-course-id');
                    let isFavorite = this.getAttribute('data-favorite') === 'true';
                    let url = isFavorite ? '/favourite/remove' : '/favourite/add';

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            course_id: courseId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'added') {
                            this.querySelector('i').classList.remove('text-gray-400');
                            this.querySelector('i').classList.add('text-red-500');
                            this.setAttribute('data-favorite', 'true');
                            favList.push(courseId);
                        } else if (data.status === 'removed') {
                            this.querySelector('i').classList.remove('text-red-500');
                            this.querySelector('i').classList.add('text-gray-400');
                            this.setAttribute('data-favorite', 'false');
                            favList = favList.filter(id => id !== courseId);
                        }
                        localStorage.setItem('favourites', JSON.stringify(favList));
                    });
                });
            });
        });
    </script>
</body>
</html>