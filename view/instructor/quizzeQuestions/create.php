<div class="container-fluid px-4">
    <h1 class="mt-4 text-center" style="color: var(--header-bg);">Thêm Câu Hỏi</h1>
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Thêm câu hỏi mới</h5>
        </div>
        <div class="card-body">
            <style>
            :root {
                --header-bg: #343a40;
                --primary-btn: #dc3545;
                --primary-btn-hover: #bb2d3b;
                --text-light: #f8f9fa;
                --border-color: rgba(255,255,255,0.1);
            }

            .error {
                color: var(--primary-btn);
            }

            .form-control:focus,
            .form-select:focus {
                border-color: var(--primary-btn);
                box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
            }

            .btn-primary {
                background-color: var(--primary-btn);
                border-color: var(--primary-btn);
                color: var(--text-light);
            }

            .btn-primary:hover {
                background-color: var(--primary-btn-hover);
                border-color: var(--primary-btn-hover);
                transform: translateY(-1px);
            }
            </style>

            <form method="POST" enctype="multipart/form-data" class="border p-4 rounded shadow bg-white">
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

                <!-- Chọn quiz_id -->
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
                    <span class="error"><?= $errors['quiz_id']; ?></span>
                <?php endif; ?>

                <!-- Câu hỏi và file -->
                <div class="mb-3" style="display: flex; flex-direction: row; gap: 10px;">
                    <div class="mb-2" style="flex: 1;">
                        <label class="form-label">Câu hỏi:</label>
                        <input type="text" name="question" class="form-control" value="<?= htmlspecialchars($question); ?>">
                    </div>
                    <div class="mb-2" style="flex: 1;">
                        <label for="file" class="form-label">File câu hỏi và câu trả lời</label>
                        <input type="file" name="file" id="file" class="form-control">
                    </div>
                </div>

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

        </div>
    </div>
</div>