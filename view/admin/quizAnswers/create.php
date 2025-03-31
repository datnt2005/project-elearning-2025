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
    .quiz-form {
        width: 60%;
        margin: auto;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #333;
    }

    .input-field {
        width: 100%;
        padding: 10px;
        margin: 5px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    .answer-group {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .remove-btn {
        background: #ff4d4d;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
    }

    .remove-btn:hover {
        background: #e60000;
    }

    .add-btn, .submit-btn {
        display: block;
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .add-btn {
        background: #28a745;
        color: white;
    }

    .add-btn:hover {
        background: #218838;
    }

    .submit-btn {
        background: #007bff;
        color: white;
    }

    .submit-btn:hover {
        background: #0056b3;
    }

    .error {
        color: red;
        font-size: 14px;
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
</script>