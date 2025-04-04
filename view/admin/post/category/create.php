<style>
    :root {
        --header-bg: #343a40;
        --primary-btn: #dc3545;
        --primary-btn-hover: #bb2d3b;
        --text-light: #f8f9fa;
        --border-color: rgba(255,255,255,0.1);
    }

    /* Reset margin and padding */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Custom container styling */
    .custom-container {
        width: 100%;
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
    }

    /* Header styling */
    .custom-header {
        text-align: center;
        font-size: 24px;
        color: var(--header-bg);
        margin-bottom: 20px;
    }

    /* Card container styling */
    .custom-card {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
    }

    /* Form group styling */
    .form-group {
        margin-bottom: 20px;
    }

    /* Label styling */
    .form-label {
        font-size: 16px;
        color: #555;
    }

    /* Input field styling */
    .form-control {
        width: 100%;
        padding: 12px;
        font-size: 16px;
        border: 2px solid #ccc;
        border-radius: 5px;
        margin-top: 8px;
        outline: none;
    }

    .form-control:focus {
        border-color: var(--primary-btn);
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
    }

    .btn {
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
        text-align: center;
        width: 100%;
        margin-top: 10px;
    }

    .custom-btn-submit {
        background-color: var(--primary-btn);
        border-color: var(--primary-btn);
        color: var(--text-light);
    }

    .custom-btn-submit:hover {
        background-color: var(--primary-btn-hover);
        border-color: var(--primary-btn-hover);
        transform: translateY(-1px);
    }

    .custom-btn-back {
        background-color: #f8f9fa;
        color: #333;
        border: 1px solid #ccc;
        margin-top: 10px;
    }

    .custom-btn-back:hover {
        background-color: #e2e6ea;
        color: #007bff;
    }

    @media (max-width: 600px) {
        .custom-container {
            padding: 15px;
        }

        .form-control {
            font-size: 14px;
        }

        .btn {
            font-size: 14px;
        }
    }
</style>
<div class="custom-container">
    <h2 class="custom-header">Thêm danh mục bài viết</h2>

    <div class="custom-card">
        <form action="" method="POST">
            <div class="form-group">
                <label for="name" class="form-label">Tên danh mục</label>
                <input type="text" class="form-control" id="name" name="name" required placeholder="Nhập tên danh mục" autocomplete="off">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn custom-btn-submit">Thêm</button>
                <a href="/admin/postCategory" class="btn custom-btn-back">Quay lại</a>
            </div>
        </form>
    </div>
</div>