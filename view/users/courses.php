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
                            <li class="cursor-pointer mt-2 list-disc">Bài <?= $lesson['order_number']; ?>: <?php echo $lesson['title']; ?></li>
                            <hr class="my-2">
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
            <button class="favorite-btn flex items-center gap-1 px-3 py-1 border border-gray-300 rounded-lg hover:bg-red-100 transition-all duration-300"
                data-course-id="<?= $course['id']; ?>"
                data-favorite="<?= $isFavourite ? 'true' : 'false'; ?>">
                <i class="fas fa-heart <?= $isFavourite ? 'text-red-500' : 'text-gray-400'; ?>"></i>
            </button>
            <button id="registerBtn" class="w-full bg-[#f05123] text-white py-2 rounded-lg font-medium hover:bg-[#d9451e]">
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
    <!-- Popup Thanh Toán -->
    <div id="paymentPopup" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-4xl w-full flex flex-col md:flex-row">
            <div class="flex-1 p-4">
                <div class="flex items-center mb-4">
                    <img src="https://placehold.co/50x50" alt="JavaScript Pro logo" class="w-12 h-12 rounded-full mr-4">
                    <h1 class="text-2xl font-bold mb-2"><?php echo $course['title']; ?></h1>
                </div>
                <p class="mb-4"><?php echo $course['description']; ?></p>
                <h2 class="font-semibold mb-2">Bạn nhận được gì từ khóa học này?</h2>
                <ul class="list-disc pl-5">
                    <li>Trình độ cơ bản</li>
                    <li>138 bài học</li>
                    <li>10 giờ 29 phút</li>
                    <li>Hoạc mọi lúc, mọi nơi</li>
                </ul>
            </div>
            <div class="bg-gray-100 p-4 rounded-lg w-full md:w-1/3">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Chi tiết thanh toán</h2>
                    <button id="closePopup" class="text-gray-500"><i class="fas fa-times"></i></button>
                </div>
                <div class="mb-4">
                    <span class="font-semibold">Giá khóa học:</span>
                    <span class="text-gray-500 line-through"> <?php echo $course['price']; ?></span>
                    <span class="text-[#f05123] font-bold"> <?php echo $course['discount_price']; ?></span>
                </div>
                <input type="text" placeholder="Nhập mã giảm giá" class="w-full p-2 border rounded mb-2">
                <button class="w-full bg-blue-100 text-blue-500 p-2 rounded">Áp dụng</button>
                <div class="flex justify-between items-center mb-4">
                    <span class="font-semibold">TỔNG</span>
                    <span class="text-[#f05123] font-bold"> <?php echo $course['discount_price']; ?></span>
                </div>
                <form action="/checkout" method="POST">
                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                    <input type="hidden" name="totalPrice" value="<?php echo $course['discount_price']; ?>">
                    <?php $totalAmount = $course['discount_price'] - 0; ?>
                    <input type="hidden" name="totalAmount" value=" <?php echo $totalAmount; ?>">
                    <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded">Tiếp tục thanh toán</button>
                </form>


                <p class="text-center text-gray-500 mt-4">Thanh toán an toàn với VNPay</p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("registerBtn").addEventListener("click", function() {
            document.getElementById("paymentPopup").classList.remove("hidden");
        });
        document.getElementById("closePopup").addEventListener("click", function() {
            document.getElementById("paymentPopup").classList.add("hidden");
        });
    </script>
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




<script>
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
                    } else if (data.status === 'removed') {
                        this.querySelector('i').classList.remove('text-red-500');
                        this.querySelector('i').classList.add('text-gray-400');
                        this.setAttribute('data-favorite', 'false');
                    }
                })
                .catch(error => console.error("Lỗi JSON:", error));
        });
    });
</script>