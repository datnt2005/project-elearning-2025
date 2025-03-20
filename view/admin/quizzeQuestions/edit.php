<h2>Sửa Câu hỏi</h2>

<form action="" method="post">
    <div class="mb-3">
        <label class="form-label" for="quiz_id">Bài Kiểm tra</label>
        <select class="form-control" name="quiz_id" id="quiz_id">
            <option value="">Chọn bài Kiểm tra</option>
            <?php foreach ($quizs as $quiz): ?>
            <option value="<?= $quiz['id']; ?>" value="<?= $quiz['id']; ?>" <?= isset($quiz_id) && $quiz_id == $quiz['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($quiz['title']); ?>
            </option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($errors['quiz_id'])): ?>
        <p style="color: red;"><?= $errors['quiz_id']; ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label class="form-label" for="question">Câu hỏi</label>
        <input value="<?= $question; ?>" class="form-control" type="text" name="question" id="question" 
               value="<?= htmlspecialchars($question); ?>">
        <?php if (!empty($errors['question'])): ?>
            <p style="color: red;"><?= $errors['question']; ?></p>
        <?php endif; ?>
    </div>
    <div>
        <label class="form-label" for="type">Kiểu câu hỏi</label>
        <select name="type" id="type" class="form-control">
            <option value="">Chọn kiểu kiểm tra</option>
            <?php foreach ($types as $option): ?>
            <option  value="<?= $option; ?>" <?= ($type == $option) ? 'selected' : '' ?>>
                <?= ucfirst(str_replace('_', ' ', $option)); ?>
            </option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($errors['type'])): ?>
        <p style="color: red;"><?= $errors['type']; ?></p>
        <?php endif; ?>
    </div>

    <br>

    <button class="btn btn-primary" type="submit">Sửa</button>
</form>