<div class="container-fluid px-4">
    <h1 class="mt-4 text-center" style="color: var(--header-bg);">Chỉnh sửa Câu Hỏi</h1>
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Chỉnh sửa câu hỏi</h5>
        </div>
        <div class="card-body">
            <style>
            :root {
                --header-bg: #343a40;
                --primary-btn: #dc3545;
                --primary-btn-hover: #bb2d3b;
                --text-light: #f8f9fa;
                --border-color: rgba(255,255,255,0.1);
            }

            .error {
                color: var(--primary-btn);
            }

            .form-control:focus,
            .form-select:focus {
                border-color: var(--primary-btn);
                box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
            }

            .btn-primary {
                background-color: var(--primary-btn);
                border-color: var(--primary-btn);
                color: var(--text-light);
            }

            .btn-primary:hover {
                background-color: var(--primary-btn-hover);
                border-color: var(--primary-btn-hover);
                transform: translateY(-1px);
            }
            </style>

            <div class="container mt-4">
                <form id="quizForm" method="POST" class="border p-4 rounded shadow bg-white">
                    <!-- Chọn khóa học -->
                    <div class="mb-3">
                        <label class="form-label">Chọn Khóa Học:</label>
                        <select id="course_id" name="course_id" class="form-select">
                            <option value="id">-- Chọn Khóa Học --</option>
                            <?php foreach ($courses as $course) : ?>
                                <option value="<?= $course['id']; ?>" 
                                    <?= (isset($course_id) && $course_id == $course['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($course['title']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['course_id'])) : ?>
                            <span class="error text-danger"><?= htmlspecialchars($errors['course_id']); ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Chọn Section -->
                    <div class="mb-3">
                        <label class="form-label">Chọn Section:</label>
                        <select id="section_id" name="section_id" class="form-select">
                            <option value="">-- Chọn Section --</option>
                            <?php foreach ($sections as $section) : ?>
                            <option value="<?= $section['id']; ?>" <?= ($section_id == $section['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($section['title']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['section_id'])) : ?>
                            <span class="error text-danger"><?= htmlspecialchars($errors['section_id']); ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Chọn bài kiểm tra -->
                    <div class="mb-3">
                        <label class="form-label">Chọn Bài Kiểm Tra:</label>
                        <select id="quiz_id" name="quiz_id" class="form-select">
                            <option value="">-- Chọn Bài Kiểm Tra --</option>
                            <?php foreach ($quizs as $quiz) : ?>
                            <option value="<?= $quiz['id']; ?>" <?= ($quiz_id == $quiz['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($quiz['title']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['quiz_id'])) : ?>
                            <span class="error text-danger"><?= htmlspecialchars($errors['quiz_id']); ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Nhập câu hỏi -->
                    <div class="mb-3">
                        <label class="form-label">Câu hỏi:</label>
                        <input type="text" name="question" class="form-control" value="<?= htmlspecialchars($question); ?>">
                        <?php if (isset($errors['question'])) : ?>
                            <span class="error text-danger"><?= htmlspecialchars($errors['question']); ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Chọn loại câu hỏi -->
                    <div class="mb-3">
                        <label class="form-label">Loại câu hỏi:</label>
                        <select name="type" class="form-select">
                            <?php foreach ($types as $typeOption) : ?>
                            <option value="<?= $typeOption; ?>" <?= ($type == $typeOption) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($typeOption); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['type'])) : ?>
                            <span class="error text-danger"><?= htmlspecialchars($errors['type']); ?></span>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Cập Nhật Câu Hỏi</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    var courseDropdown = document.getElementById("course_id");
    var sectionDropdown = document.getElementById("section_id");
    var quizDropdown = document.getElementById("quiz_id");

    // Sự kiện khi chọn khóa học
    courseDropdown.addEventListener("change", function () {
        var courseId = this.value;
        console.log("📌 Đã chọn khóa học ID:", courseId);

        // Reset danh sách Section và Quiz
        sectionDropdown.innerHTML = '<option value="">-- Chọn phần học --</option>';
        quizDropdown.innerHTML = '<option value="">-- Chọn bài kiểm tra --</option>';

        if (courseId) {
            fetch(`/instructor/getSections?course_id=${courseId}`)
                .then(response => response.json())
                .then(data => {
                    console.log("✅ Dữ liệu sections:", data);

                    if (data.data.length > 0) {
                        data.data.forEach(section => {
                            var option = new Option(section.title, section.id);
                            sectionDropdown.add(option);
                        });
                    } else {
                        console.warn("⚠️ Không có section nào!");
                    }
                })
                .catch(error => console.error("❌ Lỗi khi tải sections:", error));
        }
    });

    // Sự kiện khi chọn section
    sectionDropdown.addEventListener("change", function () {
        var courseId = courseDropdown.value;
        var sectionId = this.value;
        console.log("📌 Đã chọn section ID:", sectionId, "của khóa học ID:", courseId);

        // Reset danh sách Quiz
        quizDropdown.innerHTML = '<option value="">-- Chọn bài kiểm tra --</option>';

        if (courseId && sectionId) {
            fetch(`/instructor/getQuizzes?course_id=${courseId}&section_id=${sectionId}`)
                .then(response => response.json())
                .then(data => {
                    console.log("✅ Dữ liệu quizzes:", data);

                    if (data.data.length > 0) {
                        data.data.forEach(quiz => {
                            var option = new Option(quiz.title, quiz.id);
                            quizDropdown.add(option);
                        });
                    } else {
                        console.warn("⚠️ Không có bài kiểm tra nào cho section này!");
                    }
                })
                .catch(error => console.error("❌ Lỗi khi tải quizzes:", error));
        }
    });
});

</script>