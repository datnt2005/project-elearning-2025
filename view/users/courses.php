    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .main-course {
            /* margin: 80px auto 20px; */
            margin: 0 auto;
            display: flex;
            gap: 20px;
        }

        .sidebar,
        .content,
        .course-info {
            background: white;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .sidebar {
            width: 250px;
        }

        .content {
            flex-grow: 1;
        }

        .course-info {
            width: 300px;
        }
    </style>
    <main class="ml-24 pt-20 px-4">

        <div class="container main-course p-4">
            <aside class="sidebar">
                <ul>
                    <li><a href="#">🏠 Trang chủ</a></li>
                    <li><a href="#">📚 Lộ trình</a></li>
                    <li><a href="#">📝 Bài viết</a></li>
                </ul>
            </aside>
            <main class="content">
                <h1 class="text-2xl font-bold mb-2"><?php echo $course['title']; ?></h1>
                <p class="mb-4"><?php echo $course['description']; ?></p>
                <h2 class="text-xl font-semibold mb-2">Nội dung khóa học</h2>
                <div class="course-content">
                    <?php foreach ($sections as $section): ?>
                        <div class="chapter font-medium cursor-pointer bg-gray-200 p-2 mt-2 rounded-md flex justify-between items-center" onclick="toggleDropdown(this)">
                            <span>Phần <?php echo $section['order_number']; ?>: <?php echo $section['title']; ?></span>
                            <i class="fas fa-chevron-right transition-transform duration-300"></i>
                        </div>
                        <ul class="list-disc pl-5 hidden">
                            <?php foreach ($lessonsBySection[$section['id']] as $lesson): ?>
                                <li>Bài <?= $lesson['order_number']; ?>: <?php echo $lesson['title']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endforeach; ?>
                </div>
            </main>
            <aside class="course-info">
                <video controls width="100%" class="rounded-lg shadow-lg">
                    <source src="http://localhost:8000/<?php echo $course['video_intro']; ?>" type="video/mp4">
                    Trình duyệt của bạn không hỗ trợ video.
                </video>
                <div class="video-preview text-center text-lg font-semibold mb-3">🎥 Xem giới thiệu khóa học</div>
                <h3 class="text-xl font-bold text-[#f05123] mb-3">Miễn phí</h3>
                <button class="w-full bg-[#f05123] text-white py-2 rounded-lg font-medium hover:bg-[#d9451e]">
                    Đăng ký học
                </button>
                <ul class="mt-4 text-gray-700">
                    <li>📊 Trình độ: Cơ bản</li>
                    <li>📚 Tổng số: 138 bài học</li>
                    <li>⏳ Thời lượng: 10 giờ 29 phút</li>
                    <li>📱 Học mọi lúc, mọi nơi</li>
                </ul>
            </aside>
        </div>
        <script>
            function toggleDropdown(element) {
                let content = element.nextElementSibling;
                let icon = element.querySelector("i");
                document.querySelectorAll(".course-content ul").forEach(ul => {
                    if (ul !== content) ul.classList.add("hidden");
                });
                content.classList.toggle("hidden");
                icon.classList.toggle("rotate-90");
            }
        </script>

    </main>