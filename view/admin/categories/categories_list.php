<style>
:root {
    --header-bg: #343a40;
    --primary-btn: #dc3545;
    --primary-btn-hover: #bb2d3b;
    --text-light: #f8f9fa;
    --border-color: rgba(255,255,255,0.1);
}

/* Update text center color */
h1.text-center {
    color: var(--header-bg) !important;
}

.mt-4 {
    color: var(--header-bg);
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

.badge {
    font-size: 85%;
}

.badge.bg-info {
    background-color: var(--header-bg) !important;
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
  <h1 class="mt-4 text-center" style="color: var(--header-bg);">Danh sách danh mục</h1>

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
        <i class="fas fa-tags me-2"></i>Quản lý danh mục
      </h5>
      <a href="/admin/categories/create" class="btn btn-add">
        <i class="fas fa-plus me-1"></i> Thêm danh mục
      </a>
    </div>
    <div class="card-body">
      <div class="filter-container">
        <div class="search-group">
          <div class="input-group">
            <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm danh mục...">
            <button class="btn btn-outline-secondary" type="button">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Tên danh mục</th>
              <th>Mô tả</th>
              <th>Số khóa học</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($categories)): ?>
              <?php foreach ($categories as $category): ?>
                <tr>
                  <td><?= $category['id'] ?></td>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="fw-bold"><?= htmlspecialchars($category['name']) ?></div>
                    </div>
                  </td>
                  <td><?= htmlspecialchars($category['description']) ?></td>
                  <td>
                    <span class="badge bg-info">
                      <?= $category['course_count'] ?? 0 ?> khóa học
                    </span>
                  </td>
                  <td>
                    <div class="btn-group">
                      <a href="/admin/categories/<?= $category['id'] ?>" 
                         class="btn btn-sm btn-outline-info" title="Xem">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="/admin/categories/edit/<?= $category['id'] ?>" 
                         class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                        <i class="fas fa-edit"></i>
                      </a>
                      <button type="button" class="btn btn-sm btn-outline-danger"
                              onclick="confirmDelete(<?= $category['id'] ?>)" title="Xóa">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center">Không có dữ liệu</td>
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
  if (confirm('Bạn có chắc chắn muốn xóa danh mục này?')) {
    window.location.href = '/admin/categories/delete/' + id;
  }
}

// Hàm tìm kiếm danh mục
function filterCategories() {
  const searchText = document.getElementById('searchInput').value.toLowerCase().trim();
  const rows = document.querySelectorAll('tbody tr');
  
  rows.forEach(row => {
    const name = row.querySelector('.fw-bold')?.textContent.toLowerCase().trim() || '';
    const description = row.cells[2]?.textContent.toLowerCase().trim() || '';
    
    const searchTerms = searchText.split(' ').filter(term => term.length > 0);
    const searchTarget = `${name} ${description}`.toLowerCase();
    const showRow = searchTerms.every(term => searchTarget.includes(term));
    
    row.style.display = showRow ? '' : 'none';
  });
}

// Event listeners
document.getElementById('searchInput').addEventListener('input', filterCategories);
</script>