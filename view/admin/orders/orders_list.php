<style>
:root {
    --header-bg: #343a40;
    --primary-btn: #dc3545;
    --primary-btn-hover: #bb2d3b;
    --text-light: #f8f9fa;
    --border-color: rgba(255,255,255,0.1);
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
    vertical-align: middle;
}

.badge {
    font-size: 85%;
    padding: 0.5em 0.8em;
}

.badge.bg-success {
    background-color: #198754 !important;
}

.badge.bg-warning {
    background-color: #ffc107 !important;
    color: #000;
}

.badge.bg-danger {
    background-color: var(--primary-btn) !important;
}

/* Button styles */
.btn-group .btn {
    padding: 0.25rem 0.5rem;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
}

.status-filters .btn {
    background: white;
    border: 1px solid var(--primary-btn);
    color: var(--primary-btn);
}

.status-filters .btn.active {
    background: var(--primary-btn);
    color: white;
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

    .status-filters {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
    }

    .card-header {
        flex-direction: column;
        gap: 1rem;
    }

    .card-header .status-filters {
        width: 100%;
    }
}
</style>

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center" style="color: var(--header-bg);">Danh sách Đơn Hàng</h1>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center text-white">
            <h5 class="mb-0">
                <i class="fas fa-shopping-cart me-2"></i>Quản lý Đơn Hàng
            </h5>
            
        </div>

        <div class="card-body">
            <div class="filter-container">
                <div class="search-group">
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm đơn hàng...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="filter-group">
                    <select class="form-select" id="dateFilter">
                        <option value="">Tất cả thời gian</option>
                        <option value="today">Hôm nay</option>
                        <option value="week">Tuần này</option>
                        <option value="month">Tháng này</option>
                    </select>
                </div>
            </div>

            <!-- Table content -->
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Mã đơn hàng</th>
                            <th>Khách hàng</th>
                            <th>Khóa học</th>
                            <th>Tổng giá trị</th>
                            <th>Ngày tạo</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= $order['id'] ?></td>
                                <td class="fw-bold"><?= $order['order_code'] ?></td>
                                <td><?= $order['user_name'] ?></td>
                                <td><?= $order['course_title'] ?></td>
                                <td><?= number_format($order['total_price'], 0, ',', '.') ?> VND</td>
                                <td><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                                <td>
                                    <span class="badge bg-<?= 
                                        $order['status'] == 'completed' ? 'success' : 
                                        ($order['status'] == 'pending' ? 'warning' : 'danger') 
                                    ?>">
                                        <?= $order['status'] == 'pending' ? 'Đang xử lý' : 
                                            ($order['status'] == 'completed' ? 'Hoàn thành' : 'Đã hủy') 
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="/admin/orders/<?= $order['id'] ?>" 
                                           class="btn btn-sm btn-outline-info" title="Xem">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/admin/orders/edit/<?= $order['id'] ?>" 
                                           class="btn btn-sm btn-outline-primary" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-order" 
                                                data-id="<?= $order['id'] ?>" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Simple search function
document.getElementById('searchInput').addEventListener('input', function() {
    const searchText = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const orderCode = row.cells[1].textContent.toLowerCase();
        const customerName = row.cells[2].textContent.toLowerCase();
        const courseTitle = row.cells[3].textContent.toLowerCase();
        
        const matches = orderCode.includes(searchText) || 
                       customerName.includes(searchText) ||
                       courseTitle.includes(searchText);
        
        row.style.display = matches ? '' : 'none';
    });
});

// Delete confirmation
document.querySelectorAll('.delete-order').forEach(button => {
    button.addEventListener('click', function() {
        const orderId = this.getAttribute('data-id');
        Swal.fire({
            title: "Bạn có chắc muốn xóa?",
            text: "Hành động này không thể hoàn tác!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Xóa",
            cancelButtonText: "Hủy"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/admin/orders/delete/" + orderId;
            }
        });
    });
});

// Filter function
function filterOrders() {
    const searchText = document.getElementById('searchInput').value.toLowerCase().trim();
    const activeStatus = document.querySelector('.btn-group .active').getAttribute('data-status');
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const orderCode = row.cells[1].textContent.toLowerCase();
        const customerName = row.cells[2].textContent.toLowerCase();
        const courseTitle = row.cells[3].textContent.toLowerCase();
        const status = row.cells[6].textContent.toLowerCase();
        
        const matchesSearch = orderCode.includes(searchText) || 
                            customerName.includes(searchText) ||
                            courseTitle.includes(searchText);
        const matchesStatus = activeStatus === 'all' || 
                            status.includes(activeStatus);
        
        row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
    });
}

// Add date filtering
document.getElementById('dateFilter').addEventListener('change', function() {
    const filterValue = this.value;
    const rows = document.querySelectorAll('tbody tr');
    const today = new Date();
    
    rows.forEach(row => {
        const dateCell = row.cells[5].textContent;
        const orderDate = new Date(dateCell.split('/').reverse().join('-'));
        
        let show = true;
        if (filterValue === 'today') {
            show = orderDate.toDateString() === today.toDateString();
        } else if (filterValue === 'week') {
            const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
            show = orderDate >= weekAgo;
        } else if (filterValue === 'month') {
            show = orderDate.getMonth() === today.getMonth() && 
                   orderDate.getFullYear() === today.getFullYear();
        }
        
        if (!show) {
            row.style.display = 'none';
        }
    });
});

// Event listeners
document.querySelectorAll('.btn-group .btn').forEach(button => {
    button.addEventListener('click', function() {
        document.querySelectorAll('.btn-group .btn').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
        filterOrders();
    });
})
</script>
