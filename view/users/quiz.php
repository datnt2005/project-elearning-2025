<main class="ml-24 pt-20 px-4">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-1xl">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">
            📘 Quiz cho Section: <?= htmlspecialchars($section['title'] ?? 'Không xác định') ?>
        </h2>

        <div id="completed-banner" class="hidden bg-green-100 text-green-800 font-bold p-3 rounded mb-4">
            ✅ Bạn đã hoàn thành bài kiểm tra này!
        </div>

        <p id="timer" class="text-red-500 font-bold text-lg">⏳ Còn lại: 60s</p>

        <form id="quiz-form">
            <?php foreach ($questions as $quizQuestions): ?>
            <?php foreach ($quizQuestions as $question): ?>
            <div class="question p-4 border rounded mb-4" data-id="<?= $question['id'] ?>">
                <p class="font-semibold"><?= htmlspecialchars($question['question']) ?></p>

                <?php if ($question['type'] === 'multiple_choice'): ?>
                <?php foreach ($question['answers'] as $answer): ?>
                <label class="block mt-2">
                    <input type="radio" name="question_<?= $question['id'] ?>" value="<?= $answer['id'] ?>"
                        data-correct="<?= $answer['is_correct'] ?>">
                    <?= htmlspecialchars($answer['answer']) ?>
                </label>
                <?php endforeach; ?>

                <?php elseif ($question['type'] === 'short_answer'): ?>
                <div class="mt-2">
                    <p class="text-gray-600 text-sm italic">Nhập câu trả lời</p>
                    <?php foreach ($question['answers'] as $answer): ?>
                    <input type="text" name="question_<?= $question['id'] ?>"
                        class="border px-2 py-1 rounded w-full mt-1" placeholder="Nhập câu trả lời của bạn tại đây">
                    <?php endforeach; ?>
                    <!-- Nếu muốn dùng để kiểm tra đúng/sai ở client-side -->
                    <input type="hidden" class="correct-answer" data-question-id="<?= $question['id'] ?>"
                        value="<?= htmlspecialchars($question['answers'][0]['answer']) ?>">
                </div>
                <?php endif; ?>
               

                <p class="feedback text-sm font-semibold mt-2"></p>
            </div>
            <?php endforeach; ?>
            <?php endforeach; ?>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Nộp bài</button>
        </form>

        <div id="quiz-result" class="hidden mt-4 p-4 border rounded bg-gray-100"></div>
        <br>
        <a href="javascript:history.back()" id="back-button" class="hidden">Trở về phần học</a>
    </div>
</main>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const quizForm = document.getElementById("quiz-form");
    const quizResult = document.getElementById("quiz-result");
    const timerDisplay = document.getElementById("timer");
    const backButton = document.getElementById("back-button");

    let timeLeft = 60;
    let timer;
    let answers = {};

    startTimer();
    restoreAnswers();
    handleShortAnswerClicks();

    quizForm.addEventListener("submit", handleSubmit);

    function startTimer() {
        timer = setInterval(() => {
            timeLeft--;
            timerDisplay.textContent = `⏳ Còn lại: ${timeLeft}s`;
            if (timeLeft <= 0) {
                clearInterval(timer);
                quizForm.dispatchEvent(new Event("submit"));
            }
        }, 1000);
    }

    function restoreAnswers() {
    const saved = JSON.parse(localStorage.getItem("quizAnswers") || "{}");

    for (const questionId in saved) {
        const value = saved[questionId];

        // Nếu là câu hỏi trắc nghiệm
        const radio = document.querySelector(`input[name="question_${questionId}"][value="${value}"]`);
        if (radio) {
            radio.checked = true;
            continue;
        }

        // Nếu là câu hỏi tự luận (short_answer)
        const textInput = document.querySelector(`input[type="text"][name="question_${questionId}"]`);
        if (textInput) {
            textInput.value = value;
            continue;
        }

        // Nếu bạn có kiểu button gợi ý (có thể bỏ nếu không dùng nữa)
        const hidden = document.querySelector(`input[type="hidden"][name="question_${questionId}"]`);
        if (hidden) {
            hidden.value = value;

            const parent = document.querySelector(`.question[data-id="${questionId}"]`);
            parent?.querySelectorAll(".answer-btn").forEach(btn => {
                btn.classList.toggle("bg-blue-500", btn.dataset.value.toLowerCase() === value.toLowerCase());
                btn.classList.toggle("text-white", btn.dataset.value.toLowerCase() === value.toLowerCase());
            });
        }
    }
}


    function handleSubmit(e) {
    e.preventDefault();
    answers = {};
    let correctCount = 0;
    const questions = document.querySelectorAll(".question");

    questions.forEach((q) => {
        const qid = q.dataset.id;
        const selected = q.querySelector("input[type='radio']:checked");
        const feedback = q.querySelector(".feedback");

        // Câu hỏi trắc nghiệm
        if (selected) {
            const val = selected.value;
            const isCorrect = selected.dataset.correct === "1";
            answers[qid] = val;
            feedback.textContent = isCorrect ? "Đúng!" : "Sai!";
            feedback.style.color = isCorrect ? "green" : "red";
            if (isCorrect) correctCount++;
        }

        // Câu hỏi tự luận (short_answer)
        else {
            const textInput = q.querySelector("input[type='text']");
            const correctAnswerInput = q.querySelector(".correct-answer");

            if (textInput && correctAnswerInput) {
                const userVal = normalize(textInput.value);
                const correctVal = normalize(correctAnswerInput.value);

                answers[qid] = userVal;

                const isCorrect = userVal === correctVal;
                feedback.textContent = isCorrect ? "Đúng!" : "Sai!";
                feedback.style.color = isCorrect ? "green" : "red";
                if (isCorrect) correctCount++;
            } else {
                feedback.textContent = "Chưa trả lời!";
                feedback.style.color = "orange";
            }
        }
    });

    localStorage.setItem("quizAnswers", JSON.stringify(answers));
    clearInterval(timer);
    showResult(correctCount, questions.length);
}

