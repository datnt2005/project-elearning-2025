<h2>Thêm câu trả lời</h2>

<?php if (!empty($_SESSION['success_message'])) : ?>
    <div class="alert alert-success"><?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
<?php endif; ?>

<form action="" method="POST" class="quiz-form">
    <label for="question_id">Chọn câu hỏi:</label>
    <select name="question_id" class="input-field">
        <option value="">-- Chọn câu hỏi --</option>
        <?php foreach ($questions as $question) : ?>
            <option value="<?= $question['id'] ?>" <?= isset($question_id) && $question_id == $question['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($question['question']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <p class="error"><?= $errors['question_id'] ?? '' ?></p>

    <label>Nhập các câu trả lời:</label>
    <div id="answers">
        <?php if (!empty($answers)) : ?>
            <?php foreach ($answers as $index => $answer) : ?>
                <div class="answer-group">
                    <input type="text" name="answers[]" value="<?= htmlspecialchars($answer) ?>" class="input-field" >
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_correct[<?= $index ?>]" value="1" <?= isset($is_corrects[$index]) ? 'checked' : '' ?>>
                        Đáp án đúng?
                    </label>
                    <button type="button" class="remove-btn" onclick="removeAnswer(this)">❌</button>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="answer-group">
                <input type="text" name="answers[]" class="input-field" >
                <label class="checkbox-label"><input type="checkbox" name="is_correct[0]" value="1"> Đáp án đúng?</label>
                <button type="button" class="remove-btn" onclick="removeAnswer(this)">❌</button>
            </div>
        <?php endif; ?>
    </div>

    <p class="error"><?= $errors['answers'] ?? '' ?></p>

    <button type="button" class="add-btn" onclick="addAnswer()">➕ Thêm đáp án</button>
    <button type="submit" class="submit-btn">Lưu</button>
    <a href="/quizAnswers" type="submit" class="submit-btn">trở về quản lí</a>
</form>

<style>
:root {
    --header-bg: #343a40;
    --primary-btn: #dc3545;
    --primary-btn-hover: #bb2d3b;
    --text-light: #f8f9fa;
    --border-color: rgba(255,255,255,0.1);
}

.container-fluid {
    padding: 20px;
}

h2 {
    text-align: center;
    color: var(--header-bg);
    margin-bottom: 30px;
}

.quiz-form {
    max-width: 800px;
    margin: auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
}

.input-field {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    font-size: 16px;
}

.input-field:focus {
    border-color: var(--primary-btn);
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
    outline: none;
}

.answer-group {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
    padding: 10px;
    border: 1px solid #dee2e6;
    border-radius: 5px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
}

.remove-btn {
    background: var(--primary-btn);
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
}

.remove-btn:hover {
    background: var(--primary-btn-hover);
    transform: translateY(-1px);
}

.add-btn, .submit-btn {
    width: 100%;
    padding: 10px;
    margin-top: 15px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.add-btn {
    background: var(--primary-btn);
    color: white;
    margin-bottom: 10px;
}

.add-btn:hover {
    background: var(--primary-btn-hover);
    transform: translateY(-1px);
}

.submit-btn {
    background: var(--header-bg);
    color: white;
    text-decoration: none;
    text-align: center;
    display: inline-block;
}

.submit-btn:hover {
    background: #23272b;
    transform: translateY(-1px);
}

.error {
    color: var(--primary-btn);
    font-size: 14px;
    margin-top: 5px;
}

.alert {
    margin-bottom: 20px;
    padding: 15px;
    border-radius: 5px;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}
</style>

<script>
function addAnswer() {
    let index = document.querySelectorAll(".answer-group").length;
    let div = document.createElement("div");
    div.classList.add("answer-group");
    div.innerHTML = `
        <input type="text" name="answers[]" class="input-field" required>
        <label class="checkbox-label"><input type="checkbox" name="is_correct[${index}]" value="1"> Đáp án đúng?</label>
        <button type="button" class="remove-btn" onclick="removeAnswer(this)">❌</button>
    `;
    document.getElementById("answers").appendChild(div);
}

function removeAnswer(button) {
    button.parentElement.remove();
}
</script><style>
:root {
    --header-bg: #343a40;
    --primary-btn: #dc3545;
    --primary-btn-hover: #bb2d3b;
    --text-light: #f8f9fa;
    --border-color: rgba(255,255,255,0.1);
}

.container-fluid {
    padding: 20px;
}

h2 {
    text-align: center;
    color: var(--header-bg);
    margin-bottom: 30px;
}

.quiz-form {
    max-width: 800px;
    margin: auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
}

.input-field {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    font-size: 16px;
}

.input-field:focus {
    border-color: var(--primary-btn);
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
    outline: none;
}

.answer-group {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
    padding: 10px;
    border: 1px solid #dee2e6;
    border-radius: 5px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
}

.remove-btn {
    background: var(--primary-btn);
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
}

.remove-btn:hover {
    background: var(--primary-btn-hover);
    transform: translateY(-1px);
}

.add-btn, .submit-btn {
    width: 100%;
    padding: 10px;
    margin-top: 15px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.add-btn {
    background: var(--primary-btn);
    color: white;
    margin-bottom: 10px;
}

.add-btn:hover {
    background: var(--primary-btn-hover);
    transform: translateY(-1px);
}

.submit-btn {
    background: var(--header-bg);
    color: white;
    text-decoration: none;
    text-align: center;
    display: inline-block;
}

.submit-btn:hover {
    background: #23272b;
    transform: translateY(-1px);
}

.error {
    color: var(--primary-btn);
    font-size: 14px;
    margin-top: 5px;
}

.alert {
    margin-bottom: 20px;
    padding: 15px;
    border-radius: 5px;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}
</style>