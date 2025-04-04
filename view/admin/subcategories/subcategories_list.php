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

/* Update badge styles */
.badge.bg-secondary {
    background-color: var(--primary-btn) !important;
}

/* Update responsive styles */
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
    <h1 class="mt-4 text-center" style="color: var(--header-bg);">Danh sách danh mục con</h1>

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
                <i class="fas fa-tags me-2"></i>Quản lý danh mục con
            </h5>
            <a href="/admin/subcategories/create" class="btn btn-add">
                <i class="fas fa-plus me-1"></i> Thêm danh mục
            </a>
        </div>
        <div class="card-body">
            <!-- Add filter structure -->
            <div class="filter-container">
                <div class="search-group">
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm danh mục...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="filter-group">
                    <select class="form-select" id="categoryFilter">
                        <option value="">Tất cả danh mục</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="20%">Danh mục</th>
                            <th width="25%">Tên danh mục con</th>
                            <th width="30%">Mô tả</th>
                            <th width="20%">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subcategories as $subcategory): ?>
                            <tr>
                                <td><?= $subcategory['id'] ?></td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?= htmlspecialchars($subcategory['category_name']) ?>
                                    </span>
                                </td>
                                <td class="fw-medium"><?= htmlspecialchars($subcategory['name']) ?></td>
                                <td class="text-start"><?= htmlspecialchars($subcategory['description']) ?></td>
                                <td>
                                    <div class="btn-group table-actions">
                                        <a href="/admin/subcategories/<?= $subcategory['id'] ?>" 
                                           class="btn btn-outline-info btn-sm" title="Xem">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/admin/subcategories/edit/<?= $subcategory['id'] ?>" 
                                           class="btn btn-outline-primary btn-sm" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger btn-sm" 
                                                onclick="confirmDelete(<?= $subcategory['id'] ?>)" 
                                                title="Xóa">
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
function confirmDelete(id) {
    if (confirm('Bạn có chắc chắn muốn xóa danh mục này?')) {
        window.location.href = '/admin/subcategories/delete/' + id;
    }
}

// Add filter functionality
function populateCategoryFilter() {
    const rows = document.querySelectorAll('tbody tr');
    const categoryFilter = document.getElementById('categoryFilter');
    const categories = new Set();

    rows.forEach(row => {
        const categoryEl = row.querySelector('.badge');
        if (categoryEl) {
            const categoryText = categoryEl.textContent.trim();
            if (categoryText !== '') {
                categories.add(categoryText);
            }
        }
    });

    while (categoryFilter.options.length > 1) {
        categoryFilter.remove(1);
    }

    Array.from(categories).sort().forEach(category => {
        const option = new Option(category, category);
        categoryFilter.add(option);
    });
}

function filterSubcategories() {
    const searchText = document.getElementById('searchInput').value.toLowerCase().trim();
    const categoryFilter = document.getElementById('categoryFilter').value;
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const name = row.querySelector('.fw-medium').textContent.toLowerCase();
        const description = row.cells[3].textContent.toLowerCase();
        const category = row.querySelector('.badge').textContent.trim();
        
        let showRow = true;
        
        if (searchText) {
            showRow = name.includes(searchText) || description.includes(searchText);
        }
        
        if (categoryFilter && category !== categoryFilter) {
            showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    populateCategoryFilter();
});

document.getElementById('searchInput').addEventListener('input', filterSubcategories);
document.getElementById('categoryFilter').addEventListener('change', filterSubcategories);
</script>