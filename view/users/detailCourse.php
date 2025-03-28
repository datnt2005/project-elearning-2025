<main class="ml-24 pt-20 px-4">
    <div class="container-learning">
        <!-- Phần xem video -->
        <div class="video-section">
            <div class="video-title">
                <h1 class="text-2xl font-bold m-3"><?php echo htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
            </div>
            <div class="video-player">
                <?php if ($error): ?>
                    <p class="text-red-500 text-center"><?php echo $error; ?></p>
                    <iframe id="video-iframe" width="100%" height="400" src="" frameborder="0" allowfullscreen></iframe>
                <?php else: ?>
                    <iframe id="video-iframe" width="100%" height="400"
                        src="<?php echo htmlspecialchars($currentLesson['video_url'], ENT_QUOTES, 'UTF-8') . '&enablejsapi=1'; ?>"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                <?php endif; ?>
            </div>
            <div class="description" id="video-description">
                <p class="text-3xl m-5 mb-0"><strong>Bài <?php echo $currentLesson['order_number']; ?>: <?php echo htmlspecialchars($currentLesson['title'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
                <p class="mx-5"><?php echo htmlspecialchars($currentLesson['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </div>

        <!-- Sidebar nội dung khóa học -->
        <div class="sidebar">
            <div class="progress-bar mt-4">
                <span id="course-progress">Tiến độ khóa học: <?php echo number_format($progress['progress'], 2); ?>%</span>
                <div class="progress-container">
                    <div id="progress-bar" class="progress-fill" style="width: <?php echo $progress['progress']; ?>%;"></div>
                </div>
                <span id="current-lesson-progress">Tiến độ bài học: 0%</span>
                <div id="certificate-notice" style="display: none; margin-top: 10px;">
                    <p class="text-green-500">Chúc mừng! Bạn đã hoàn thành khóa học. Chứng chỉ đã được gửi qua email của bạn.</p>
                </div>
                <?php if ($certificate): ?>
                    <div id="certificate-link" style="margin-top: 10px;">
                        <p class="text-blue-500">Bạn đã hoàn thành khóa học: 
                            <a href="http://localhost:8000/certificate?certificate_url=<?php echo htmlspecialchars($certificate['certificate_url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="underline">
                                Xem chứng chỉ
                            </a>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
            <h2 class="text-xl font-semibold mb-2 mt-3">Nội dung khóa học</h2>
            <div class="course-content">
                <?php
                $sectionIds = array_column($sections, 'id');
                $sectionOrderNumbers = array_column($sections, 'order_number', 'id');
                ?>
                <?php foreach ($sections as $section): ?>
                    <div class="chapter" onclick="toggleDropdown(this)">
                        <span>Phần <?php echo $section['order_number']; ?>: <?php echo htmlspecialchars($section['title'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                    <ul class="lesson-list">
                        <?php if (isset($lessonsBySection[$section['id']])): ?>
                            <?php
                            $isSectionLocked = false;
                            if ($section['order_number'] > 1) {
                                $prevSectionIndex = array_search($section['order_number'] - 1, $sectionOrderNumbers);
                                $prevSectionId = $sectionIds[$prevSectionIndex];
                                foreach ($lessonsBySection[$prevSectionId] as $prevLesson) {
                                    if (!$lessonProgressById[$prevLesson['id']]['completed']) {
                                        $isSectionLocked = true;
                                        break;
                                    }
                                }
                            }
                            ?>
                            <?php foreach ($lessonsBySection[$section['id']] as $lesson): ?>
                                <?php
                                $lessonProgress = $lessonProgressById[$lesson['id']];
                                $isLocked = $isSectionLocked;
                                if (!$isSectionLocked && $lesson['order_number'] > 1) {
                                    $prevLessonIndex = $lesson['order_number'] - 2;
                                    if (isset($lessonsBySection[$section['id']][$prevLessonIndex])) {
                                        $prevLessonId = $lessonsBySection[$section['id']][$prevLessonIndex]['id'];
                                        $isLocked = !$lessonProgressById[$prevLessonId]['completed'];
                                    }
                                }
                                if ($lessonProgress['completed'] || ($lesson['order_number'] > 1 && isset($lessonsBySection[$section['id']][$prevLessonIndex]) && $lessonProgressById[$lessonsBySection[$section['id']][$prevLessonIndex]['id']]['completed'])) {
                                    $isLocked = false;
                                }
                                ?>
                                <li class="lesson-item <?php echo $isLocked ? 'locked' : ''; ?>"
                                    data-section-id="<?php echo $section['id']; ?>">
                                    <a href="/courses/learning/<?php echo $course['id']; ?>?course=<?php echo $course['id']; ?>&lesson=<?php echo $lesson['id']; ?>"
                                        data-video="<?php echo htmlspecialchars($lesson['video_url'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-title="<?php echo htmlspecialchars($lesson['title'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-description="<?php echo htmlspecialchars($lesson['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                        data-lesson-id="<?php echo $lesson['id']; ?>"
                                        data-order-number="<?php echo $lesson['order_number']; ?>"
                                        class="<?php echo $isLocked ? 'disabled' : ''; ?>">
                                        Bài <?php echo $lesson['order_number']; ?>: <?php echo htmlspecialchars($lesson['title'], ENT_QUOTES, 'UTF-8'); ?>
                                        <span class="lesson-progress"><?php echo round($lessonProgress['progress']); ?>%</span>
                                    </a>
                                    <?php if ($isLocked): ?>
                                        <span class="lock-icon">🔒</span>
                                    <?php endif; ?>
                                    <hr class="my-1">
                                </li>
                            <?php endforeach; ?>
                            <a href="/quizzes/section/<?= htmlspecialchars($section['id']); ?>" class="btn btn-primary text-muted">Bài tập</a>
                        <?php endif; ?>
                    </ul>
                <?php endforeach; ?>
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



    <div class="review-section mt-8 p-4 bg-white rounded shadow ">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Đánh giá khóa học</h2>

        <form id="review-form" class="space-y-4">
            <input type="hidden" name="course_id" value="<?= $courseId ?>">

            <!-- Chọn số sao -->
            <label class="block text-lg font-medium text-gray-700">Đánh giá của bạn:</label>
            <div class="flex space-x-1">
                <?php for ($i = 1; $i <= 5; $i++) : ?>
                    <button type="button" id="rating-<?= $i ?>" class="star-button text-gray-400 text-3xl" data-value="<?= $i ?>">★</button>
                <?php endfor; ?>
                <input type="hidden" name="rating" id="rating" required>
            </div>

            <!-- Nhận xét -->
            <label for="comment" class="block text-lg font-medium text-gray-700">Nhận xét:</label>
            <textarea name="comment" id="comment" rows="4" class="w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-blue-300 focus:outline-none" placeholder="Nhập nhận xét của bạn..." required></textarea>

            <!-- Upload hình ảnh -->
            <label for="images" class="block text-lg font-medium text-gray-700">Hình ảnh (tùy chọn):</label>
            <input type="file" name="images[]" id="images" multiple class="border border-gray-300 p-2 w-full rounded-lg">
            <div id="image-preview" class="flex space-x-2 mt-2"></div>

            <!-- Nút gửi -->
            <button type="submit" id="submit-button" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg text-lg font-medium hover:bg-blue-700 transition duration-200">
                Gửi đánh giá
            </button>
        </form>

        <div id="review-message" class="mt-4 text-center text-lg font-medium"></div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("review-form");
            const ratingInput = document.getElementById("rating");
            const starButtons = document.querySelectorAll(".star-button");
            const imageInput = document.getElementById("images");
            const imagePreview = document.getElementById("image-preview");
            const submitButton = document.getElementById("submit-button");

            // Chọn số sao
            starButtons.forEach((star) => {
                star.addEventListener("click", function() {
                    let value = this.getAttribute("data-value");
                    ratingInput.value = value;

                    starButtons.forEach((s) => s.classList.remove("text-yellow-400"));
                    for (let i = 0; i < value; i++) {
                        starButtons[i].classList.add("text-yellow-400");
                    }
                });
            });

            // Xem trước hình ảnh
            imageInput.addEventListener("change", function() {
                imagePreview.innerHTML = "";
                Array.from(this.files).forEach((file) => {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let img = document.createElement("img");
                        img.src = e.target.result;
                        img.classList.add("w-16", "h-16", "object-cover", "rounded-md", "shadow-md");
                        imagePreview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            });

            // Gửi đánh giá (thêm hoặc sửa)
            form.addEventListener("submit", function(event) {
                event.preventDefault();
                let formData = new FormData(this);
                let reviewId = form.dataset.editId || "";

                if (reviewId) {
                    formData.append("review_id", reviewId);
                }

                let apiUrl = reviewId ? "/courses/review/update" : "/courses/review/add";

                fetch(apiUrl, {
                        method: "POST",
                        body: formData,
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        document.getElementById("review-message").innerText = data.message;
                        if (data.status === "success") {
                            location.reload(); // Load lại trang để cập nhật danh sách đánh giá
                        }
                    })
                    .catch((error) => console.error("Lỗi:", error));
            });

            // Lấy danh sách đánh giá
            let courseId = <?= json_encode($course['id']) ?>;
            let userId = <?= json_encode($_SESSION['user']['id'] ?? null) ?>;
            let isAdmin = <?= json_encode($_SESSION['user']['role'] === 'admin') ?>;

            fetch(`/courses/review/get?course_id=${courseId}&user_id=${userId}`)
                .then((response) => response.json())
                .then((data) => {
                    if (data.status === "success") {
                        let reviewsList = document.getElementById("reviews-list");
                        reviewsList.innerHTML = "";

                        if (data.reviews.length === 0) {
                            reviewsList.innerHTML = "<p>Chưa có đánh giá nào.</p>";
                        } else {
                            data.reviews.forEach((review) => {
                                let imageHtml = "";
                                if (review.images.length > 0) {
                                    review.images.forEach((image) => {
                                        imageHtml += `<img src="/${image}" class="w-16 h-16 object-cover rounded-md shadow-md mr-2">`;
                                    });
                                }

                                let isOwner = review.user_id == <?= json_encode($_SESSION['user']['id'] ?? null) ?>;

                                // 👉 **Tạo danh sách phản hồi**
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
                        <div class="review-item p-3 border-b border-gray-300 relative" data-review-id="${review.id}">
                            <p class="font-semibold">${review.user_name}</p>
                            <p>${"★".repeat(review.rating)}${"☆".repeat(5 - review.rating)}</p>
                            <p>${review.comment}</p>
                            ${imageHtml ? `<div class="mt-2">${imageHtml}</div>` : ""}
                            <small class="text-gray-500">${review.created_at}</small>

                            <!-- Dropdown ba chấm -->
                            ${isOwner ? `
                                <div class="absolute right-0 top-0 mt-2">
                                    <button class="dropdown-btn text-gray-600 hover:text-black focus:outline-none">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm0 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm0 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"></path>
                                        </svg>
                                    </button>
                                    <div class="dropdown-menu absolute right-0 mt-2 w-28 bg-white border rounded-md shadow-lg hidden">
                                        <button class="edit-review w-full text-left px-4 py-2 text-sm hover:bg-gray-100" 
                                            data-id="${review.id}" 
                                            data-rating="${review.rating}" 
                                            data-comment="${review.comment}" 
                                            data-images='${JSON.stringify(review.images)}'>Sửa</button>
                                        <button class="delete-review w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100" 
                                            data-id="${review.id}">Xóa</button>
                                    </div>
                                </div>
                            ` : ""}

                            <!-- Nút Like -->
                            <div class="flex space-x-4 mt-2">
                                <button class="like-btn flex items-center space-x-2" data-review-id="${review.id}">
                                    <svg class="like-icon w-6 h-6 ${review.liked ? 'text-blue-500' : 'text-gray-500'}" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14 9V5a3 3 0 0 0-6 0v4H5a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-5zM9 5a1 1 0 0 1 2 0v4H9V5zm8 14H5v-8h3.5l.5-4h6l.5 4H19v8z"></path>
                                    </svg>
                                    <span class="like-count">${review.like_count}</span>
                                </button>

                                <button class="reply-btn text-blue-500 underline" data-review-id="${review.id}">Trả lời</button>
                            </div>
        
                            

                            <!-- Hiển thị phản hồi -->
                            <div class="replies-container ml-6 mt-2">${repliesHtml}</div>
                            ${replyForm}
                        </div>
                    `;

                                reviewsList.innerHTML += reviewHtml;
                            });

                            // Event delegation: Sự kiện click cho nút ba chấm


                            document.getElementById("reviews-list").addEventListener("click", function(event) {
                                let target = event.target;

                                if (event.target.closest(".dropdown-btn")) {
                                    let dropdownMenu = event.target.closest(".dropdown-btn").nextElementSibling;
                                    document.querySelectorAll(".dropdown-menu").forEach(menu => {
                                        if (menu !== dropdownMenu) menu.classList.add("hidden");
                                    });
                                    dropdownMenu.classList.toggle("hidden");
                                    event.stopPropagation();
                                }

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



                            // Đóng dropdown khi click ra ngoài
                            document.addEventListener("click", function() {
                                document.querySelectorAll(".dropdown-menu").forEach(menu => {
                                    menu.classList.add("hidden");
                                });
                            });

                        }
                    }
                })
                .catch((error) => console.error("Lỗi:", error));
        });

        // Hiển thị hộp nhập phản hồi
        function toggleReplyInput(reviewId) {
            let reviewItem = document.querySelector(`.review-item[data-review-id="${reviewId}"]`);
            if (!reviewItem) {
                console.error("Không tìm thấy review-item với ID:", reviewId);
                return;
            }
            let replyForm = reviewItem.querySelector(".reply-form");
            if (replyForm) {
                replyForm.classList.toggle("hidden");
            } else {
                console.error("Không tìm thấy reply-form trong review-item:", reviewId);
            }
        }



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




        // Xử lý xóa đánh giá
        document.addEventListener("click", function(event) {
            if (event.target.classList.contains("delete-review")) {
                let reviewId = event.target.getAttribute("data-id");

                if (confirm("Bạn có chắc muốn xóa đánh giá này?")) {
                    let formData = new FormData();
                    formData.append("review_id", reviewId);

                    fetch("/courses/review/delete", {
                            method: "POST",
                            body: formData,
                        })
                        .then((response) => response.json())
                        .then((data) => {
                            alert(data.message);
                            if (data.status === "success") {
                                event.target.closest(".review-item").remove();
                            }
                        })
                        .catch((error) => console.error("Lỗi:", error));
                }
            }
        });

        // Xử lý sửa đánh giá
        document.addEventListener("click", function(event) {
            if (event.target.classList.contains("edit-review")) {
                let reviewId = event.target.getAttribute("data-id");
                let rating = event.target.getAttribute("data-rating");
                let comment = event.target.getAttribute("data-comment");
                let images = JSON.parse(event.target.getAttribute("data-images"));
                const submitButton = document.getElementById("submit-button");

                document.getElementById("rating").value = rating;
                document.getElementById("comment").value = comment;
                document.getElementById("review-form").dataset.editId = reviewId;
                submitButton.textContent = "Cập nhật đánh giá";

                // Cập nhật giao diện sao
                starButtons.forEach((s) => s.classList.remove("text-yellow-400"));
                for (let i = 0; i < rating; i++) {
                    starButtons[i].classList.add("text-yellow-400");
                }

                // Hiển thị ảnh cũ trong phần xem trước ảnh
                imagePreview.innerHTML = ""; // Xóa ảnh cũ nếu có
                images.forEach((imgSrc) => {
                    let img = document.createElement("img");
                    img.src = imgSrc;
                    img.classList.add("w-16", "h-16", "object-cover", "rounded-md", "shadow-md", "mr-2");
                    imagePreview.appendChild(img);
                });
            }
        });

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

                    // Cập nhật số like
                    likeCount.innerText = data.like_count;
                })
                .catch(error => console.error("Lỗi:", error));
        }
    </script>
</main>
<script src="https://www.youtube.com/iframe_api"></script>
<script>
    console.log('Script loaded');
    let player;
    let currentLessonId;

    try {
        console.log('Loading YouTube IFrame API');
        window.onYouTubeIframeAPIReady = function() {
            console.log('YouTube IFrame API ready');

            // Gọi updateCourseProgressAndEnrollment khi trang được tải để đảm bảo tiến độ hiển thị ngay
            updateCourseProgressAndEnrollment();

            document.querySelectorAll(".lesson-item a:not(.disabled)").forEach(item => {
                item.addEventListener("click", function(e) {
                    e.preventDefault();
                    let videoUrl = this.dataset.video;
                    let title = this.dataset.title;
                    let description = this.dataset.description;
                    let lessonId = this.dataset.lessonId;
                    let orderNumber = this.dataset.orderNumber;
                    changeVideo(videoUrl, title, description, lessonId, orderNumber);
                });
            });

            let params = new URLSearchParams(window.location.search);
            let lessonId = params.get("lesson");
            console.log('Initial lesson ID:', lessonId);

            if (lessonId) {
                let activeLesson = document.querySelector(`[data-lesson-id='${lessonId}']`);
                if (activeLesson && !activeLesson.classList.contains('disabled')) {
                    changeVideo(activeLesson.dataset.video, activeLesson.dataset.title, activeLesson.dataset.description, lessonId, activeLesson.dataset.orderNumber);
                } else {
                    console.log('Lesson not found or locked:', lessonId);
                }
            } else {
                let firstLesson = document.querySelector(".lesson-item a:not(.disabled)");
                if (firstLesson) {
                    changeVideo(firstLesson.dataset.video, firstLesson.dataset.title, firstLesson.dataset.description, firstLesson.dataset.lessonId, firstLesson.dataset.orderNumber);
                } else {
                    console.log('No unlocked lesson found');
                }
            }
        };

        function changeVideo(videoUrl, title, description, lessonId, orderNumber) {
            console.log('Changing video to:', videoUrl, 'Lesson ID:', lessonId);
            currentLessonId = lessonId;
            if (player) {
                player.destroy();
            }

            let iframe = document.getElementById("video-iframe");
            if (!iframe) {
                console.error('Iframe not found! Attempting to recover...');
                const videoPlayer = document.querySelector('.video-player');
                if (videoPlayer) {
                    iframe = document.createElement('iframe');
                    iframe.id = 'video-iframe';
                    iframe.width = '100%';
                    iframe.height = '400';
                    iframe.frameBorder = '0';
                    iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
                    iframe.allowFullscreen = true;
                    videoPlayer.prepend(iframe);
                    console.log('Iframe recreated:', iframe);
                } else {
                    console.error('Video player container not found!');
                    return;
                }
            }
            iframe.src = videoUrl + "&enablejsapi=1";
            document.getElementById("video-description").innerHTML = `<p class="text-3xl m-5 mb-0"><strong>Bài ${orderNumber}: ${title}</strong></p><p class="mx-5">${description}</p>`;
            history.pushState(null, "", `?course=<?php echo $course['id']; ?>&lesson=${lessonId}`);

            console.log('Initializing YT.Player with iframe:', iframe);
            try {
                player = new YT.Player('video-iframe', {
                    events: {
                        'onReady': onPlayerReady,
                        'onStateChange': onPlayerStateChange,
                        'onError': (event) => console.log('Player error:', event.data)
                    }
                });
                console.log('YT.Player initialized:', player);
            } catch (error) {
                console.error('Error initializing YT.Player:', error);
            }
        }

        function onPlayerReady(event) {
            console.log('Player ready');
        }

        let progressInterval;
        let lastProgress = 0;

        function onPlayerStateChange(event) {
            console.log('Player state changed:', event.data);
            if (event.data === YT.PlayerState.PLAYING) {
                console.log('Video is playing');
                clearInterval(progressInterval);
                progressInterval = setInterval(() => {
                    let currentTime = player.getCurrentTime();
                    let duration = player.getDuration();
                    let progress = (currentTime / duration) * 100 || 0;
                    let completed = progress >= 95;

                    document.getElementById("current-lesson-progress").textContent = `Tiến độ bài học: ${Math.round(progress)}%`;
                    let lessonLink = document.querySelector(`[data-lesson-id='${currentLessonId}']`);
                    if (lessonLink) {
                        lessonLink.querySelector('.lesson-progress').textContent = `${Math.round(progress)}%`;
                    }

                    console.log('Current time:', currentTime, 'Duration:', duration, 'Progress:', progress);
                    if (Math.abs(progress - lastProgress) >= 5 || completed) {
                        let payload = {
                            user_id: <?php echo $_SESSION['user']['id']; ?>,
                            lesson_id: currentLessonId,
                            progress: progress,
                            completed: completed
                        };
                        console.log('Sending payload:', payload);
                        fetch('/update_progress', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        }).then(response => {
                            console.log('Response status:', response.status);
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.json();
                        }).then(data => {
                            console.log('Response data:', data);
                            if (data.status === 'success') {
                                lastProgress = progress;
                                if (completed) {
                                    unlockNextLesson(currentLessonId);
                                    goToNextLesson(currentLessonId);
                                    updateCourseProgressAndEnrollment();
                                }
                            }
                        }).catch(error => {
                            console.error('Fetch error:', error);
                        });
                    }
                }, 1000);
            } else if (event.data === YT.PlayerState.ENDED) {
                console.log('Video ended');
                clearInterval(progressInterval);
                let payload = {
                    user_id: <?php echo $_SESSION['user']['id']; ?>,
                    lesson_id: currentLessonId,
                    progress: 100,
                    completed: true
                };
                document.getElementById("current-lesson-progress").textContent = `Tiến độ bài học: 100%`;
                let lessonLink = document.querySelector(`[data-lesson-id='${currentLessonId}']`);
                if (lessonLink) {
                    lessonLink.querySelector('.lesson-progress').textContent = '100%';
                }
                fetch('/update_progress', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                }).then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                }).then(data => {
                    console.log('Response data:', data);
                    if (data.status === 'success') {
                        unlockNextLesson(currentLessonId);
                        goToNextLesson(currentLessonId);
                        updateCourseProgressAndEnrollment();
                    }
                }).catch(error => {
                    console.error('Fetch error:', error);
                });
            } else {
                console.log('Video stopped or paused');
                clearInterval(progressInterval);
            }
        }

        function unlockNextLesson(currentLessonId) {
            console.log('Unlocking next lesson after:', currentLessonId);
            let allLessons = document.querySelectorAll('.lesson-item a');
            let currentLessonIndex = -1;
            let currentSectionId = null;

            for (let i = 0; i < allLessons.length; i++) {
                if (allLessons[i].dataset.lessonId === currentLessonId) {
                    currentLessonIndex = i;
                    currentSectionId = allLessons[i].parentElement.dataset.sectionId;
                    break;
                }
            }

            if (currentLessonIndex !== -1) {
                let nextLessonIndex = currentLessonIndex + 1;
                if (nextLessonIndex < allLessons.length) {
                    let nextLesson = allLessons[nextLessonIndex];
                    let nextLessonItem = nextLesson.parentElement;
                    let nextSectionId = nextLessonItem.dataset.sectionId;

                    if (currentSectionId !== nextSectionId) {
                        let currentSectionLessons = document.querySelectorAll(`.lesson-item[data-section-id='${currentSectionId}'] a`);
                        let allCompleted = true;
                        currentSectionLessons.forEach(lesson => {
                            let progress = parseInt(lesson.querySelector('.lesson-progress').textContent);
                            if (progress < 95) {
                                allCompleted = false;
                            }
                        });

                        if (allCompleted) {
                            let nextSectionLessons = document.querySelectorAll(`.lesson-item[data-section-id='${nextSectionId}']`);
                            nextSectionLessons.forEach(item => {
                                let lessonLink = item.querySelector('a');
                                lessonLink.classList.remove('disabled');
                                item.classList.remove('locked');
                                item.querySelector('.lock-icon')?.remove();
                                lessonLink.onclick = function(e) {
                                    e.preventDefault();
                                    changeVideo(lessonLink.dataset.video, lessonLink.dataset.title, lessonLink.dataset.description, lessonLink.dataset.lessonId, lessonLink.dataset.orderNumber);
                                };
                            });
                            console.log('Unlocked section:', nextSectionId);
                        }
                    } else {
                        nextLesson.classList.remove('disabled');
                        nextLessonItem.classList.remove('locked');
                        nextLessonItem.querySelector('.lock-icon')?.remove();
                        nextLesson.onclick = function(e) {
                            e.preventDefault();
                            changeVideo(nextLesson.dataset.video, nextLesson.dataset.title, nextLesson.dataset.description, nextLesson.dataset.lessonId, nextLesson.dataset.orderNumber);
                        };
                        console.log('Unlocked next lesson:', nextLesson.dataset.lessonId);
                    }
                }
            }
        }

        function goToNextLesson(currentLessonId) {
            console.log('Going to next lesson from:', currentLessonId);
            let allLessons = document.querySelectorAll('.lesson-item a');
            let currentLessonIndex = -1;

            for (let i = 0; i < allLessons.length; i++) {
                if (allLessons[i].dataset.lessonId === currentLessonId) {
                    currentLessonIndex = i;
                    break;
                }
            }

            if (currentLessonIndex !== -1) {
                let nextLessonIndex = currentLessonIndex + 1;
                if (nextLessonIndex < allLessons.length) {
                    let nextLesson = allLessons[nextLessonIndex];
                    let videoUrl = nextLesson.dataset.video;
                    let title = nextLesson.dataset.title;
                    let description = nextLesson.dataset.description;
                    let lessonId = nextLesson.dataset.lessonId;
                    let orderNumber = nextLesson.dataset.orderNumber;
                    changeVideo(videoUrl, title, description, lessonId, orderNumber);
                    console.log('Moved to next lesson:', lessonId);
                } else {
                    console.log('No next lesson available');
                }
            }
        }

        function updateCourseProgressAndEnrollment() {
            console.log('Starting updateCourseProgressAndEnrollment');
            const payload = {
                user_id: <?php echo $_SESSION['user']['id']; ?>,
                course_id: <?php echo $course['id']; ?>
            };
            fetch('/calculate_progress', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            }).then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            }).then(data => {
                if (data.status === 'success') {
                    let courseProgress = Math.round(data.progress * 100) / 100;
                    document.getElementById("course-progress").textContent = `Tiến độ khóa học: ${courseProgress}%`;
                    document.getElementById("progress-bar").style.width = `${courseProgress}%`;

                    let status = courseProgress >= 100 ? 'completed' : (courseProgress > 0 ? 'in-progress' : 'enrolled');
                    const enrollmentPayload = {
                        user_id: <?php echo $_SESSION['user']['id']; ?>,
                        course_id: <?php echo $course['id']; ?>,
                        enrollment_date: '<?php echo date('Y-m-d H:i:s'); ?>',
                        status: status,
                        progress: courseProgress
                    };
                    return fetch('/update_enrollment', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(enrollmentPayload)
                    });
                }
            }).then(response => {
                if (!response) return;
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            }).then(data => {
                console.log('Update enrollment response:', data);
                if (data.status === 'success' && data.certificate && data.certificate.status === 'success') {
                    const notice = document.getElementById('certificate-notice');
                    notice.style.display = 'block';
                    console.log('Certificate sent to email:', data.certificate.certificate_code);
                }
            }).catch(error => console.error('Error:', error));
        }

        function toggleDropdown(element) {
            let content = element.nextElementSibling;
            let icon = element.querySelector("i");
            document.querySelectorAll(".lesson-list").forEach(ul => {
                if (ul !== content) ul.style.display = "none";
            });
            content.style.display = (content.style.display === "block") ? "none" : "block";
            icon.classList.toggle("rotate-90");
        }

        setTimeout(() => {
            if (!window.YT) console.error('YouTube API not loaded after 5 seconds');
            else console.log('YouTube API loaded:', window.YT);
        }, 5000);

        setTimeout(() => {
            if (typeof window.onYouTubeIframeAPIReady === 'function') {
                console.log('Manually triggering onYouTubeIframeAPIReady');
                window.onYouTubeIframeAPIReady();
            }
        }, 2000);

    } catch (error) {
        console.error('JavaScript error:', error);
    }
</script>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f9f9f9;
    }

    .container-learning {
        display: flex;
        max-width: 1800px;
        margin: 0 auto;
    }

    .video-section {
        flex: 3;
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .video-player iframe {
        width: 100%;
        height: 600px;
        border-radius: 10px;
    }

    .sidebar {
        flex: 1;
        background: white;
        padding: 20px;
        margin-left: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .chapter {
        font-size: 16px;
        font-weight: bold;
        background: #f1f1f1;
        padding: 10px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .lesson-list {
        list-style: none;
        padding-left: 15px;
        display: none;
    }

    .lesson-item {
        padding: 5px 0;
        font-size: 14px;
        color: #333;
        position: relative;
    }

    .lesson-item a {
        text-decoration: none;
        color: inherit;
        display: block;
        padding: 5px;
    }

    .lesson-item a:hover {
        background: #f1f1f1;
    }

    .lesson-item.locked a {
        color: #999;
        cursor: not-allowed;
    }

    .lesson-item .disabled {
        pointer-events: none;
    }

    .lesson-progress {
        float: right;
        color: #007bff;
        font-size: 12px;
    }

    .rotate-90 {
        transform: rotate(90deg);
        transition: transform 0.3s ease;
    }

    .progress-container {
        width: 100%;
        background: #e9ecef;
        border-radius: 5px;
        height: 10px;
        margin-top: 5px;
    }

    .progress-fill {
        background: #007bff;
        height: 100%;
        border-radius: 5px;
        transition: width 0.3s ease;
    }

    #current-lesson-progress {
        display: block;
        margin-top: 5px;
        font-size: 14px;
        color: #333;
    }

    .lock-icon {
        margin-left: 5px;
    }
</style>
