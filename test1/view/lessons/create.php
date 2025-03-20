<!-- file: view/lessons/create.php -->
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Thêm bài học mới</h1>
    <div class="card">
        <div class="card-body">
            <form action="/lessons/store" method="POST">
                <div class="mb-3">
                    <label>Phần học (Section)</label>
                    <select name="section_id" class="form-control" required>
                        <option value="">-- Chọn phần học --</option>
                        <?php foreach ($sections as $section): ?>
                            <option value="<?= $section['id'] ?>">
                                <?= htmlspecialchars($section['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tiêu đề bài học</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Mô tả</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label>Video URL</label>
                    <input type="text" name="video_url" class="form-control" placeholder="https://youtube.com/...">
                </div>

                <div class="mb-3">
                    <label>Nội dung</label>
                    <textarea name="content" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label>Thứ tự sắp xếp (order_number)</label>
                    <input type="number" name="order_number" class="form-control" value="0">
                </div>

                <button type="submit" class="btn btn-submit">Thêm bài học</button>
            </form>
        </div>
    </div>
</div>
