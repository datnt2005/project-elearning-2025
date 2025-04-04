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

h1.text-center {
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

.btn-light {
    background: white;
    border: 1px solid var(--primary-btn);
    color: var(--primary-btn);
}

.btn-light:hover {
    background: var(--primary-btn);
    color: var(--text-light);
}

.btn-add {
    background-color: var(--primary-btn);
    border-color: var(--primary-btn);
    color: var(--text-light);
}

.btn-add:hover {
    background-color: var(--primary-btn-hover);
    border-color: var(--primary-btn-hover);
}

.table-bordered th {
    color: var(--header-bg);
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

.badge.bg-primary {
    background-color: var(--primary-btn) !important;
}

.table td {
    vertical-align: middle;
}

.table thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.btn-group .btn {
    padding: 0.25rem 0.5rem;
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

@media (max-width: 768px) {
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }
    
    .form-select.w-auto {
        width: 100% !important;
    }
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

    .card-header {
        flex-direction: column;
        gap: 1rem;
    }

    .card-header .btn-add {
        width: 100%;
        margin-top: 0.5rem;
    }
}
</style>

<div class="container-fluid px-4">
  <h1 class="mt-4 text-center" style="color: var(--header-bg);">Danh sách bài học</h1>

  <?php if (!empty($_SESSION['success_message'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= $_SESSION['success_message'] ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php unset($_SESSION['success_message']); ?>
  <?php endif; ?>

  <div class="card mb-4 shadow-sm">
    <div class="card-header d-flex justify-content-start align-items-center text-white">
      <h5 class="mb-0 me-3"><i class="fas fa-book me-2"></i> Quản lý bài học</h5>
      <a href="/admin/lessons/create" class="btn btn-add">
        <i class="fas fa-plus me-1"></i> Thêm bài học
      </a>
    </div>
    <div class="card-body">
      <!-- Thanh tìm kiếm và filter -->
      <div class="filter-container">
        <div class="search-group">
            <div class="input-group">
                <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm bài học...">
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
            <select class="form-select" id="typeFilter">
                <option value="">Tất cả loại</option>
                <option value="video">Video</option>
                <option value="text">Văn bản</option>
            </select>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered text-center">
          <thead class="table-light">
            <tr>
              <th style="width: 5%">ID</th>
              <th style="width: 45%">Tiêu đề</th>
              <th style="width: 35%">Section</th>
              <th style="width: 15%">Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($lessons)): ?>
              <?php foreach ($lessons as $lesson): ?>
                <tr>
                  <td><?= $lesson['id'] ?></td>
                  <td class="text-start">
                    <div class="d-flex align-items-center">
                      <?php if ($lesson['video_url']): ?>
                        <span class="badge bg-primary me-2">
                          <i class="fas fa-video"></i>
                        </span>
                      <?php endif; ?>
                      <span class="fw-bold"><?= htmlspecialchars($lesson['title']) ?></span>
                    </div>
                  </td>
                  <td>
                    <span class="badge bg-info">
                      <?= htmlspecialchars($lesson['section_title']) ?>
                    </span>
                  </td>
                  <td>
                    <div class="btn-group">
                      <a href="/admin/lessons/view/<?= $lesson['id'] ?>" 
                         class="btn btn-sm btn-outline-info" title="Xem">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="/admin/lessons/edit/<?= $lesson['id'] ?>" 
                         class="btn btn-sm btn-outline-primary" title="Sửa">
                        <i class="fas fa-edit"></i>
                      </a>
                      <button type="button" class="btn btn-sm btn-outline-danger"
                              onclick="confirmDelete(<?= $lesson['id'] ?>)" title="Xóa">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" class="text-center text-muted">Không có bài học nào</td>
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
  if (confirm('Bạn có chắc chắn muốn xóa bài học này?')) {
    window.location.href = '/admin/lessons/delete/' + id;
  }
}

// Thêm hàm để lấy danh sách section từ bảng
function populateSectionFilter() {
  const rows = document.querySelectorAll('tbody tr');
  const sectionFilter = document.getElementById('sectionFilter');
  const sections = new Set();

  // Lấy tất cả các section từ bảng
  rows.forEach(row => {
    const sectionTitle = row.querySelector('td:nth-child(3)').textContent.trim();
    if (sectionTitle) {
      sections.add(sectionTitle);
    }
  });

  // Xóa các option cũ (giữ lại option đầu tiên)
  while (sectionFilter.options.length > 1) {
    sectionFilter.remove(1);
  }

  // Thêm các section vào filter
  Array.from(sections).sort().forEach(section => {
    const option = new Option(section, section);
    sectionFilter.add(option);
  });
}

// Cập nhật hàm filterLessons
function filterLessons() {
  const searchText = document.getElementById('searchInput').value.toLowerCase();
  const sectionFilter = document.getElementById('sectionFilter').value;
  const typeFilter = document.getElementById('typeFilter').value;
  
  const rows = document.querySelectorAll('tbody tr');
  
  rows.forEach(row => {
    const title = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
    const section = row.querySelector('td:nth-child(3)').textContent.trim();
    const hasVideo = row.querySelector('.fa-video') !== null;
    
    let showRow = true;
    
    // Áp dụng filter tìm kiếm
    if (!title.includes(searchText) && !section.toLowerCase().includes(searchText)) {
      showRow = false;
    }
    
    // Áp dụng filter section - so sánh chính xác
    if (sectionFilter && section !== sectionFilter) {
      showRow = false;
    }
    
    // Áp dụng filter loại bài học
    if (typeFilter) {
      if (typeFilter === 'video' && !hasVideo) showRow = false;
      if (typeFilter === 'text' && hasVideo) showRow = false;
    }
    
    row.style.display = showRow ? '' : 'none';
  });
}

// Gọi hàm populate khi trang được load
document.addEventListener('DOMContentLoaded', () => {
  populateSectionFilter();
});

// Thêm event listeners
document.getElementById('searchInput').addEventListener('keyup', filterLessons);
document.getElementById('sectionFilter').addEventListener('change', filterLessons);
document.getElementById('typeFilter').addEventListener('change', filterLessons);
</script>
