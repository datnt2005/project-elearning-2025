<main class="ml-24 pt-20 px-4">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-2xl">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">
            📘 Quiz cho Section: <?= htmlspecialchars($section['title'] ?? 'Không xác định') ?>
        </h2>

        <form id="quiz-form">
            <?php foreach ($questions as $quizQuestions): ?>
            <?php foreach ($quizQuestions as $question): ?>
            <div class="question" data-id="<?= $question['id'] ?>">
                <p><strong><?= htmlspecialchars($question['question']) ?></strong></p>

                <?php foreach ($question['answers'] as $answer): ?>
                <label>
                    <input type="radio" name="question_<?= $question['id'] ?>" value="<?= $answer['id'] ?>"
                        data-correct="<?= $answer['is_correct'] ?>">
                    <?= htmlspecialchars($answer['answer']) ?>
                </label><br>
                <?php endforeach; ?>

                <p class="feedback text-sm font-semibold mt-2"></p>
            </div>
            <hr>
            <?php endforeach; ?>
            <?php endforeach; ?>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Nộp bài</button>
        </form>
        <br>
        <a href="javascript:history.back()">🔙 Trở về phần học</a>


    </div>


</main>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const quizForm = document.getElementById("quiz-form");

    quizForm.addEventListener("submit", function(event) {
        event.preventDefault(); // Ngăn form gửi đi

        let correctCount = 0; // Đếm số câu đúng
        let totalQuestions = document.querySelectorAll(".question").length; // Tổng số câu hỏi

        document.querySelectorAll(".question").forEach((question) => {
            let selectedAnswer = question.querySelector("input[type='radio']:checked");
            let feedback = question.querySelector(".feedback");

            if (selectedAnswer) {
                let isCorrect = selectedAnswer.dataset.correct === "1"; // Kiểm tra đáp án đúng

                if (isCorrect) {
                    feedback.textContent = "✅ Bạn đã trả lời đúng!";
                    feedback.style.color = "green";
                    correctCount++;
                } else {
                    feedback.textContent = "❌ Bạn đã trả lời sai, vui lòng kiểm tra lại!";
                    feedback.style.color = "red";
                }
            } else {
                feedback.textContent = "⚠️ Bạn chưa chọn đáp án!";
                feedback.style.color = "orange";
            }
        });

        // Hiển thị kết quả tổng thể
        setTimeout(() => {
            alert(`Bạn đã trả lời đúng ${correctCount}/${totalQuestions} câu!`);
            // history.back(); // 🔙 Quay lại trang trước sau khi nộp bài
        }, 1000); // Đợi 1 giây trước khi quay lại
    });
});
</script>