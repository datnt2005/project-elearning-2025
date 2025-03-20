<h2>Sửa bài tập</h2>

<form action="" method="post">
    <div class="mb-3">
        <label class="form-label" for="lesson_id">Bài học</label>
        <select class="form-control" name="lesson_id" id="lesson_id">
            <option value="">Chọn bài học</option>
            <?php foreach ($lessons as $lesson): ?>
                <option value="<?= $lesson['id']; ?>" <?= isset($lesson_id) && $lesson_id == $lesson['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($lesson['title']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($errors['lesson_id'])): ?>
            <p style="color: red;"><?= $errors['lesson_id']; ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label class="form-label" for="title">Câu hỏi</label>
        <input value="<?= $quiz['title']; ?>" class="form-control" type="text" name="title" id="title" placeholder="Nhập câu hỏi" value="<?= isset($title) ? htmlspecialchars($title) : ''; ?>">
        <?php if (!empty($errors['title'])): ?>
            <p style="color: red;"><?= $errors['title']; ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label class="form-label" for="description">Mô tả</label>
        <textarea value="<?= $quiz['description']; ?>" class="form-control" name="description" id="description" placeholder="Nhập mô tả"><?= isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
        <?php if (!empty($errors['description'])): ?>
            <p style="color: red;"><?= $errors['description']; ?></p>
        <?php endif; ?>
    </div>
    <br>

    <button class="btn btn-primary" type="submit">Sửa</button>
</form>
