<div class="container mt-20 p-4" >
<h2>Thêm bài tập</h2>

<form  action="" method="post">
       <div class="mb-3">
            <label class="form-label" for="section_id">Phần học</label>
            <select class="form-control" name="section_id" id="section_id">
                <option value="">Chọn Phần học</option>
                <?php foreach ($sections as $section): ?>
                    <option value="<?= $section['id']; ?>" <?= isset($section_id) && $section_id == $section['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($section['title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['section_id'])): ?>
                <p style="color: red;"><?= $errors['section_id']; ?></p>
            <?php endif; ?>
        </div>

    <div>
        <label class="form-label" for="title">Câu hỏi</label>
        <input class="form-control" type="text" name="title" id="title" placeholder="Nhập câu hỏi" value="<?= isset($title) ? htmlspecialchars($title) : ''; ?>">
        <?php if (!empty($errors['title'])): ?>
            <p style="color: red;"><?= $errors['title']; ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label class="form-label" for="description">Mô tả</label>
        <textarea class="form-control" name="description" id="description" placeholder="Nhập mô tả"><?= isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
        <?php if (!empty($errors['description'])): ?>
            <p style="color: red;"><?= $errors['description']; ?></p>
        <?php endif; ?>
    </div>
    <br>

    <button class="btn btn-primary" type="submit">Thêm</button>
</form>

</div>
