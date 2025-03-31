<div class="container-fluid px-4">
  <h1 class="mt-4 text-center text-success">Danh sách bài học</h1>
  <div class="card mb-4 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-success text-white">
      <h5 class="mb-0"><i class="fas fa-book me-2"></i> Quản lý bài học</h5>
      <a href="/admin/lessons/create" class="btn btn-light">
        <i class="fas fa-plus"></i> Thêm bài học
      </a>
    </div>
    <div class="card-body">
      <!-- Thanh tìm kiếm và filter -->
      <div class="row mb-3">
        <div class="col-md-6">
          <div class="input-group">
            <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm bài học...">
            <button class="btn btn-outline-secondary" type="button">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
        <div class="col-md-6">
          <div class="d-flex gap-2 justify-content-end">
            <select id="sectionFilter" class="form-select w-auto">
              <option value="">Tất cả section</option>
            </select>
            <select id="typeFilter" class="form-select w-auto">
              <option value="">Tất cả loại</option>
              <option value="video">Có video</option>
              <option value="text">Chỉ văn bản</option>
            </select>
          </div>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Tiêu đề</th>
              <th>Section</th>
             
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($lessons as $lesson): ?>
            <tr>
              <td><?= $lesson['id'] ?></td>
              <td>
                <div class="d-flex align-items-center">
                  <?php if ($lesson['video_url']): ?>
                    <i class="fas fa-video text-primary me-2"></i>
                  <?php endif; ?>
                  <?= htmlspecialchars($lesson['title']) ?>
                </div>
              </td>
              <td><?= htmlspecialchars($lesson['section_title']) ?></td>
             
              <td>
                <div class="btn-group">
                  <a href="/admin/lessons/edit/<?= $lesson['id'] ?>" 
                     class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-edit"></i>
                  </a>
                  
                  <button type="button" class="btn btn-sm btn-outline-danger"
                          onclick="confirmDelete(<?= $lesson['id'] ?>)">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
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
