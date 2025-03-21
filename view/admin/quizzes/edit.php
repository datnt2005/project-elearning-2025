<h2>Sửa bài tập</h2>

<form action="" method="post">
    <div class="mb-3">
        <label class="form-label" for="section_id">Bài học</label>
        <select class="form-control" name="section_id" id="section_id">
            <option value="">Chọn bài học</option>
            <?php if (!empty($sections)): ?>
                <?php foreach ($sections as $section): ?>
                    <option value="<?= $section['id']; ?>" <?= isset($section_id) && $section_id == $section['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($section['title']); ?>
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="">Không có bài học nào</option>
            <?php endif; ?>
        </select>
        <?php if (!empty($errors['section_id'])): ?>
            <p style="color: red;"><?= $errors['section_id']; ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label class="form-label" for="title">Câu hỏi</label>
        <input class="form-control" type="text" name="title" id="title" placeholder="Nhập câu hỏi"
            value="<?= isset($title) ? htmlspecialchars($title) : htmlspecialchars($quiz['title'] ?? ''); ?>">
        <?php if (!empty($errors['title'])): ?>
            <p style="color: red;"><?= $errors['title']; ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label class="form-label" for="description">Mô tả</label>
        <textarea class="form-control" name="description" id="description" placeholder="Nhập mô tả"><?= isset($description) ? htmlspecialchars($description) : htmlspecialchars($quiz['description'] ?? ''); ?></textarea>
        <?php if (!empty($errors['description'])): ?>
            <p style="color: red;"><?= $errors['description']; ?></p>
        <?php endif; ?>
    </div>
    <br>

    <button class="btn btn-primary" type="submit">Sửa</button>
</form>
