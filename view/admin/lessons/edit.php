<!-- file: view/lessons/edit.php -->
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Chỉnh sửa bài học</h1>
    <div class="">
        <div class="card-body">
            <form action="/admin/lessons/update/<?= $lesson['id'] ?>" method="POST">
                <input type="hidden" name="id" value="<?= $lesson['id'] ?>">

                <div class="mb-3">
                    <label>Phần học (Section)</label>
                    <select name="section_id" class="form-control" required>
                        <option value="">-- Chọn phần học --</option>
                        <?php foreach ($sections as $sec): ?>
                            <option value="<?= $sec['id'] ?>"
                                <?= ($sec['id'] == $lesson['section_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($sec['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tiêu đề bài học</label>
                    <input type="text" name="title" class="form-control" 
                           value="<?= htmlspecialchars($lesson['title']) ?>" required>
                </div>

                <div class="mb-3">
                    <label>Mô tả</label>
                    <textarea name="description" class="form-control"><?= htmlspecialchars($lesson['description']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label>Video URL</label>
                    <input type="text" name="video_url" class="form-control" 
                           value="<?= htmlspecialchars($lesson['video_url']) ?>">
                </div>

                <div class="mb-3">
                    <label>Nội dung (text/html)</label>
                    <textarea name="content" class="form-control"><?= htmlspecialchars($lesson['content']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label>Thứ tự sắp xếp (order_number)</label>
                    <input type="number" name="order_number" class="form-control" 
                           value="<?= htmlspecialchars($lesson['order_number']) ?>">
                </div>

                <button type="submit" class="btn btn-success">Lưu thay đổi</button>
            </form>
        </div>
    </div>
</div>