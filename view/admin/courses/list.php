<style>
:root {
    --header-bg: #343a40;
    --primary-btn: #dc3545;
    --primary-btn-hover: #bb2d3b;
    --text-light: #f8f9fa;
    --border-color: rgba(255,255,255,0.1);
}

.mt-4 {
    color: var(--header-bg);
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

/* Filter styles */
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

/* Table styles */
.table-responsive {
    margin-top: 1rem;
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

.btn-group .btn {
    padding: 0.25rem 0.5rem;
}

.btn-group .btn:hover {
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
    <h1 class="mt-4 text-center">Danh sách khóa học</h1>

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
                <i class="fas fa-graduation-cap me-2"></i>Quản lý khóa học
            </h5>
            <a href="/admin/courses/create" class="btn btn-add">
                <i class="fas fa-plus me-1"></i>Thêm khóa học
            </a>
        </div>

        <div class="card-body">
            <div class="filter-container">
                <div class="search-group">
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm khóa học...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="filter-group">
                    <select id="categoryFilter" class="form-select">
                        <option value="">Tất cả danh mục</option>
                    </select>
                </div>
                <div class="filter-group">
                    <select id="statusFilter" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="1">Hoạt động</option>
                        <option value="0">Tạm dừng</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Thông tin khóa học</th>
                            <th>Giá</th>
                            <th>Danh mục</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($courses)): ?>
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td><?= $course['id'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($course['image'])): ?>
                                                <img src="/<?= htmlspecialchars($course['image']) ?>" 
                                                     class="rounded me-2" alt="Course" style="width: 40px; height: 40px; object-fit: cover;">
                                            <?php endif; ?>
                                            <div>
                                                <div class="fw-bold"><?= htmlspecialchars($course['title']) ?></div>
                                                <small class="text-muted">
                                                    <i class="fas fa-user me-1"></i><?= htmlspecialchars($course['instructor_name'] ?? 'N/A') ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div><?= number_format($course['price']) ?> đ</div>
                                        <?php if ($course['discount_price']): ?>
                                            <small class="text-success">
                                                KM: <?= number_format($course['discount_price']) ?> đ
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="badge bg-info text-white">
                                            <?= htmlspecialchars($course['category_name'] ?? 'N/A') ?>
                                        </div>
                                        <?php if (!empty($course['subcategory_name'])): ?>
                                            <div class="badge bg-secondary mt-1">
                                                <?= htmlspecialchars($course['subcategory_name']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $course['status'] ? 'success' : 'warning' ?>">
                                            <?= $course['status'] ? 'Hoạt động' : 'Tạm dừng' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="/admin/courses/edit/<?= $course['id'] ?>" class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="confirmDelete(<?= $course['id'] ?>)" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Không có dữ liệu</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Phân trang -->
            <?php if (isset($total_pages) && $total_pages > 1): ?>
                <div class="mt-3">
                    <nav aria-label="Page navigation example">
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
    if (confirm('Bạn có chắc chắn muốn xóa khóa học này?')) {
        window.location.href = '/admin/courses/delete/' + id;
    }
}

// Add function to populate category filter
function populateCategoryFilter() {
    const rows = document.querySelectorAll('tbody tr');
    const categoryFilter = document.getElementById('categoryFilter');
    const categories = new Set();

    // Get all categories from table
    rows.forEach(row => {
        const categoryEl = row.querySelector('.badge.bg-info');
        if (categoryEl) {
            const categoryName = categoryEl.textContent.trim();
            if (categoryName !== 'N/A') {
                categories.add(categoryName);
            }
        }
    });

    // Clear old options (keep first "All" option)
    while (categoryFilter.options.length > 1) {
        categoryFilter.remove(1);
    }

    // Add categories to filter
    Array.from(categories).sort().forEach(category => {
        const option = new Option(category, category);
        categoryFilter.add(option);
    });
}

// Update filterCourses function
function filterCourses() {
    const searchText = document.getElementById('searchInput').value.toLowerCase().trim();
    const categoryFilter = document.getElementById('categoryFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const title = row.querySelector('.fw-bold')?.textContent.toLowerCase().trim() || '';
        const instructor = row.querySelector('.text-muted')?.textContent.toLowerCase().trim() || '';
        const categoryEl = row.querySelector('.badge.bg-info');
        const statusEl = row.querySelector('.badge.bg-success, .badge.bg-warning');
        
        const category = categoryEl ? categoryEl.textContent.trim() : '';
        const statusText = statusEl ? statusEl.textContent.trim() : '';
        
        let showRow = true;
        
        // Improved search: search by words
        if (searchText) {
            const searchTerms = searchText.split(' ').filter(term => term.length > 0);
            const searchTarget = `${title} ${instructor}`.toLowerCase();
            showRow = searchTerms.every(term => searchTarget.includes(term));
        }
        
        // Category filter - exact match
        if (categoryFilter && category !== categoryFilter) {
            showRow = false;
        }
        
        // Status filter
        if (statusFilter) {
            if ((statusFilter === '1' && statusText !== 'Hoạt động') ||
                (statusFilter === '0' && statusText !== 'Tạm dừng')) {
                showRow = false;
            }
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

// Initialize filters when page loads
document.addEventListener('DOMContentLoaded', () => {
    populateCategoryFilter();
});

// Update event listeners
document.getElementById('searchInput').addEventListener('input', filterCourses);
document.getElementById('categoryFilter').addEventListener('change', filterCourses);
document.getElementById('statusFilter').addEventListener('change', filterCourses);
</script>
