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

.badge.bg-secondary {
    background-color: var(--primary-btn) !important;
}

.page-item.active .page-link {
    background-color: var(--primary-btn);
    border-color: var(--primary-btn);
}

.page-link {
    color: var(--primary-btn);
}

.page-link:hover {
    color: var(--primary-btn-hover);
}

.btn-group .btn:hover {
    transform: translateY(-1px);
}

/* Add new filter styles */
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
    <h1 class="mt-4 text-center" style="color: var(--header-bg);">Danh sách Quiz</h1>
    
    <?php if (!empty($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success_message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <div class="card mb-4">
        <!-- Update header alignment -->
        <div class="card-header d-flex justify-content-start align-items-center text-white">
            <h5 class="mb-0 me-3">
                <i class="fas fa-clipboard-list me-2"></i>Quản lý Quiz
            </h5>
            <a href="/admin/quizzes/create" class="btn btn-add">
                <i class="fas fa-plus me-1"></i> Thêm Quiz
            </a>
        </div>
        <div class="card-body">
            <!-- Update filter structure -->
            <div class="filter-container">
                <div class="search-group">
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm quiz...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="filter-group">
                    <select class="form-select" id="sectionFilter">
                        <option value="">Tất cả section</option>
                    </select>
                </div>
                <div class="filter-group">
                    <select class="form-select" id="statusFilter">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active">Hoạt động</option>
                        <option value="inactive">Không hoạt động</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Thông tin Quiz</th>
                            <th>Section</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($quizzes)): ?>
                            <?php foreach ($quizzes as $quiz): ?>
                                <tr>
                                    <td><?= $quiz['id'] ?></td>
                                    <td class="text-start">
                                        <div class="fw-bold"><?= htmlspecialchars($quiz['title']) ?></div>
                                        <small class="text-muted">
                                            <?= htmlspecialchars(substr($quiz['description'], 0, 100)) ?>...
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            Section <?= htmlspecialchars($quiz['section_id']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="/admin/quizzes/<?= $quiz['id'] ?>" 
                                               class="btn btn-sm btn-outline-info" title="Xem">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="/admin/quizzes/update/<?= $quiz['id'] ?>" 
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
                                <td colspan="4" class="text-center text-muted">Không có quiz nào</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination section remains the same -->
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
    if (confirm('Bạn có chắc chắn muốn xóa quiz này?')) {
        window.location.href = '/admin/quizzes/delete/' + id;
    }
}

// Add section filter population
function populateSectionFilter() {
    const rows = document.querySelectorAll('tbody tr');
    const sectionFilter = document.getElementById('sectionFilter');
    const sections = new Set();

    rows.forEach(row => {
        const sectionEl = row.querySelector('.badge.bg-info');
        if (sectionEl) {
            const sectionText = sectionEl.textContent.trim();
            if (sectionText !== 'N/A') {
                sections.add(sectionText);
            }
        }
    });

    while (sectionFilter.options.length > 1) {
        sectionFilter.remove(1);
    }

    Array.from(sections).sort().forEach(section => {
        const option = new Option(section, section);
        sectionFilter.add(option);
    });
}

function filterQuizzes() {
    const searchText = document.getElementById('searchInput').value.toLowerCase().trim();
    const sectionFilter = document.getElementById('sectionFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const titleEl = row.querySelector('.fw-bold');
        const descEl = row.querySelector('.text-muted');
        const sectionEl = row.querySelector('.badge.bg-info');
        
        if (!titleEl || !descEl || !sectionEl) return;
        
        const title = titleEl.textContent.toLowerCase();
        const description = descEl.textContent.toLowerCase();
        const section = sectionEl.textContent.trim();
        
        let showRow = true;
        
        if (searchText) {
            const searchTerms = searchText.split(' ').filter(term => term.length > 0);
            const searchTarget = `${title} ${description}`.toLowerCase();
            showRow = searchTerms.every(term => searchTarget.includes(term));
        }
        
        if (sectionFilter && section !== sectionFilter) {
            showRow = false;
        }

        if (statusFilter) {
            // Add status filtering logic if needed
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    populateSectionFilter();
});

document.getElementById('searchInput').addEventListener('input', filterQuizzes);
document.getElementById('sectionFilter').addEventListener('change', filterQuizzes);
document.getElementById('statusFilter').addEventListener('change', filterQuizzes);
</script>