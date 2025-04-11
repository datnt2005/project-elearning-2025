<style>
    .container1 main-course p-4 {
        max-width: 1600px;
        margin: auto;
    }
.container1 {
    display: flex;
    justify-content: space-between;
    margin: auto auto;
    gap: 2rem;
}

.content {
    flex: 1;
}

.course-info {
    width: 400px;
}

.course-info video {
    width: 100%;
    height: auto;
}

.course-info ul {
    padding-left: 20px;
}

#registerBtn {
    display: block;
    width: 100%;
}

@media (max-width: 1024px) {
    .container1 {
        flex-direction: column;
        gap: 1.5rem;
    }

    .course-info {
        width: 100%;
    }
}

@media (max-width: 768px) {
    .content h1 {
        font-size: 1.75rem;
    }

    .content h2 {
        font-size: 1.25rem;
    }

    .course-info ul {
        padding-left: 10px;
    }

    #registerBtn {
        padding: 12px;
        font-size: 16px;
    }
}

@media (max-width: 576px) {
    .container1 {
        /* padding: 1rem; */
        margin: auto auto;
        margin-left: -80px;
    }

    .content h1 {
        font-size: 1.5rem;
    }

    .content h2 {
        font-size: 1.125rem;
    }

    .course-info ul li {
        font-size: 14px;
    }

    #registerBtn {
        font-size: 14px;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<main class="ml-24 pt-10 ">

    <div class="container1 main-course p-4">
        <main class="content">
            <h1 class="text-2xl font-bold mb-2"><?php echo $course['title']; ?></h1>
            <p class="mb-4"><?php echo $course['description']; ?></p>
            <h2 class="text-xl font-semibold mb-2">Nội dung khóa học</h2>
            <div class="course-content">
                <?php foreach ($sections as $section): ?>
                <div class="chapter font-medium cursor-pointer bg-gray-200 p-2 mt-2 rounded-md flex justify-between items-center"
                    onclick="toggleDropdown(this)">
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
                <source src="http://localhost:8000/<?php echo $course['video_intro']; ?>" type="video/mp4">
                Trình duyệt của bạn không hỗ trợ video.
            </video>
            <div class="video-preview text-center text-lg font-semibold mb-3">🎥 Xem giới thiệu khóa học</div>
            <h3 class="text-xl font-bold text-[#f05123] mb-3"><?= number_format($course['discount_price']); ?>đ</h3>
            <button id="registerBtn"
                class="w-full bg-[#f05123] text-white py-2 rounded-lg font-medium hover:bg-[#d9451e]">
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
                    <span class="text-gray-500 line-through"
                        id="originalPrice"><?= number_format($course['price']); ?>đ</span>
                    <span class="text-[#f05123] font-bold"
                        id="discountedPrice"><?= number_format($course['discount_price']); ?>đ</span>
                </div>


                <!-- Form for coupon code -->
                <form action="/checkout" method="POST">
                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                    <input type="hidden" name="amount" id="finalAmount"
                        value="<?php echo $course['discount_price']; ?>">
                    <input type="hidden" name="payment_method" value="VNPAY">

                    <input type="text" name="coupon_code" id="couponCode" placeholder="Nhập mã giảm giá"
                        class="w-full p-2 border rounded mb-2">

                    <button type="button" id="applyCouponBtn" class="w-full bg-blue-500 text-white p-2 rounded">Áp
                        dụng</button>

                    <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded mt-2">Thanh toán
                        VNPAY</button>

                </form>

                <form action="/payment/momo" method="POST" id="momoPaymentForm">
                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                    <input type="hidden" name="amount" id="momoFinalAmount"
                        value="<?php echo $course['discount_price']; ?>">
                    <input type="hidden" name="payment_method" value="MOMO">
                    <button type="submit" class="w-full bg-pink-500 text-white p-2 rounded mt-2">
                        Thanh toán qua Momo
                    </button>
                </form>
                <!-- Form cho phương thức thanh toán PayPal -->
                <form action="/payment/paypal" method="POST">
                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                    <input type="hidden" name="amount" value="<?php echo $course['discount_price']; ?>">
                    <input type="hidden" name="payment_method" value="paypal">
                    <button type="submit" class="w-full bg-yellow-500 text-white p-2 rounded mt-2">
                        Thanh toán qua PayPal
                    </button>
                </form>





                <p class="text-center text-gray-500 mt-4">Thanh toán an toàn với VNPay</p>
            </div>
        </div>
    </div>


    <!-- Popup yêu cầu đăng nhập -->
    <div id="loginPopup" class="hidden">
        <div
            style="background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
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
                <p class="mb-4">Bạn đã đăng ký khóa học này trước đó. Vui lòng kiểm tra khóa học của bạn trong trang của
                    tôi.</p>
                <button onclick="window.location.href = '/';" class="bg-blue-500 text-white py-2 px-4 rounded-lg">Đi đến
                    trang của tôi</button>
                <button id="closePurchasePopup" class="mt-3 text-gray-500">Đóng</button>
            </div>
        </div>
    </div>

    <?php
    $courseId = $course['id'] ?? null;
    ?>
    <div class="reviews-container mt-8 p-4 bg-white rounded shadow">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Đánh giá từ học viên</h2>

        <!-- Danh sách đánh giá sẽ được JavaScript cập nhật -->
        <div id="reviews-list">
            <p>Chưa có đánh giá nào.</p>
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
                    // Cập nhật giá hiển thị
                    document.getElementById('discountedPrice').innerText = `
                  Giá mới: ${data.new_price} VND`;

                    // Cập nhật giá trị amount cho cả VNPAY và MoMo
                    document.getElementById('finalAmount').value = data.new_price.replace(/\D/g,
                    ''); // Form VNPAY
                    document.getElementById('momoFinalAmount').value = data.new_price.replace(/\D/g,
                    ''); // Form MoMo

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
        } else if (
            <?php echo isset($course['purchased']) && $course['purchased'] == true ? 'true' : 'false'; ?>) {
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

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        let courseId = <?= json_encode($course['id']) ?>;
        let userId = <?= json_encode($_SESSION['user']['id'] ?? null) ?>;
        let isAdmin = <?= json_encode($_SESSION['user']['role'] === 'admin') ?>;

        // Tải danh sách đánh giá
        fetch(`/courses/review/get?course_id=${courseId}&user_id=${userId}`)
            .then(response => response.json())
            .then(data => {
                console.log("Dữ liệu nhận từ API:", data);
                let reviewsList = document.getElementById("reviews-list");
                reviewsList.innerHTML = "";

                if (data.status === "success" && Array.isArray(data.reviews) && data.reviews.length > 0) {
                    data.reviews.forEach(review => {
                        let imageHtml = review.images?.map(img =>
                            `<img src="/${img}" class="w-16 h-16 object-cover rounded-md shadow-md mr-2">`
                        ).join('') || "";

                        // 👉 **Hiển thị danh sách phản hồi khi trang load**
                        let repliesHtml = review.replies?.map(reply => `
                        <div class="reply-item ml-6 p-2 border-l-2 border-gray-300">
                            <p class="font-semibold text-blue-600">${reply.admin_name} (Admin)</p>
                            <p>${reply.comment}</p>
                            <small class="text-gray-500">${reply.created_at}</small>
                        </div>
                    `).join("") || "";

                        let replyForm = isAdmin ? `
                        <div class="reply-form hidden ml-6 mt-2">
                            <textarea class="reply-text border p-2 w-full" placeholder="Viết phản hồi..."></textarea>
                            <button class="send-reply-btn bg-green-500 text-white px-3 py-1 mt-2 rounded-md" data-review-id="${review.id}">Gửi</button>
                        </div>
                    ` : "";

                        let reviewHtml = `
                        <div class="review-item p-3 border-b border-gray-300" data-review-id="${review.id}">
                            <p class="font-semibold">${review.user_name}</p>
                            <p>${"★".repeat(review.rating)}${"☆".repeat(5 - review.rating)}</p>
                            <p>${review.comment}</p>
                            ${imageHtml ? `<div class="mt-2">${imageHtml}</div>` : ""}

                            <div class="flex space-x-4 mt-2">
                                <button class="like-btn flex items-center space-x-2" data-review-id="${review.id}">
                                    <svg class="like-icon w-6 h-6 ${review.liked ? 'text-blue-500' : 'text-gray-500'}" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14 9V5a3 3 0 0 0-6 0v4H5a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-5zM9 5a1 1 0 0 1 2 0v4H9V5zm8 14H5v-8h3.5l.5-4h6l.5 4H19v8z"></path>
                                    </svg>
                                    <span class="like-count">${review.like_count}</span>
                                </button>

                                <button class="reply-btn text-blue-500 underline" data-review-id="${review.id}">Trả lời</button>
                            </div>
                            <small class="text-gray-500">${review.created_at}</small>
                            <div class="replies-container ml-6 mt-2">${repliesHtml}</div>
                            ${replyForm}
                        </div>
                    `;

                        reviewsList.innerHTML += reviewHtml;
                    });
                } else {
                    reviewsList.innerHTML = "<p>Chưa có đánh giá nào.</p>";
                }
            })
            .catch(error => console.error("Lỗi:", error));

        // Sử dụng event delegation
        document.getElementById("reviews-list").addEventListener("click", function(event) {
            let target = event.target;

            // Xử lý nút like
            if (target.closest(".like-btn")) {
                let button = target.closest(".like-btn");
                let reviewId = button.getAttribute("data-review-id");
                toggleLike(reviewId, button);
            }

            // Xử lý nút trả lời
            if (target.classList.contains("reply-btn")) {
                let reviewId = target.getAttribute("data-review-id");
                toggleReplyInput(reviewId);
            }

            // Xử lý gửi phản hồi
            if (target.classList.contains("send-reply-btn")) {
                let reviewId = target.getAttribute("data-review-id");
                let comment = target.previousElementSibling.value.trim();
                if (comment) {
                    sendReply(reviewId, comment, target);
                }
            }
        });
    });

    // Hiển thị hộp nhập phản hồi
    function toggleReplyInput(reviewId) {
        let replyForm = document.querySelector(`.review-item[data-review-id="${reviewId}"] .reply-form`);
        if (replyForm) {
            replyForm.classList.toggle("hidden");
        }
    }

    // Gửi phản hồi từ Admin
    function sendReply(reviewId, comment, button) {
        fetch(`/courses/review/reply`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `review_id=${reviewId}&comment=${encodeURIComponent(comment)}`
            })
            .then(response => response.json())
            .then(data => {
                console.log("Dữ liệu phản hồi:", data);

                if (data.status === "success") {
                    let reply = data.reply;

                    let replyHtml = `
                    <div class="reply-item ml-6 p-2 border-l-2 border-gray-300">
                        <p class="font-semibold text-blue-600">${reply.admin_name} (Admin)</p>
                        <p>${reply.comment}</p>
                        <small class="text-gray-500">Vừa xong</small>
                    </div>
                `;

                    let reviewItem = document.querySelector(`.review-item[data-review-id="${reviewId}"]`);
                    if (reviewItem) {
                        let replyContainer = reviewItem.querySelector(".replies-container");
                        if (!replyContainer) {
                            replyContainer = document.createElement("div");
                            replyContainer.classList.add("replies-container", "ml-6", "mt-2");
                            reviewItem.appendChild(replyContainer);
                        }
                        replyContainer.insertAdjacentHTML("beforeend", replyHtml);
                    }

                    button.previousElementSibling.value = "";
                    button.closest(".reply-form").classList.add("hidden");
                } else {
                    alert("Lỗi khi gửi phản hồi!");
                }
            })
            .catch(error => console.error("Lỗi:", error));
    }

    // Xử lý Like
    function toggleLike(reviewId, button) {
        fetch(`/courses/review/like`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `review_id=${reviewId}`
            })
            .then(response => response.json())
            .then(data => {
                let likeIcon = button.querySelector(".like-icon");
                let likeCount = button.querySelector(".like-count");

                if (data.status === "liked") {
                    likeIcon.classList.add("text-blue-500");
                    likeIcon.classList.remove("text-gray-500");
                } else if (data.status === "unliked") {
                    likeIcon.classList.remove("text-blue-500");
                    likeIcon.classList.add("text-gray-500");
                }

                likeCount.innerText = data.like_count;
            })
            .catch(error => console.error("Lỗi:", error));
    }
    </script>
</main>