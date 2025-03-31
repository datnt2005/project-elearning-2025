<div class="container mt-20 p-4">
    <h2>Thêm bài tập</h2>

    <!-- Form bắt buộc kiểm tra tính hợp lệ -->
    <form action="" method="post" novalidate class="needs-validation">
        
        <!-- Khoá học -->
        <div class="mb-3">
            <label for="course_id">Khoá học</label>
            <select class="form-control" name="course_id" id="course_id">
            <option value="">-- Chọn khoá học --</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?= htmlspecialchars($course['id']); ?>" <?= $course['id'] == $course_id ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($course['title']); ?>
                </option>
            <?php endforeach; ?>
        </select>

            <div class="invalid-feedback"> <?=$errors['course_id'] ?? 'Vui lòng chọn khoá học' ?> </div>
        </div>

        <!-- Phần học -->
        <div class="mb-3">
            <label class="form-label" for="section_id">Phần học</label>
            <select class="form-control" name="section_id" id="section_id">
                <option value="">-- Chọn phần học --</option>
            </select>
            <div class="invalid-feedback"> <?=$errors['section_id'] ?? 'Vui lòng chọn phần học' ?> </div>
            <?php if (!empty($errors['section_id'])): ?>
                <p style="color: red;"><?= $errors['section_id']; ?></p>
            <?php endif; ?>
        </div>

        <!-- Câu hỏi -->
        <div class="mb-3">
            <label class="form-label" for="title">Câu hỏi</label>
            <input class="form-control" type="text" name="title" id="title" placeholder="Nhập câu hỏi" value="<?= isset($title) ? htmlspecialchars($title) : ''; ?>">
            <?php if (!empty($errors['title'])): ?>
                <p style="color: red;"><?= $errors['title']; ?></p>
            <?php endif; ?>
        </div>

        <!-- Mô tả -->
        <div class="mb-3">
            <label class="form-label" for="description">Mô tả</label>
            <textarea class="form-control" name="description" id="description" placeholder="Nhập mô tả"><?= isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
            <?php if (!empty($errors['description'])): ?>
                <p style="color: red;"><?= $errors['description']; ?></p>
            <?php endif; ?>
        </div>

        <br>

        <!-- Nút Thêm -->
        <button class="btn btn-primary" type="submit">Thêm</button>
    </form>

</div>

<!-- Bootstrap & JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Lấy danh sách section theo khóa học
    var sectionsData = <?= json_encode($sectionsByCourse); ?>;

    // Lắng nghe sự kiện thay đổi khóa học
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

    // Khi trang tải lại, tự động cập nhật phần học dựa trên khóa học đã chọn
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById("course_id").dispatchEvent(new Event("change"));
    });

    // Xử lý validation Bootstrap
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })();
</script>