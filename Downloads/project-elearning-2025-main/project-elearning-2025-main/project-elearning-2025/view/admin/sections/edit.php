<!-- file: view/sections/edit.php -->
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Chỉnh sửa Section</h1>
    <div class="">
        <div class="card-body">
            <form action="/admin/sections/update/<?= $section['id'] ?>" method="POST">
                <input type="hidden" name="id" value="<?= $section['id'] ?>">

                <div class="mb-3">
                    <label>Khóa học</label>
                    <select name="course_id" class="form-control" required>
                        <option value="">-- Chọn khóa học --</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['id'] ?>"
                                <?= ($course['id'] == $section['course_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($course['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tiêu đề Section</label>
                    <input type="text" name="title" class="form-control" 
                           value="<?= htmlspecialchars($section['title']) ?>" required>
                </div>

                <div class="mb-3">
                    <label>Mô tả</label>
                    <textarea name="description" class="form-control"><?= htmlspecialchars($section['description']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label>Thứ tự (order_number)</label>
                    <input type="number" name="order_number" class="form-control" 
                           value="<?= htmlspecialchars($section['order_number']) ?>">
                </div>

                <button type="submit" class="btn btn-success">Lưu thay đổi</button>
            </form>
        </div>
    </div>
</div>
