<style>
:root {
    --header-bg: #343a40;
    --primary-btn: #dc3545;
    --primary-btn-hover: #bb2d3b;
    --text-light: #f8f9fa;
    --border-color: rgba(255,255,255,0.1);
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
}

.card-header {
    background: var(--header-bg) !important;
    border-bottom: 1px solid var(--border-color);
}

.btn-add {
    background-color: var(--primary-btn);
    border-color: var(--primary-btn);
    color: var(--text-light);
}

.btn-add:hover {
    background-color: var(--primary-btn-hover);
    border-color: var(--primary-btn-hover);
    color: var(--text-light);
}

.filter-container {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1.5rem;
}

.filter-group {
    flex: 1;
    min-width: 200px;
}

.search-group {
    flex: 2;
    min-width: 300px;
}

.table thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.btn-group .btn {
    padding: 0.25rem 0.5rem;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
}

/* Update table styles */
.table {
    margin-bottom: 0;
}

.table td {
    vertical-align: middle;
}

/* Update button styles */
.btn-group {
    display: flex;
    gap: 0.25rem;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.btn-outline-info:hover,
.btn-outline-primary:hover,
.btn-outline-danger:hover {
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    .filter-container {
        flex-direction: column;
        gap: 1rem;
    }

    .filter-group,
    .search-group {
        width: 100%;
        min-width: 100%;
    }

    .input-group {
        margin-bottom: 0;
    }

    .btn-group {
        flex-wrap: nowrap;
        gap: 0.5rem;
    }
    
    .btn-group .btn {
        flex: 1;
    }
}
</style>

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center" style="color: var(--header-bg);">Danh sách câu trả lời</h1>
    
    <?php if (!empty($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success_message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-start align-items-center text-white">
            <h5 class="mb-0 me-3">
                <i class="fas fa-check-circle me-2"></i>Quản lý câu trả lời
            </h5>
            <a href="/admin/quizAnswers/create" class="btn btn-add">
                <i class="fas fa-plus me-1"></i> Thêm câu trả lời
            </a>
        </div>
        
        <div class="card-body">
            <div class="filter-container">
                <div class="search-group">
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm câu trả lời...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="filter-group">
                    <select class="form-select" id="questionFilter">
                        <option value="">Tất cả câu hỏi</option>
                    </select>
                </div>
                <div class="filter-group">
                    <select class="form-select" id="resultFilter">
                        <option value="">Tất cả kết quả</option>
                        <option value="correct">Đúng</option>
                        <option value="incorrect">Sai</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Câu hỏi</th>
                            <th>Câu trả lời</th>
                            <th>Kết quả</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($quizAnswers as $quiz) : ?>
                            <tr class="answer-group">
                                <td><?= htmlspecialchars($quiz['question_id']) ?></td>
                                <td class="text-start"><?= htmlspecialchars($quiz['question']) ?></td>
                                <td class="text-start"><?= htmlspecialchars($quiz['answer']) ?></td>
                                <td>
                                    <span class="badge <?= $quiz['is_correct'] == 1 ? 'bg-success' : 'bg-danger' ?>">
                                        <?= $quiz['is_correct'] == 1 ? 'Đúng' : 'Sai' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="/admin/quizAnswers/<?= $quiz['answer_id'] ?>" 
                                           class="btn btn-sm btn-outline-info" title="Xem">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/admin/quizAnswers/update/<?= $quiz['answer_id'] ?>" 
                                           class="btn btn-sm btn-outline-primary" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="confirmDelete(<?= $quiz['answer_id'] ?>)" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function populateQuestionFilter() {
    const rows = document.querySelectorAll('.answer-group');
    const questionFilter = document.getElementById('questionFilter');
    const questions = new Set();

    rows.forEach(row => {
        const question = row.cells[1].textContent.trim();
        if (question !== '') {
            questions.add(question);
        }
    });

    while (questionFilter.options.length > 1) {
        questionFilter.remove(1);
    }

    Array.from(questions).sort().forEach(question => {
        const option = new Option(question, question);
        questionFilter.add(option);
    });
}

function filterAnswers() {
    const searchText = document.getElementById('searchInput').value.toLowerCase().trim();
    const questionFilter = document.getElementById('questionFilter').value;
    const resultFilter = document.getElementById('resultFilter').value;
    const rows = document.querySelectorAll('.answer-group');
    
    rows.forEach(row => {
        const question = row.cells[1].textContent.toLowerCase();
        const answer = row.cells[2].textContent.toLowerCase();
        const result = row.cells[3].textContent.toLowerCase();
        
        let showRow = true;
        
        if (searchText) {
            showRow = question.includes(searchText) || answer.includes(searchText);
        }
        
        if (questionFilter && question !== questionFilter.toLowerCase()) {
            showRow = false;
        }

        if (resultFilter) {
            const isCorrect = result.includes('đúng');
            if ((resultFilter === 'correct' && !isCorrect) || 
                (resultFilter === 'incorrect' && isCorrect)) {
                showRow = false;
            }
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    populateQuestionFilter();
});

document.getElementById('searchInput').addEventListener('input', filterAnswers);
document.getElementById('questionFilter').addEventListener('change', filterAnswers);
document.getElementById('resultFilter').addEventListener('change', filterAnswers);
</script>