<main class="ml-24 pt-20 px-4">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-1xl">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">
            📘 Quiz cho Section: <?= htmlspecialchars($section['title'] ?? 'Không xác định') ?>
        </h2>

        <!-- Bộ đếm thời gian -->
        <p id="timer" class="text-red-500 font-bold text-lg">⏳ Còn lại: 60s</p>

        <form id="quiz-form">
            <?php foreach ($questions as $quizQuestions): ?>
            <?php foreach ($quizQuestions as $question): ?>
            <div class="question p-4 border rounded mb-4" data-id="<?= $question['id'] ?>">
                <p class="font-semibold"><?= htmlspecialchars($question['question']) ?></p>

                <?php foreach ($question['answers'] as $answer): ?>
                <label class="block mt-2">
                    <input type="radio" name="question_<?= $question['id'] ?>" value="<?= $answer['id'] ?>"
                        data-correct="<?= $answer['is_correct'] ?>">
                    <?= htmlspecialchars($answer['answer']) ?>
                </label>
                <?php endforeach; ?>

                <p class="feedback text-sm font-semibold mt-2"></p>
            </div>
            <?php endforeach; ?>
            <?php endforeach; ?>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Nộp bài</button>
        </form>

        <!-- Hiển thị kết quả -->
        <div id="quiz-result" class="hidden mt-4 p-4 border rounded bg-gray-100"></div>

        <br>
        <a href="javascript:history.back()">🔙 Trở về phần học</a>

    </div>

</main>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const quizForm = document.getElementById("quiz-form");
    const quizResult = document.getElementById("quiz-result");
    const timerDisplay = document.getElementById("timer");

    let timeLeft = 60; // Thời gian làm bài (giây)
    const timer = setInterval(() => {
        timeLeft--;
        timerDisplay.textContent = `⏳ Còn lại: ${timeLeft}s`;
        if (timeLeft <= 0) {
            clearInterval(timer);
            quizForm.dispatchEvent(new Event("submit")); // Tự động nộp bài khi hết giờ
        }
    }, 1000);

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
                    feedback.textContent = "✅ Đúng!";
                    feedback.style.color = "green";
                    correctCount++;
                } else {
                    feedback.textContent = "❌ Sai!";
                    feedback.style.color = "red";
                }
            } else {
                feedback.textContent = "⚠️ Chưa chọn đáp án!";
                feedback.style.color = "orange";
            }
        });

        // Hiển thị bảng kết quả
        quizResult.classList.remove("hidden");
        quizResult.innerHTML = `
            <h3 class="font-bold text-lg">Kết quả bài làm</h3>
            <p class="text-gray-700">Bạn đã trả lời đúng ${correctCount}/${totalQuestions} câu.</p>
            <button class="bg-green-500 text-white px-4 py-2 rounded mt-2" onclick="location.reload()">Làm lại</button>
        `;

        clearInterval(timer); // Dừng bộ đếm thời gian sau khi nộp bài
    });
});
</script>
