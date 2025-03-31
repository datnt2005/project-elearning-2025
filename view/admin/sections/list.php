<div class="container-fluid px-4">
  <h1 class="mt-4 text-center text-success">Danh sách Section</h1>
  <div class="card mb-4 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-success text-white">
      <h5 class="mb-0"><i class="fas fa-folder me-2"></i> Quản lý Section</h5>
      <a href="/admin/sections/create" class="btn btn-light">
        <i class="fas fa-plus"></i> Thêm Section
      </a>
    </div>
    <div class="card-body">
      <!-- Thanh tìm kiếm và filter -->
      <div class="row mb-3">
        <div class="col-md-6">
          <div class="input-group">
            <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm section...">
            <button class="btn btn-outline-secondary" type="button">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
        <div class="col-md-6">
          <div class="d-flex gap-2 justify-content-end">
            <select id="courseFilter" class="form-select w-auto">
              <option value="">Tất cả khóa học</option>
            </select>
            <select id="orderFilter" class="form-select w-auto">
              <option value="">Sắp xếp</option>
              <option value="asc">Thứ tự tăng dần</option>
              <option value="desc">Thứ tự giảm dần</option>
            </select>
          </div>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Thông tin Section</th>
              <th>Khóa học</th>
              <th>Thứ tự</th>
         
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($sections as $section): ?>
            <tr>
              <td><?= $section['id'] ?></td>
              <td>
                <div class="fw-bold"><?= htmlspecialchars($section['title']) ?></div>
                <small class="text-muted">
                  <?= htmlspecialchars(substr($section['description'], 0, 100)) ?>...
                </small>
              </td>
              <td>
                <span class="badge bg-info">
                  <?= htmlspecialchars($section['course_title'] ?? 'N/A') ?>
                </span>
              </td>
              <td>
                <span class="badge bg-secondary">
                  #<?= htmlspecialchars($section['order_number']) ?>
                </span>
              </td>
            
              <td>
                <div class="btn-group">
                  <a href="/admin/sections/edit/<?= $section['id'] ?>" 
                     class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                    <i class="fas fa-edit"></i>
                  </a>
                
                  <button type="button" class="btn btn-sm btn-outline-danger"
                          onclick="confirmDelete(<?= $section['id'] ?>)" title="Xóa">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
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
  if (confirm('Bạn có chắc chắn muốn xóa section này?')) {
    window.location.href = '/admin/sections/delete/' + id;
  }
}

// Hàm populate filter khóa học từ dữ liệu trong bảng
function populateCourseFilter() {
  const rows = document.querySelectorAll('tbody tr');
  const courseFilter = document.getElementById('courseFilter');
  const courses = new Set();

  // Lấy tất cả các khóa học từ bảng
  rows.forEach(row => {
    const courseEl = row.querySelector('.badge.bg-info');
    if (courseEl) {
      const courseTitle = courseEl.textContent.trim();
      if (courseTitle !== 'N/A') {
        courses.add(courseTitle);
      }
    }
  });

  // Xóa các option cũ (giữ lại option đầu tiên)
  while (courseFilter.options.length > 1) {
    courseFilter.remove(1);
  }

  // Thêm các khóa học vào filter
  Array.from(courses).sort().forEach(course => {
    const option = new Option(course, course);
    courseFilter.add(option);
  });
}

// Cập nhật hàm filterSections
function filterSections() {
  const searchText = document.getElementById('searchInput').value.toLowerCase().trim();
  const courseFilter = document.getElementById('courseFilter').value;
  const orderFilter = document.getElementById('orderFilter').value;

  const rows = document.querySelectorAll('tbody tr');
  
  rows.forEach(row => {
    const titleEl = row.querySelector('.fw-bold');
    const descEl = row.querySelector('.text-muted');
    const courseEl = row.querySelector('.badge.bg-info');
    
    if (!titleEl || !descEl || !courseEl) return;
    
    const title = titleEl.textContent.trim().toLowerCase();
    const description = descEl.textContent.trim().toLowerCase();
    const course = courseEl.textContent.trim();
    
    let showRow = true;
    
    // Cải thiện tìm kiếm: tìm theo từng từ
    if (searchText) {
      const searchTerms = searchText.split(' ').filter(term => term.length > 0);
      const searchTarget = `${title} ${description}`.toLowerCase();
      
      showRow = searchTerms.every(term => searchTarget.includes(term));
    }
    
    // Filter theo khóa học - so sánh chính xác
    if (courseFilter && course !== courseFilter) {
      showRow = false;
    }
    
    row.style.display = showRow ? '' : 'none';
  });

  // Sắp xếp nếu có
  if (orderFilter) {
    const tbody = document.querySelector('tbody');
    const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
    
    visibleRows.sort((a, b) => {
      const orderAEl = a.querySelector('.badge.bg-secondary');
      const orderBEl = b.querySelector('.badge.bg-secondary');
      if (!orderAEl || !orderBEl) return 0;
      
      const orderA = parseInt(orderAEl.textContent.replace('#', '').trim(), 10);
      const orderB = parseInt(orderBEl.textContent.replace('#', '').trim(), 10);
      return orderFilter === 'asc' ? orderA - orderB : orderB - orderA;
    });

    // Cập nhật lại thứ tự hiển thị
    visibleRows.forEach(row => tbody.appendChild(row));
  }
}

// Khởi tạo filters khi trang load
document.addEventListener('DOMContentLoaded', () => {
  populateCourseFilter();
});

// Event listeners
document.getElementById('searchInput').addEventListener('input', filterSections);
document.getElementById('courseFilter').addEventListener('change', filterSections);
document.getElementById('orderFilter').addEventListener('change', filterSections);
</script>
