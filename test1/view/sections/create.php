<!-- file: view/sections/create.php -->
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Thêm Section</h1>
    <div class="card">
        <div class="card-body">
            <form action="/sections/store" method="POST">
                <div class="mb-3">
                    <label>Khóa học</label>
                    <select name="course_id" class="form-control" required>
                        <option value="">-- Chọn khóa học --</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['id'] ?>">
                                <?= htmlspecialchars($course['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tiêu đề Section</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Mô tả</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label>Thứ tự (order_number)</label>
                    <input type="number" name="order_number" class="form-control" value="0">
                </div>

                <button type="submit" class="btn btn-submit">Thêm Section</button>
            </form>
        </div>
    </div>
</div>
