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

.table-actions .btn {
    margin: 0 2px;
    padding: 0.25rem 0.5rem;
}

.table thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.badge.bg-info {
    background-color: var(--header-bg) !important;
}

.page-item.active .page-link {
    background-color: var(--primary-btn);
    border-color: var(--primary-btn);
}

.btn-group .btn:hover {
    transform: translateY(-1px);
}

/* Add filter styles */
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
    <h1 class="mt-4 text-center" style="color: var(--header-bg);">Danh sách Câu hỏi</h1>
    
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
                <i class="fas fa-question-circle me-2"></i>Quản lý câu hỏi
            </h5>
            <a href="/admin/quizQuests/create" class="btn btn-add">
                <i class="fas fa-plus me-1"></i> Thêm câu hỏi
            </a>
        </div>
        <div class="card-body">
            <!-- Update filter structure -->
            <div class="filter-container">
                <div class="search-group">
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm câu hỏi...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="filter-group">
                    <select class="form-select" id="quizFilter">
                        <option value="">Tất cả bài kiểm tra</option>
                    </select>
                </div>
                <div class="filter-group">
                    <select class="form-select" id="typeFilter">
                        <option value="">Tất cả kiểu câu hỏi</option>
                        <option value="multiple">Nhiều lựa chọn</option>
                        <option value="single">Một lựa chọn</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Bài kiểm tra</th>
                            <th>Câu hỏi</th>
                            <th>Kiểu câu hỏi</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($quizQuestions)): ?>
                            <?php foreach ($quizQuestions as $quiz): ?>
                                <tr>
                                    <td><?= $quiz['id'] ?></td>
                                    <td><?= htmlspecialchars($quiz['quiz_id']) ?></td>
                                    <td class="text-start"><?= htmlspecialchars($quiz['question']) ?></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= htmlspecialchars($quiz['type']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="/admin/quizQuests/view/<?= $quiz['id'] ?>" 
                                               class="btn btn-sm btn-outline-info" title="Xem">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="/admin/quizQuests/update/<?= $quiz['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="confirmDelete(<?= $quiz['id'] ?>)" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">Không có câu hỏi nào</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Phân trang -->
            <?php if (isset($total_pages) && $total_pages > 1): ?>
                <div class="mt-3">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= ($current_page == $i) ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Bạn có chắc chắn muốn xóa câu hỏi này?')) {
        window.location.href = '/admin/quizQuests/delete/' + id;
    }
}

// Add quiz filter population
function populateQuizFilter() {
    const rows = document.querySelectorAll('tbody tr');
    const quizFilter = document.getElementById('quizFilter');
    const quizzes = new Set();

    rows.forEach(row => {
        const quizId = row.cells[1].textContent.trim();
        if (quizId !== 'N/A') {
            quizzes.add(quizId);
        }
    });

    while (quizFilter.options.length > 1) {
        quizFilter.remove(1);
    }

    Array.from(quizzes).sort().forEach(quiz => {
        const option = new Option('Quiz ' + quiz, quiz);
        quizFilter.add(option);
    });
}

function filterQuestions() {
    const searchText = document.getElementById('searchInput').value.toLowerCase().trim();
    const quizFilter = document.getElementById('quizFilter').value;
    const typeFilter = document.getElementById('typeFilter').value;
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const question = row.cells[2].textContent.toLowerCase();
        const quizId = row.cells[1].textContent.trim();
        const type = row.querySelector('.badge').textContent.trim().toLowerCase();
        
        let showRow = true;
        
        if (searchText) {
            showRow = question.includes(searchText);
        }
        
        if (quizFilter && quizId !== quizFilter) {
            showRow = false;
        }

        if (typeFilter && !type.includes(typeFilter)) {
            showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    populateQuizFilter();
});

document.getElementById('searchInput').addEventListener('input', filterQuestions);
document.getElementById('quizFilter').addEventListener('change', filterQuestions);
document.getElementById('typeFilter').addEventListener('change', filterQuestions);
</script>