// ✅ Hàm chuẩn hóa câu trả lời (không phân biệt hoa/thường, loại bỏ khoảng trắng dư)
function normalize(text) {
    return text.toLowerCase().trim().replace(/\s+/g, ' ');
}


    function showResult(correct, total) {
        quizResult.classList.remove("hidden");

        if (correct < total) {
            quizResult.innerHTML = `
                <h3 class="font-bold text-lg text-red-500">Bạn chưa làm đúng tất cả câu hỏi.</h3>
                <p class="text-gray-700">Bạn đúng ${correct}/${total} câu. Hãy làm lại để được lưu kết quả.</p>
                <button class="bg-yellow-500 text-white px-4 py-2 rounded mt-2" onclick="location.reload()">Làm lại</button>
            `;
        } else {
            // Lưu trạng thái đã hoàn thành vào localStorage
            localStorage.setItem("quizCompleted", "true");

            // Hiển thị thông báo đã hoàn thành
            const completedBanner = document.getElementById("completed-banner");
            completedBanner.classList.remove("hidden");

            // Cập nhật dữ liệu để gửi đi
            const requestData = {
                section_id: <?php echo $section['id']; ?>, // Chỉnh sửa ở đây
                correct_count: correct,
                total_questions: total,
                answers: Object.entries(answers).map(([id, answer]) => ({
                    question_id: id,
                    answer
                }))
            };

            console.log("Dữ liệu gửi lên:", requestData); // In dữ liệu trước khi gửi

            fetch("http://localhost:8000/view/users/save_quiz_result.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(requestData),
                })
                .then(response => response.json()) // Chờ phản hồi từ server
                .then(data => {
                    console.log("Phản hồi từ server:", data); // In phản hồi từ server
                    if (data.success) {
                        quizResult.innerHTML = `
                        <h3 class="font-bold text-lg text-green-600">🎉 Hoàn thành xuất sắc!</h3>
                        <p class="text-gray-700">Bạn đã trả lời đúng tất cả ${total} câu.</p>
                    `;
                        backButton.classList.remove("hidden");
                    } else {
                        quizResult.innerHTML = `
                        <h3 class="font-bold text-lg text-red-500">Có lỗi xảy ra</h3>
                        <p class="text-gray-700">${data.message}</p>
                    `;
                    }
                })
                .catch(error => {
                    console.error("Lỗi khi gửi dữ liệu:", error); // In lỗi nếu có
                    quizResult.innerHTML = `
                    <h3 class="font-bold text-lg text-red-500">Có lỗi xảy ra</h3>
                    <p class="text-gray-700">Không thể lưu kết quả. Vui lòng thử lại sau.</p>
                `;
                });
        }
    }


   // lưu vào localstorage

    document.querySelectorAll("input[type='text'][name^='question_']").forEach(input => {
    input.addEventListener("input", () => {
        const name = input.name; // ví dụ: question_123
        const value = input.value.trim();
        const saved = JSON.parse(localStorage.getItem("quizAnswers") || "{}");
        saved[name.replace("question_", "")] = value;
        localStorage.setItem("quizAnswers", JSON.stringify(saved));
    });
});



});
</script>