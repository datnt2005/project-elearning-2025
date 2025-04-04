<style>
:root {
    --header-bg: #343a40;
    --primary-btn: #dc3545;
    --primary-btn-hover: #bb2d3b;
    --text-light: #f8f9fa;
    --border-color: rgba(255,255,255,0.1);
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary-btn);
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}

.btn-add {
    background-color: var(--primary-btn);
    border-color: var(--primary-btn);
    color: var(--text-light);
}

.btn-add:hover {
    background-color: var(--primary-btn-hover);
    border-color: var(--primary-btn-hover);
    transform: translateY(-1px);
}
</style>

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center" style="color: var(--header-bg);">Chỉnh sửa Quiz</h1>
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Chỉnh sửa Quiz</h5>
        </div>
        <div class="card-body">
            <h2>Sửa bài kiểm tra</h2>

            <form action="" method="post">
                <div class="mb-3">
                    <label for="course_id" class="form-label">Chọn Khoá Học:</label>
                    <select class="form-control" id="course_id" name="course_id">
                        <option value="">-- Chọn khóa học --</option>
                        <?php foreach ($courses as $course) : ?>
                        <option value="<?= $course['id']; ?>" <?= ($course_id == $course['id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($course['title']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback"><?= $errors['course_id'] ?? 'Vui lòng chọn khoá học!' ?></div>
                </div>

                <!-- Chọn Phần Học -->
                <div class="mb-3">
                    <label for="section_id" class="form-label">Chọn Phần Học:</label>
                    <select class="form-control" id="section_id" name="section_id">
                        <option value="">-- Chọn phần học --</option>
                        <?php if (!empty($sectionsByCourse[$course_id])): ?>
                            <?php foreach ($sectionsByCourse[$course_id] as $section) : ?>
                                <option value="<?= $section['id']; ?>" <?= ($section_id == $section['id']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($section['title']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>


                    <div class="invalid-feedback"><?= $errors['section_id'] ?? 'Vui lòng chọn phần học!' ?></div>
                </div>

                <div>
                    <label class="form-label" for="title">Câu hỏi</label>
                    <input class="form-control" type="text" name="title" id="title" placeholder="Nhập câu hỏi"
                        value="<?= htmlspecialchars($title); ?>">
                    <?php if (!empty($errors['title'])): ?>
                    <p style="color: red;"><?= $errors['title']; ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label class="form-label" for="description">Mô tả</label>
                    <textarea class="form-control" name="description" id="description"
                        placeholder="Nhập mô tả"><?= htmlspecialchars($description); ?></textarea>
                    <?php if (!empty($errors['description'])): ?>
                    <p style="color: red;"><?= $errors['description']; ?></p>
                    <?php endif; ?>
                </div>
                <br>

                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-add">
                        <i class="fas fa-save me-1"></i> Cập nhật
                    </button>
                    <a href="/admin/quizzes" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var sectionsData = <?= json_encode($sectionsByCourse); ?>;

    document.getElementById("course_id").addEventListener("change", function () {
        var courseId = this.value;
        var sectionDropdown = document.getElementById("section_id");

        // Xóa các option cũ
        sectionDropdown.innerHTML = '<option value="">-- Chọn phần học --</option>';

        // Nếu có dữ liệu, thêm vào dropdown
        if (courseId && sectionsData[courseId]) {
            sectionsData[courseId].forEach(section => {
                var option = document.createElement("option");
                option.value = section.id;
                option.textContent = section.title;
                sectionDropdown.appendChild(option);
            });
        }
    });

    // Load lại phần học nếu đã chọn khóa học trước đó
    window.onload = function() {
        document.getElementById("course_id").dispatchEvent(new Event("change"));
    };
</script>