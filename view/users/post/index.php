<br>
<br>
<br>
<br>
<br>

<style>
    .container1 {
        max-width: 1800px;
        text-align: center;
        margin-right: auto;
        margin-top: -40px;
        margin-left: 80px;
    }

    .post-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }

    .post-card {
        width: 350px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.3s ease;
    }
 

    .post-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-bottom: 1px solid #ddd;
        border-radius: 12px 12px 0 0;
        transition: transform 0.3s ease;
        display: block;
    }
    .post-card img:hover {
        transform: scale(1.05);
    }

    .post-body {
        padding: 16px;
    }

    .post-title {
        font-size: 1.25rem;
        margin-bottom: 8px;
    }

    .post-meta {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .no-posts {
        color: #6c757d;
    }
</style>

<link rel="stylesheet" href="style.css">
<div class="container1">
    <h2 class="text-3xl font-bold mb-4 text-primary">Các bài viết nổi bật</h2>
    <p class="text-decoration">Tổng hợp các bài viết chia sẻ về kinh nghiệm tự học lập trình online và các kỹ thuật lập trình web.
    </p>
    <br>
    <br>
    <?php if (empty($posts)): ?>
        <p class="no-posts">Chưa có bài viết nào.</p>
    <?php else: ?>
        <div class="post-grid">
            <?php foreach ($posts as $post): ?>
                <div class="post-card">
                    <a href="/posts/detail/<?= htmlspecialchars($post['id']) ?>" style="text-decoration: none; color: inherit;">
                        <?php if (!empty($post['thumbnail'])): ?>
                            <img src="/uploads/<?= htmlspecialchars($post['thumbnail']) ?>"
                                alt="<?= htmlspecialchars($post['title']) ?>">
                        <?php endif; ?>
                        <div class="post-body">
                            <h5 class="post-title"><?= htmlspecialchars($post['title']) ?></h5>
                            <p class="post-meta">Danh Mục: <?php echo htmlspecialchars($post['category_name']) ?></p>

                            <p class="post-meta">Đăng bởi <strong><?= htmlspecialchars($post['name']) ?> </strong>vào
                                <?= date("d/m/Y", strtotime($post['created_at'])) ?></p>
                        </div>

                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

     <button id="chatbot-toggler">
         <span class="material-symbols-rounded">mode_comment</span>
         <span class="material-symbols-rounded">close</span>
     </button>
     <div class="chatbot-popup">
         <!-- Chatbot Header -->
         <div class="chat-header">
             <div class="header-info">
                 <svg class="chatbot-logo" xmlns="http://www.w3.org/2000/svg" width="50" height="50"
                     viewBox="0 0 1024 1024">
                     <path
                         d="M738.3 287.6H285.7c-59 0-106.8 47.8-106.8 106.8v303.1c0 59 47.8 106.8 106.8 106.8h81.5v111.1c0 .7.8 1.1 1.4.7l166.9-110.6 41.8-.8h117.4l43.6-.4c59 0 106.8-47.8 106.8-106.8V394.5c0-59-47.8-106.9-106.8-106.9zM351.7 448.2c0-29.5 23.9-53.5 53.5-53.5s53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5-53.5-23.9-53.5-53.5zm157.9 267.1c-67.8 0-123.8-47.5-132.3-109h264.6c-8.6 61.5-64.5 109-132.3 109zm110-213.7c-29.5 0-53.5-23.9-53.5-53.5s23.9-53.5 53.5-53.5 53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5zM867.2 644.5V453.1h26.5c19.4 0 35.1 15.7 35.1 35.1v121.1c0 19.4-15.7 35.1-35.1 35.1h-26.5zM95.2 609.4V488.2c0-19.4 15.7-35.1 35.1-35.1h26.5v191.3h-26.5c-19.4 0-35.1-15.7-35.1-35.1zM561.5 149.6c0 23.4-15.6 43.3-36.9 49.7v44.9h-30v-44.9c-21.4-6.5-36.9-26.3-36.9-49.7 0-28.6 23.3-51.9 51.9-51.9s51.9 23.3 51.9 51.9z" />
                 </svg>
                 <h2 class="logo-text">Trợ Lý Nóng bỏng</h2>
             </div>
             <button id="close-chatbot" class="material-symbols-rounded">keyboard_arrow_down</button>
         </div>
         <!-- Chatbot Body -->
         <div class="chat-body">
             <div class="message bot-message">
                 <svg class="bot-avatar" xmlns="http://www.w3.org/2000/svg" width="50" height="50"
                     viewBox="0 0 1024 1024">
                     <path
                         d="M738.3 287.6H285.7c-59 0-106.8 47.8-106.8 106.8v303.1c0 59 47.8 106.8 106.8 106.8h81.5v111.1c0 .7.8 1.1 1.4.7l166.9-110.6 41.8-.8h117.4l43.6-.4c59 0 106.8-47.8 106.8-106.8V394.5c0-59-47.8-106.9-106.8-106.9zM351.7 448.2c0-29.5 23.9-53.5 53.5-53.5s53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5-53.5-23.9-53.5-53.5zm157.9 267.1c-67.8 0-123.8-47.5-132.3-109h264.6c-8.6 61.5-64.5 109-132.3 109zm110-213.7c-29.5 0-53.5-23.9-53.5-53.5s23.9-53.5 53.5-53.5 53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5zM867.2 644.5V453.1h26.5c19.4 0 35.1 15.7 35.1 35.1v121.1c0 19.4-15.7 35.1-35.1 35.1h-26.5zM95.2 609.4V488.2c0-19.4 15.7-35.1 35.1-35.1h26.5v191.3h-26.5c-19.4 0-35.1-15.7-35.1-35.1zM561.5 149.6c0 23.4-15.6 43.3-36.9 49.7v44.9h-30v-44.9c-21.4-6.5-36.9-26.3-36.9-49.7 0-28.6 23.3-51.9 51.9-51.9s51.9 23.3 51.9 51.9z" />
                 </svg>
                 <!-- prettier-ignore -->
                 <div class="message-text"> Xin chào 👋<br /> Tôi có thể giúp gì cho bạn </div>
             </div>
         </div>
         <!-- Chatbot Footer -->
         <div class="chat-footer">
             <form action="#" class="chat-form">
                 <textarea placeholder="Message..." class="message-input" required></textarea>
                 <div class="chat-controls">
                     <button type="button" id="emoji-picker"
                         class="material-symbols-outlined">sentiment_satisfied</button>
                     <div class="file-upload-wrapper">
                         <input type="file" id="file-input" hidden />
                         <img src="#" />
                         <button type="button" id="file-upload" class="material-symbols-rounded">attach_file</button>
                         <button type="button" id="file-cancel" class="material-symbols-rounded">close</button>
                     </div>
                     <button type="submit" id="send-message" class="material-symbols-rounded">arrow_upward</button>
                 </div>
             </form>
         </div>
     </div>
</div>
