<style>
:root {
    --header-bg: #343a40;
    --primary-btn: #dc3545;
    --primary-btn-hover: #bb2d3b;
    --text-light: #f8f9fa;
    --border-color: rgba(255,255,255,0.1);
}

/* Card and button styles */
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

/* Badge styles */
.badge {
    font-size: 85%;
}

.badge.bg-danger {
    background-color: var(--primary-btn) !important;
}

.badge.bg-primary {
    background-color: var(--header-bg) !important;
}

/* Responsive styles */
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
    <h1 class="mt-4 text-center" style="color: var(--header-bg);">Danh sách người dùng</h1>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['errors'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?= htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-start align-items-center text-white">
            <h5 class="mb-0 me-3">
                <i class="fas fa-users me-2"></i>Quản lý người dùng
            </h5>
            <a href="/admin/user/create" class="btn btn-add">
                <i class="fas fa-plus me-1"></i> Thêm người dùng
            </a>
        </div>

        <div class="card-body">
            <div class="filter-container">
                <div class="search-group">
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm người dùng...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="filter-group">
                    <select id="roleFilter" class="form-select">
                        <option value="">Tất cả vai trò</option>
                        <option value="admin">Admin</option>
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
                    </select>
                </div>
                <div class="filter-group">
                    <select id="statusFilter" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active">Hoạt động</option>
                        <option value="inactive">Không hoạt động</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['id']) ?></td>
                                    <td class="fw-bold"><?= htmlspecialchars($user['name']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= 
                                            $user['role'] == 'admin' ? 'danger' : 
                                            ($user['role'] == 'teacher' ? 'primary' : 'info') 
                                        ?>">
                                            <?= htmlspecialchars($user['role']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $user['status'] == 'active' ? 'success' : 'secondary' ?>">
                                            <?= htmlspecialchars($user['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="/admin/user/view/<?= $user['id'] ?>" 
                                               class="btn btn-sm btn-outline-info" title="Xem">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="/admin/user/edit/<?= $user['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="confirmDelete(<?= $user['id'] ?>)" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">Không có người dùng nào</td>
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
    if (confirm('Bạn có chắc chắn muốn xóa người dùng này?')) {
        window.location.href = '/admin/user/delete/' + id;
    }
}

function filterUsers() {
    const searchText = document.getElementById('searchInput').value.toLowerCase().trim();
    const roleFilter = document.getElementById('roleFilter').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const name = row.cells[1].textContent.toLowerCase();
        const email = row.cells[2].textContent.toLowerCase();
        const role = row.cells[3].textContent.toLowerCase();
        const status = row.cells[4].textContent.toLowerCase();
        
        const matchesSearch = name.includes(searchText) || 
                            email.includes(searchText);
        const matchesRole = !roleFilter || role.includes(roleFilter);
        const matchesStatus = !statusFilter || status.includes(statusFilter);
        
        row.style.display = (matchesSearch && matchesRole && matchesStatus) ? '' : 'none';
    });
}

document.getElementById('searchInput').addEventListener('input', filterUsers);
document.getElementById('roleFilter').addEventListener('change', filterUsers);
document.getElementById('statusFilter').addEventListener('change', filterUsers);

document.getElementById('filterToggle').addEventListener('click', () => {
    const filterContainer = document.getElementById('filterContainer');
    filterContainer.classList.toggle('active');
});
</script>
