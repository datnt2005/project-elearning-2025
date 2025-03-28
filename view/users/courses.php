<style>
    body {
        font-family: 'Inter', sans-serif;
    }

    .main-course {
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
        width: 600px;
    }

    .hidden {
        display: none;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                            <li class="mt-3 mb-1">Bài <?= $lesson['order_number']; ?>: <?php echo $lesson['title']; ?></li>
                            <hr>
                        <?php endforeach; ?>
                    </ul>
                <?php endforeach; ?>
            </div>
        </main>
        <aside class="course-info">
            <video controls width="100%" height="200" class="rounded-lg shadow-lg">
                <source  src="http://localhost:8000/<?php echo $course['video_intro']; ?>" type="video/mp4">
                Trình duyệt của bạn không hỗ trợ video.
            </video>
            <div class="video-preview text-center text-lg font-semibold mb-3">🎥 Xem giới thiệu khóa học</div>
            <h3 class="text-xl font-bold text-[#f05123] mb-3"><?= number_format($course['discount_price']); ?>đ</h3>
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
                    <li>Hoặc mọi lúc, mọi nơi</li>
                </ul>
            </div>
            <div class="bg-gray-100 p-4 rounded-lg w-full md:w-1/3">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Chi tiết thanh toán</h2>
                    <button id="closePopup" class="text-gray-500"><i class="fas fa-times"></i></button>
                </div>
                <div class="mb-4">
                    <span class="font-semibold">Giá khóa học:</span>
                    <span class="text-gray-500 line-through" id="originalPrice"><?= number_format($course['price']); ?>đ</span>
                    <span class="text-[#f05123] font-bold" id="discountedPrice"><?= number_format($course['discount_price']); ?>đ</span>
                </div>


                <!-- Form for coupon code -->
                <form action="/checkout" method="POST">
                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                    <input type="hidden" name="amount" id="finalAmount" value="<?php echo $course['discount_price']; ?>">
                    <input type="hidden" name="payment_method" value="VNPAY">

                    <input type="text" name="coupon_code" id="couponCode" placeholder="Nhập mã giảm giá" class="w-full p-2 border rounded mb-2">
                    <button type="button" id="applyCouponBtn" class="w-full bg-blue-500 text-white p-2 rounded">Áp dụng</button>

                    <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded mt-2">Thanh toán</button>

                </form>

                <p class="text-center text-gray-500 mt-4">Thanh toán an toàn với VNPay</p>
            </div>
        </div>
    </div>


    <!-- Popup yêu cầu đăng nhập -->
    <div id="loginPopup" class="hidden">
        <div style="background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
            <div style="background-color: #fff; padding: 20px; border-radius: 8px; text-align: center;">
                <p>Vui lòng đăng nhập để tiếp tục thanh toán.</p>
                <button onclick="window.location.href = '/login';">Đăng Nhập</button>
                <button id="closeLoginPopup" class="mt-3 text-gray-500">Đóng</button>
            </div>
        </div>
    </div>

    <!-- Popup Đã Mua Khóa Học -->
    <div id="purchasePopup" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
            <div class="text-center">
                <h2 class="text-2xl font-bold mb-4">Bạn đã mua khóa học này rồi!</h2>
                <p class="mb-4">Bạn đã đăng ký khóa học này trước đó. Vui lòng kiểm tra khóa học của bạn trong trang của tôi.</p>
                <button onclick="window.location.href = '/';" class="bg-blue-500 text-white py-2 px-4 rounded-lg">Đi đến trang của tôi</button>
                <button id="closePurchasePopup" class="mt-3 text-gray-500">Đóng</button>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('applyCouponBtn').addEventListener('click', function() {
            let couponCode = document.getElementById('couponCode').value;
            let courseId = document.querySelector('input[name="course_id"]').value;

            fetch('/apply-coupon', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `coupon_code=${couponCode}&course_id=${courseId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('discountedPrice').innerText = `
                  Giá mới: ${data.new_price} VND`;
                        document.getElementById('finalAmount').value = data.new_price.replace(/\D/g, ''); // Cập nhật giá mới vào form

                        // Hiển thị popup thông báo thành công
                        Swal.fire({
                            icon: 'success',
                            title: 'Áp dụng thành công!',
                            text: `Giá mới: ${data.new_price} VND`,
                            confirmButtonText: 'OK'
                        });

                    } else {
                        // Hiển thị popup lỗi khi mã giảm giá không hợp lệ
                        Swal.fire({
                            icon: 'error',
                            title: 'Mã giảm giá không hợp lệ!',
                            text: 'Vui lòng kiểm tra lại mã của bạn.',
                            confirmButtonText: 'Thử lại'
                        });
                    }
                });
        });




        document.getElementById("registerBtn").addEventListener("click", function() {
            // Kiểm tra nếu người dùng chưa đăng nhập và chưa mua khóa học
            if (!<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>) {
                // Nếu người dùng chưa đăng nhập, hiển thị popup yêu cầu đăng nhập
                document.getElementById("loginPopup").classList.remove("hidden");
            } else if (<?php echo isset($course['purchased']) && $course['purchased'] == true ? 'true' : 'false'; ?>) {
                // Nếu người dùng đã mua khóa học, hiển thị popup "Bạn đã mua khóa học"
                document.getElementById("purchasePopup").classList.remove("hidden");
            } else {
                // Nếu người dùng đã đăng nhập nhưng chưa mua khóa học, hiển thị popup thanh toán
                document.getElementById("paymentPopup").classList.remove("hidden");
            }
        });

        // Đóng popup yêu cầu đăng nhập
        document.getElementById("closeLoginPopup").addEventListener("click", function() {
            document.getElementById("loginPopup").classList.add("hidden");
        });

        // Đóng popup thanh toán
        document.getElementById("closePopup").addEventListener("click", function() {
            document.getElementById("paymentPopup").classList.add("hidden");
        });

        // Đóng popup đã mua khóa học
        document.getElementById("closePurchasePopup").addEventListener("click", function() {
            document.getElementById("purchasePopup").classList.add("hidden");
        });

        // Hàm toggle dropdown cho các phần của khóa học
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