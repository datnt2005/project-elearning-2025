<main class="ml-24 pt-20 px-4">
    <div class="container-learning">
        <!-- Phần xem video -->
        <div class="video-section">
            <div class="video-title">
                <h1 class="text-2xl font-bold m-3"><?php echo htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
            </div>
            <div class="video-player">
                <iframe id="video-iframe" width="100%" height="400" src="" title="YouTube video player" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
            <div class="description" id="video-description">
                <p>Chọn một bài học để bắt đầu.</p>
            </div>
        </div>

        <!-- Sidebar nội dung khóa học -->
        <div class="sidebar">
            <h2 class="text-xl font-semibold mb-2">Nội dung khóa học</h2>
            <div class="course-content">
                <?php foreach ($sections as $section): ?>
                    <div class="chapter" onclick="toggleDropdown(this)">
                        <span>Phần <?php echo $section['order_number']; ?>: <?php echo htmlspecialchars($section['title'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                    <ul class="lesson-list">
                        <?php if (isset($lessonsBySection[$section['id']])): ?>
                            <?php foreach ($lessonsBySection[$section['id']] as $lesson): ?>
                                <li class="lesson-item">
                                    <a href="javascript:void(0)" data-video="<?php echo htmlspecialchars($lesson['video_url'], ENT_QUOTES, 'UTF-8'); ?>"
                                       data-title="<?php echo htmlspecialchars($lesson['title'], ENT_QUOTES, 'UTF-8'); ?>"
                                       data-description="<?php echo htmlspecialchars($lesson['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                       data-lesson-id="<?php echo $lesson['id']; ?>">
                                        Bài <?php echo $lesson['order_number']; ?>: <?php echo htmlspecialchars($lesson['title'], ENT_QUOTES, 'UTF-8'); ?>
                                    </a>
                                    <hr class="my-1">
                                </li>
                                
                            <?php endforeach; ?>
                            <a href="/quizzes/section/<?= htmlspecialchars($section['id']); ?>" class="btn btn-primary text-muted">
                                    Bài tập
                                </a>
                        <?php endif; ?>
                    </ul>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let params = new URLSearchParams(window.location.search);
    let lessonId = params.get("lesson");

    function changeVideo(videoUrl, title, description, lessonId) {
        let iframe = document.getElementById("video-iframe");
        iframe.src = videoUrl;
        document.getElementById("video-description").innerHTML = `<p class="text-3xl m-5 mb-0"><strong>${title}</strong></p><p class="mx-5">${description}</p>`;
        
        // Cập nhật URL mà không tải lại trang
        history.pushState(null, "", `?course=<?php echo $course['id']; ?>&lesson=${lessonId}`);
    }

    document.querySelectorAll(".lesson-item a").forEach(item => {
        item.addEventListener("click", function () {
            let videoUrl = this.dataset.video;
            let title = this.dataset.title;
            let description = this.dataset.description;
            let lessonId = this.dataset.lessonId;
            changeVideo(videoUrl, title, description, lessonId);
        });
    });

    // Nếu URL đã có bài học, tự động tải bài đó
    if (lessonId) {
        let activeLesson = document.querySelector(`[data-lesson-id='${lessonId}']`);
        if (activeLesson) {
            changeVideo(activeLesson.dataset.video, activeLesson.dataset.title, activeLesson.dataset.description, lessonId);
        }
    } else {
        // Nếu chưa có bài học nào trong URL, tải bài đầu tiên
        let firstLesson = document.querySelector(".lesson-item a");
        if (firstLesson) {
            changeVideo(firstLesson.dataset.video, firstLesson.dataset.title, firstLesson.dataset.description, firstLesson.dataset.lessonId);
        }
    }
});

function toggleDropdown(element) {
    let content = element.nextElementSibling;
    let icon = element.querySelector("i");
    
    document.querySelectorAll(".lesson-list").forEach(ul => {
        if (ul !== content) ul.style.display = "none";
    });

    content.style.display = (content.style.display === "block") ? "none" : "block";
    icon.classList.toggle("rotate-90");
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
    /* border-radius: 5px; */
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

.rotate-90 {
    transform: rotate(90deg);
    transition: transform 0.3s ease;
}
</style>