<div class="container mt-4">
    <h2 class="mb-4">Sửa Câu Hỏi</h2>

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
            fetch(`/admin/getSections?course_id=${courseId}`)
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
            fetch(`/admin/getQuizzes?course_id=${courseId}&section_id=${sectionId}`)
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


<!-- <script>
    document.getElementById("course_id").addEventListener("change", function() {
        let courseId = this.value;
        let sectionSelect = document.getElementById("section_id");
        let quizSelect = document.getElementById("quiz_id");

        // Xóa danh sách section cũ
        sectionSelect.innerHTML = '<option value="">-- Chọn Section --</option>';
        quizSelect.innerHTML = '<option value="">-- Chọn Bài Kiểm Tra --</option>';

        if (courseId) {
            fetch(`/admin/getSections?course_id=${courseId}`)
                .then(response => response.json())
                .then(data => {
                    data.sections.forEach(section => {
                        let option = document.createElement("option");
                        option.value = section.id;
                        option.textContent = section.title;
                        sectionSelect.appendChild(option);
                    });
                })
                .catch(error => console.error("Lỗi khi tải sections:", error));
        }
    });

    document.getElementById("section_id").addEventListener("change", function() {
        let sectionId = this.value;
        let quizSelect = document.getElementById("quiz_id");

        // Xóa danh sách quiz cũ
        quizSelect.innerHTML = '<option value="">-- Chọn Bài Kiểm Tra --</option>';

        if (sectionId) {
            fetch(`/admin/getQuizzes?section_id=${sectionId}`)
                .then(response => response.json())
                .then(data => {
                    data.quizzes.forEach(quiz => {
                        let option = document.createElement("option");
                        option.value = quiz.id;
                        option.textContent = quiz.title;
                        quizSelect.appendChild(option);
                    });
                })
                .catch(error => console.error("Lỗi khi tải quizzes:", error));
        }
    });
</script> -->