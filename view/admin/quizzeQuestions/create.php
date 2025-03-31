<style>
    .error {
        color:red;
    }
</style>
<form method="POST" class="border p-4 rounded shadow bg-white">
    <!-- Giữ course_id khi submit -->
    <div class="mb-3">
        <label class="form-label">Chọn Khóa Học:</label>
        <select name="course_id" class="form-select" onchange="this.form.submit()">
            <option value="">-- Chọn Khóa Học --</option>
            <?php foreach ($courses as $course) : ?>
                <option value="<?= $course['id']; ?>" <?= ($course_id == $course['id']) ? 'selected' : '' ?>>
                    <?= $course['title']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <?php if (isset($errors['course_id'])) : ?>
         <span class='error'><?= $errors['course_id']; ?></span>

     <?php endif; ?>

    <!-- Giữ section_id khi submit -->
    <div class="mb-3">
        <label class="form-label">Chọn Section:</label>
        <select name="section_id" class="form-select" onchange="this.form.submit()">
            <option value="">-- Chọn Section --</option>
            <?php foreach ($sections as $section) : ?>
                <option value="<?= $section['id']; ?>" <?= ($section_id == $section['id']) ? 'selected' : '' ?>>
                    <?= $section['title']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php if (isset($errors['section_id'])) : ?>
        <span class="error"><?= $errors['section_id']; ?></span>
    <?php endif; ?>

    <div class="mb-3">
        <label class="form-label">Chọn Bài Kiểm Tra:</label>
        <select name="quiz_id" class="form-select">
            <option value="">-- Chọn Bài Kiểm Tra --</option>
            <?php foreach ($quizs as $quiz) : ?>
                <option value="<?= $quiz['id']; ?>" <?= ($quiz_id == $quiz['id']) ? 'selected' : '' ?>>
                    <?= $quiz['title']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php if (isset($errors['quiz_id'])) : ?>
        <p class="error"><?= $errors['quiz_id']; ?></p>
    <?php endif; ?>

    <div class="mb-3">
        <label class="form-label">Câu hỏi:</label>
        <input type="text" name="question" class="form-control" value="<?= htmlspecialchars($question); ?>">
    </div>
    <?php if (isset($errors['question'])) : ?>
        <p class="error"><?= $errors['question']; ?></p>
    <?php endif; ?>

    <div class="mb-3">
        <label class="form-label">Loại câu hỏi:</label>
        <select name="type" class="form-select">
            <?php foreach ($types as $typeOption) : ?>
                <option value="<?= $typeOption; ?>" <?= ($type == $typeOption) ? 'selected' : '' ?>>
                    <?= $typeOption; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php if (isset($errors['type'])) : ?>
        <span class="error"><?= $errors['type']; ?></span>
    <?php endif; ?>    

    <button type="submit" name="submit_question" class="btn btn-primary w-100">Thêm Câu Hỏi</button>
</form>