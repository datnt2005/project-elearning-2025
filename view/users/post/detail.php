<br>
<br>
<br>

<style>
    .container1 {
        max-width: 1800px;
        margin: 2rem auto;
        display: flex;
        justify-content: space-between;
        gap: 2rem;
        padding: 1rem;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        background: #fff;
        margin-top: -30px;
    }

    
    .post-content-container {
        flex: 1;
    }

    .post-title {
        font-size: 2rem;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 1.5rem;
    }

    .post-thumbnail img {
        max-width: 100%;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .post-meta {
        font-size: 0.9rem;
        color: #7f8c8d;
        margin-bottom: 2rem;
    }

    .post-content {
        font-size: 1rem;
        line-height: 1.8;
        color: #34495e;
        text-align: justify;
    }

    .btn-back {
        display: inline-block;
        margin-top: 2rem;
        padding: 0.8rem 1.5rem;
        background: #3498db;
        color: #fff;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        transition: background 0.3s;
    }

    .btn-back:hover {
        background: #2980b9;
    }

    .comment-section {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #fff;
    }

    .comment-section h2 {
        font-size: 24px;
        margin-bottom: 15px;
        color: #333;
    }

    .comment {
        padding: 12px 16px;
        margin-bottom: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: #f9f9f9;
        transition: transform 0.3s ease;
    }

    .comment:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .comment strong {
        font-size: 16px;
        color: #2c3e50;
    }

    .comment p {
        margin: 8px 0;
        color: #555;
    }

    .comment small {
        display: block;
        font-size: 13px;
        color: #888;
        margin-top: 5px;
    }

    #commentForm textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        resize: none;
        font-size: 14px;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    #commentForm .btn-action {
        margin-top: 10px;
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;
        font-size: 14px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    #commentForm .btn-action:hover {
        background-color: #45a049;
    }

    .sidebar1 {
        background: #f5f5f5;
        padding: 1rem;
        width: 300px;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .sidebar1 h3 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        color: #2c3e50;
    }

    .sidebar1 ul {
        list-style: none;
        padding: 0;
    }

    .sidebar1 ul li {
        margin-bottom: 0.8rem;
    }

    .sidebar1 ul li a {
        color: #3498db;
        text-decoration: none;
        transition: color 0.3s;
    }

    .sidebar1 ul li a:hover {
        color: #2980b9;
    }

    .btn-delete {
        margin-left: 10px;
        padding: 5px 10px;
        color: white;
        background-color: #e74c3c;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-delete:hover {
        background-color: #c0392b;
    }

    @media (max-width: 1024px) {
    .container1 {
        flex-direction: column;
        gap: 1rem;
    }

    .sidebar1 {
        order: -1;  
        width: 100%;
    }
}

@media (max-width: 768px) {
    .post-title {
        font-size: 1.75rem;
    }

    .post-meta {
        font-size: 0.85rem;
    }

    .post-content {
        font-size: 0.95rem;
    }

    .btn-back {
        padding: 0.7rem 1.2rem;
    }
}

@media (max-width: 576px) {
    .container1 {
        padding: 0.8rem;
    }

    .post-title {
        font-size: 1.5rem;
    }

    .post-meta {
        font-size: 0.8rem;
    }

    .post-content {
        font-size: 0.9rem;
        line-height: 1.6;
    }

    .btn-back {
        width: 100%;
        text-align: center;
    }

    .comment {
        padding: 10px;
    }

    #commentForm textarea {
        font-size: 13px;
    }

    #commentForm .btn-action {
        width: 100%;
    }
}

</style>

<div class="container1">
    <div class="post-content-container">
        <h2 style="text-align: center;" class="post-title"><?= htmlspecialchars($post['title']) ?></h2>

        <?php if (!empty($post['thumbnail'])): ?>
            <div class="post-thumbnail text-center mb-4">
                <img src="/uploads/<?= htmlspecialchars($post['thumbnail']) ?>"
                    alt="<?= htmlspecialchars($post['title']) ?>">
            </div>
        <?php endif; ?>

        <p class="post-meta">Đăng bởi <strong><?= htmlspecialchars($post['user_name']) ?></strong> vào ngày
            <?= date("d/m/Y", strtotime($post['created_at'])) ?>
        </p>

        <div class="post-content">
            <?= $post['content'] ?>
        </div>

    </div>
    <aside class="sidebar1">
    <h3><strong>Bài viết liên quan</strong></h3>
    <ul id="related-posts">
        <?php if (empty($relatedPosts)):  ?>
            <li>Không có bài viết liên quan.</li>
        <?php else: ?>
            <?php foreach ($relatedPosts as $related): ?>
                <li>
                    <a href="/posts/detail/<?= htmlspecialchars($related['id']) ?>">
                        <?= htmlspecialchars($related['title']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</aside>
</div>

<?php
$requestUri = $_SERVER['REQUEST_URI'];
$segments = explode('/', trim($requestUri, '/'));
$postId = isset($segments[2]) ? intval($segments[2]) : 0;
if ($postId <= 0) {
    die("Bài viết không hợp lệ.");
}
?>


<div class="comment-section">
    <h2>Bình luận</h2>
    <div id="comment-section"></div>


    <!-- Form thêm bình luận -->
    <form id="commentForm" method="POST" action="/add-comment">
        <input type="hidden" name="post_id" value="<?= htmlspecialchars($postId) ?>">
        <textarea name="content" placeholder="Viết bình luận..." required></textarea>
        <button class="btn-action" type="submit">Gửi</button>
    </form>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const postId = <?= json_encode($post['id']) ?>;
    const currentUserId = <?= json_encode($_SESSION['user_id'] ?? null) ?>;

    // Tải bình luận
    function loadComments() {
        fetch(`/get-comments/${postId}`)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('comment-section');
                container.innerHTML = data.length === 0
                    ? '<p class="text-gray-500">Chưa có bình luận nào.</p>'
                    : data.map(c => `
                        <div class="comment" id="comment-${c.id}">
                            <p><strong>${c.name}</strong>: ${c.content}</p>
                            <small>${c.created_at}</small>

                            <!-- Nút xoá chỉ hiển thị khi là chủ bình luận -->
                            ${c.user_id == currentUserId ? `
                                <form method="POST" action="/delete-comment/${c.id}" onsubmit="return confirmDelete(${c.id})">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit">🗑️</button>
                                </form>
                            ` : ''}
                        </div>
                    `).join('');
            })
            .catch(error => console.error('Lỗi khi tải bình luận:', error));
    }

    // Xác nhận và xoá bình luận
    window.confirmDelete = function(commentId) {
    if (!confirm("Bạn có chắc muốn xoá bình luận này không?")) {
        return false;  
    }

    fetch(`/delete-comment/${commentId}`, {
        method: 'POST',
        body: JSON.stringify({
            _method: 'DELETE',
            comment_id: commentId
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.text())  // Nhận phản hồi dưới dạng text
    .then(data => {
        console.log("Dữ liệu phản hồi từ server:", data);  // In ra phản hồi từ server để xem
        try {
            const jsonData = JSON.parse(data);  // Chuyển đổi thành JSON
            if (jsonData.success) {
                document.getElementById(`comment-${commentId}`).remove();
                alert("Xoá bình luận thành công!");
            } else {
                alert("Lỗi: " + jsonData.error);
            }
        } catch (error) {
            alert("Xoá Bình Luận Thành Công");
            location.reload();
        }
    })
    .catch(error => console.error('Lỗi khi xoá bình luận:', error));

    return false;  
};


    loadComments();
});


</script>
