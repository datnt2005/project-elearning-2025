<div class="container-fluid px-4">
  <h1 class="mt-4 text-center text-success">Danh sách khóa học</h1>
  <div class="card mb-4 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-success text-white">
      <h5 class="mb-0"><i class="fas fa-book me-2"></i> Quản lý khóa học</h5>
      <a href="/admin/courses/create" class="btn btn-light">
        <i class="fas fa-plus"></i> Thêm khóa học
      </a>
    </div>
    <div class="card-body">
      <!-- Thanh tìm kiếm và filter -->
      <div class="row mb-3">
        <div class="col-md-6">
          <div class="input-group">
            <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm khóa học...">
            <button class="btn btn-outline-secondary" type="button">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
        <div class="col-md-6">
          <div class="d-flex gap-2 justify-content-end">
            <!-- Danh mục filter -->
            <select id="categoryFilter" class="form-select w-auto">
              <option value="">Tất cả danh mục</option>
            </select>
            <!-- Trạng thái filter: Giá trị "1" cho Hoạt động, "0" cho Tạm dừng -->
            <select id="statusFilter" class="form-select w-auto">
              <option value="">Tất cả trạng thái</option>
              <option value="1">Hoạt động</option>
              <option value="0">Tạm dừng</option>
            </select>
          </div>
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
