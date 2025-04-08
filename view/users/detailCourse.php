<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khóa học</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
/* Container chính */
.container-learning {
    display: flex;
    flex-wrap: wrap;
    width: 1600px;
    gap: 20px;
    margin: auto auto;
    align-items: stretch;
    min-height: 100vh;
}
.video-section {
    min-width: 0;
    width: 100px;
    padding: 5px;
    display: flex;
    flex-direction: column;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.video-title h1 {
    font-size: 1.5rem;
    margin: 0.75rem;
    color: #333;
}

.video-player {
    position: relative;
    width: 100%;
}

.video-player iframe {
    width: 100%;
    height: auto;
    aspect-ratio: 16 / 9;
    border: none;
    border-radius: 8px;
}

.description {
    margin-top: 10px;
}

.description p {
    font-size: 1rem;
    margin: 0 1.25rem;
    color: #666;
}

.description .text-3xl {
    font-size: 1.5rem;
    color: #333;
}

.sidebar1 {
    min-width: 400px;
    padding: 10px;
    display: flex;
    flex-direction: column;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.progress-bar {
    margin-top: 1rem;
}

.progress-container {
    width: 100%;
    background-color: #e0e0e0;
    height: 8px;
    border-radius: 4px;
    margin: 5px 0;
}

.progress-fill {
    height: 100%;
    background-color: #4caf50;
    border-radius: 4px;
    transition: width 0.3s ease;
}

#course-progress, #current-lesson-progress {
    display: block;
    font-size: 0.9rem;
    color: #666;
}

.text-green-500 {
    color: #22c55e;
}

.text-blue-500 {
    color: #3b82f6;
}

.text-red-500 {
    color: #ef4444;
}

.course-content {
    flex-grow: 1;
}

.course-content h2 {
    font-size: 1.25rem;
    margin: 0.5rem 0;
    color: #333;
}

.course-content .chapter {
    cursor: pointer;
    padding: 10px;
    background-color: #f9f9f9;
    margin-bottom: 5px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.course-content .chapter:hover {
    background-color: #e5e7eb;
}

.lesson-list {
    list-style: none;
    padding-left: 20px;
    display: none;
}

.lesson-list.active {
    display: block;
}

.lesson-item {
    position: relative;
}

.lesson-item a {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    text-decoration: none;
    color: #333;
    font-size: 0.9rem;
}

.lesson-item.locked a {
    color: #999;
    pointer-events: none;
}

.lesson-progress {
    font-size: 0.875rem;
    color: #666;
}

.lock-icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
}

.btn {
    display: inline-block;
    padding: 8px 16px;
    margin: 5px 0;
    text-decoration: none;
    background-color: #e5e7eb;
    color: #6b7280;
    border-radius: 4px;
    text-align: center;
}

.btn-primary:hover {
    background-color: #d1d5db;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container-learning {
        flex-direction: column;
        align-items: flex-start;
    }

    .video-section,
    .sidebar1 {
        flex: 1 1 100%;
    }

    .video-title h1 {
        font-size: 1.25rem;
    }

    .description .text-3xl {
        font-size: 1.5rem;
    }

    .description p {
        font-size: 0.9rem;
    }

    .sidebar1 {
        min-width: 0;
        width: 100%;
    }
}

@media (max-width: 480px) {
    .video-title h1 {
        font-size: 1rem;
    }

    .description .text-3xl {
        font-size: 1.25rem;
    }

    .description p {
        font-size: 0.875rem;
    }

    .lesson-item a {
        font-size: 0.875rem;
    }
    .container-learning{
        width: 100%;
        padding: 10px;
    }
}
@media (max-width: 1024px) {
    .container-learning {
        flex-direction: column;
        padding: 20px;
    }

    .video-section,
    .sidebar1 {
        width: 100%;
        flex: 1 1 100%;
    }
}
    </style>
</head>
<body>
    <div class="container-learning">
        <!-- Phần xem video -->
        <div class="video-section">
            <div class="video-title">
                <h1 class="text-2xl font-bold m-3"><?php echo htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
            </div>
            <div class="video-player">
                <?php if ($error): ?>
                    <p class="text-red-500 text-center"><?php echo $error; ?></p>
                    <iframe id="video-iframe" width="100%" height="400" src="" frameborder="0" allowfullscreen></iframe>

                <?php elseif (!empty($currentLesson['video_url'])): ?>
                    <iframe id="video-iframe" width="100%" height="400"
                        src="<?php echo htmlspecialchars($currentLesson['video_url'], ENT_QUOTES, 'UTF-8') . '&enablejsapi=1'; ?>"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                           referrerpolicy="strict-origin-when-cross-origin" allowfullscreen loading="lazy"></iframe>

                <?php elseif (!empty($currentLesson['pdf_path'])): ?>
                    <iframe src="<?php echo htmlspecialchars($currentLesson['pdf_path'], ENT_QUOTES, 'UTF-8'); ?>" 
                        width="100%" height="600px" frameborder="0"></iframe>

                <?php else: ?>
                    <p class="text-gray-500 text-center">Không có nội dung video hoặc tài liệu cho bài học này.</p>
                <?php endif; ?>
            </div>

            <div class="flex items-center justify-between">
                <div class="description" id="video-description">
                    <p class="text-3xl m-5 mb-0">
                        <strong>Bài <?php echo $currentLesson['order_number']; ?>: <?php echo htmlspecialchars($currentLesson['title'], ENT_QUOTES, 'UTF-8'); ?></strong>
                    </p>
                    <p class="mx-5"><?php echo htmlspecialchars($currentLesson['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                </div>

              

              


                <div class="ghichu" id="ghichu">
                    <!-- Nút Thêm Ghi Chú -->
                    <button id="note-button" class="bg-purple-500 text-white px-4 py-2  rounded mr-5" onclick="openModal()">
                        <i class="fas fa-plus mr-2"></i> Thêm Ghi Chú <span id="video-time">00:00</span>
                    </button>
                </div>
            </div>

            <script src="https://cdn.tailwindcss.com"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
            <!-- Modal -->
            <div id="noteModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
                <div class="w-full max-w-2xl p-4 bg-gray-900 text-white rounded-lg">
                    <div class="mb-2 text-lg">
                        <p id="note-time" class="text-lg mb-3">Thêm ghi chú tại <span class="bg-purple-500 text-white px-2 py-1 rounded">00:00</span></p>
                    </div>
                    <div class="bg-gray-800 p-4 rounded-lg">
                        <div class="flex items-center mb-2">
                            <button onclick="toggleActive(this, 'bold')" class="bg-gray-700 text-white p-2 rounded mr-2"><i class="fas fa-bold"></i></button>
                            <button onclick="toggleActive(this, 'italic')" class="bg-gray-700 text-white p-2 rounded mr-2"><i class="fas fa-italic"></i></button>
                            <button onclick="toggleList(this, 'insertUnorderedList')" class="bg-gray-700 text-white p-2 rounded mr-2"><i class="fas fa-list-ul"></i></button>
                            <button onclick="toggleList(this, 'insertOrderedList')" class="bg-gray-700 text-white p-2 rounded mr-2"><i class="fas fa-list-ol"></i></button>
                            <button onclick="insertImage()" class="bg-gray-700 text-white p-2 rounded mr-2"><i class="fas fa-image"></i></button>
                            <button onclick="openColorPopup('textColorPopup')" class="bg-gray-700 text-white p-2 rounded mr-2"><i class="fas fa-paint-brush"></i></button>
                            <button onclick="openColorPopup('bgColorPopup')" class="bg-gray-700 text-white p-2 rounded mr-2"><i class="fas fa-fill-drip"></i></button>
                        </div>
                        <!-- Form Ghi Chú -->
                        <form id="noteForm" action="/notes" method="POST">
                            <div id="noteContent" contenteditable="true" class="w-full bg-gray-700 text-white p-2 rounded h-32 overflow-auto" style="outline: none;"></div>
                            <input type="hidden" name="note" id="note">
                            <input type="hidden" id="video_time" name="video_time">
                            <input type="hidden" id="users_id" name="users_id" value="<?php echo isset($users['id']) ? $users['id'] : ''; ?>">
                            <input type="hidden" name="courses_id" value="<?php echo $course['id']; ?>">
                            <input type="hidden" id="lessons_id" name="lessons_id" value="<?php echo $lessons_id = $_POST['lessons_id'] ?? null; ?>">

                            <div class="flex justify-between mt-4">
                                <button type="submit" class="bg-purple-500 text-white px-4 py-2 rounded">TẠO GHI CHÚ</button>
                                <button type="button" onclick="closeModal()" class="bg-gray-800 text-white px-4 py-2 rounded">HỦY BỎ</button>
                            </div>
                        </form>



                    </div>

                </div>
            </div>
            <script src="https://www.youtube.com/iframe_api"></script>


            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </div>

        <!-- Sidebar1 nội dung khóa học -->
        <div class="sidebar1">
            <div class="progress-bar mt-4">
                <button id="toggle-notes-btn" class="bg-purple-500 text-white px-4 py-2 rounded mb-2">Ghi chú</button>
                <br>
                <span id="course-progress">Tiến độ khóa học: <?php echo number_format($progress['progress'], 2); ?>%</span>
                <div class="progress-container">
                    <div id="progress-bar" class="progress-fill" style="width: <?php echo $progress['progress']; ?>%;"></div>
                </div>
                <span id="current-lesson-progress">Tiến độ bài học: 0%</span>
                <div id="certificate-notice" style="display: none; margin-top: 10px;">
                    <p class="text-green-500">Chúc mừng! Bạn đã hoàn thành khóa học. Chứng chỉ đã được gửi qua email của bạn.</p>
                </div>
                <?php if ($certificate): ?>
                    <div id="certificate-link" style="margin-top: 10px;">
                        <p class="text-blue-500">Bạn đã hoàn thành khóa học:
                            <a href="http://localhost:8000/certificate?certificate_url=<?php echo htmlspecialchars($certificate['certificate_url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="underline">
                                Xem chứng chỉ
                            </a>
                        </p>
                    </div>
                <?php endif; ?>
                <!-- Sidebar ghi chú -->
                <!-- Sidebar ghi chú -->
                <div id="notes-sidebar" class="fixed top-0 right-0 h-full w-80 bg-white text-black p-4 transform translate-x-full transition-transform duration-300 rounded-l-lg shadow-lg">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg">Ghi chú</h2>
                        <button id="close-notes-btn" class="text-black">✖</button>
                    </div>
                    <!-- Bộ lọc sắp xếp -->
                    <div class="mb-4">
                        <label for="sortOrder" class="mr-2">Sắp xếp:</label>
                        <select id="sortOrder" class="bg-gray-200 text-black p-1 rounded-lg">
                            <option value="lesson_order" selected>Theo thứ tự bài học</option>
                            <option value="newest">Mới nhất</option>
                            <option value="oldest">Cũ nhất</option>
                        </select>
                    </div>
                    <!-- Tìm kiếm theo section -->
                    <div class="mb-4 flex items-center">
                        <input type="text" id="sectionFilter" class="bg-gray-200 text-black p-1 rounded-lg w-full mr-2" placeholder="Nhập số phần (ví dụ: 1)">
                        <button id="filterSectionBtn" class="bg-blue-500 text-white px-2 py-1 rounded-lg">Tìm</button>
                    </div>
                    <div id="notes-list" class="overflow-y-auto h-5/6"></div>
                </div>

                <!-- Thêm overlay cho sidebar -->
                <div id="notes-overlay" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden"></div>
            </div>


            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const notesSidebar = document.getElementById('notes-sidebar');
                    const notesOverlay = document.getElementById('notes-overlay'); // Thêm overlay
                    const editNoteModal = document.getElementById('editNoteModal');
                    const editNoteContent = document.getElementById('editNoteContent');
                    let currentEditingNoteId = null;

                    // Mở / Đóng sidebar ghi chú
                    document.getElementById('toggle-notes-btn').addEventListener('click', function() {
                        notesSidebar.classList.toggle('open');
                        if (notesSidebar.classList.contains('open')) {
                            notesOverlay.classList.remove('hidden'); // Hiển thị overlay khi sidebar mở
                            loadNotes();
                        } else {
                            notesOverlay.classList.add('hidden'); // Ẩn overlay khi sidebar đóng
                        }
                    });

                    document.getElementById('close-notes-btn').addEventListener('click', function() {
                        notesSidebar.classList.remove('open');
                        notesOverlay.classList.add('hidden'); // Ẩn overlay khi đóng sidebar
                    });

                    // Đóng overlay và sidebar khi nhấp ra ngoài
                    notesOverlay.addEventListener('click', function() {
                        notesSidebar.classList.remove('open');
                        notesOverlay.classList.add('hidden');
                    });
                    document.getElementById('close-notes-btn').addEventListener('click', function() {
                        notesSidebar.classList.remove('open');
                    });

                    // Lắng nghe sự thay đổi của bộ lọc sắp xếp
                    const sortOrderSelect = document.getElementById('sortOrder');
                    sortOrderSelect.addEventListener('change', function() {
                        loadNotes(); // Tải lại ghi chú khi thay đổi bộ lọc
                    });

                    // Lắng nghe sự kiện nhấn nút tìm kiếm theo section
                    const sectionFilterInput = document.getElementById('sectionFilter');
                    document.getElementById('filterSectionBtn').addEventListener('click', function() {
                        loadNotes(); // Tải lại ghi chú khi nhấn nút tìm
                    });

                    // Cho phép tìm kiếm khi nhấn Enter trong input
                    sectionFilterInput.addEventListener('keypress', function(event) {
                        if (event.key === 'Enter') {
                            loadNotes();
                        }
                    });

                    // Load danh sách ghi chú
                    function loadNotes() {
                        const coursesId = <?php echo json_encode($course['id']); ?>;
                        fetch(`/notes/get?courses_id=${coursesId}`)
                            .then(response => response.json())
                            .then(data => {
                                const notesList = document.getElementById('notes-list');
                                notesList.innerHTML = '';

                                if (data.status === 'success' && data.notes.length > 0) {
                                    // Lấy giá trị sắp xếp và bộ lọc section
                                    const sortOrder = sortOrderSelect.value;
                                    const sectionFilter = sectionFilterInput.value.trim();

                                    // Sao chép mảng để không thay đổi dữ liệu gốc
                                    let filteredNotes = [...data.notes];

                                    // Lọc theo section_order nếu có giá trị nhập vào
                                    if (sectionFilter !== '') {
                                        filteredNotes = filteredNotes.filter(note => {
                                            const sectionOrder = note.section_order || 'N/A';
                                            return sectionOrder.toString() === sectionFilter;
                                        });
                                    }

                                    // Sắp xếp danh sách đã lọc
                                    filteredNotes.sort((a, b) => {
                                        if (sortOrder === 'lesson_order') {
                                            // Sắp xếp theo thứ tự bài học (tăng dần)
                                            return (a.lesson_order || 0) - (b.lesson_order || 0);
                                        } else if (sortOrder === 'newest') {
                                            // Sắp xếp theo mới nhất (giảm dần theo created_at)
                                            const dateA = new Date(a.created_at);
                                            const dateB = new Date(b.created_at);
                                            return dateB - dateA;
                                        } else if (sortOrder === 'oldest') {
                                            // Sắp xếp theo cũ nhất (tăng dần theo created_at)
                                            const dateA = new Date(a.created_at);
                                            const dateB = new Date(b.created_at);
                                            return dateA - dateB;
                                        }
                                    });

                                    // Hiển thị danh sách đã lọc và sắp xếp
                                    if (filteredNotes.length > 0) {
                                        // Trong hàm loadNotes(), thay đoạn hiển thị note-item bằng:
                                        filteredNotes.forEach(note => {
                                            notesList.innerHTML += `
        <div class="note-item p-2 border-b relative" data-id="${note.id}">
            <p><strong>${note.video_time}</strong>: <span class="note-text">${note.note}</span></p>
            <small>Phần ${note.section_order || 'N/A'} - ${note.section_name || 'Không xác định'} | Bài ${note.lesson_order || 'N/A'} - ${note.lesson_name || 'Không xác định'}</small>
            <div class="absolute right-2 top-2">
                <button class="dropdown-btn text-gray-400 hover:text-white focus:outline-none">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm0 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm0 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"></path>
                    </svg>
                </button>
                <div class="dropdown-menu absolute right-0 mt-2 w-28 bg-gray-800 border border-gray-700 rounded-md shadow-lg hidden z-50">
                    <button class="edit-note w-full text-left px-4 py-2 text-sm text-white hover:bg-gray-700">Sửa</button>
                    <button class="delete-note w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-700">Xóa</button>
                </div>
            </div>
        </div>
    `;
                                        });
                                        attachEventListeners();
                                    } else {
                                        notesList.innerHTML = '<p>Không tìm thấy ghi chú nào cho phần này.</p>';
                                    }
                                } else {
                                    notesList.innerHTML = '<p>Chưa có ghi chú nào.</p>';
                                }
                            })
                            .catch(error => {
                                console.error('Lỗi khi tải ghi chú:', error);
                                document.getElementById('notes-list').innerHTML = '<p>Lỗi khi tải ghi chú.</p>';
                            });
                    }

                    function attachEventListeners() {
                        // Xử lý nút ba chấm
                        document.querySelectorAll('.dropdown-btn').forEach(button => {
                            button.addEventListener('click', function(event) {
                                const dropdownMenu = this.nextElementSibling;
                                // Ẩn tất cả các dropdown khác
                                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                                    if (menu !== dropdownMenu) menu.classList.add('hidden');
                                });
                                dropdownMenu.classList.toggle('hidden');
                                event.stopPropagation(); // Ngăn sự kiện click lan ra ngoài
                            });
                        });

                        // Xử lý nút "Sửa"
                        document.querySelectorAll('.edit-note').forEach(button => {
                            button.addEventListener('click', function() {
                                const noteItem = this.closest('.note-item');
                                currentEditingNoteId = noteItem.getAttribute('data-id');
                                const noteText = noteItem.querySelector('.note-text').innerHTML;
                                openEditModal(noteText);
                                this.closest('.dropdown-menu').classList.add('hidden'); // Ẩn dropdown sau khi click
                            });
                        });

                        // Xử lý nút "Xóa"
                        document.querySelectorAll('.delete-note').forEach(button => {
                            button.addEventListener('click', function() {
                                const noteItem = this.closest('.note-item');
                                const noteId = noteItem.getAttribute('data-id');
                                Swal.fire({
                                    title: "Xác nhận xóa?",
                                    text: "Bạn có chắc chắn muốn xóa ghi chú này?",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#d33",
                                    cancelButtonColor: "#3085d6",
                                    confirmButtonText: "Xóa",
                                    cancelButtonText: "Hủy"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        deleteNote(noteId, noteItem);
                                    }
                                });
                                this.closest('.dropdown-menu').classList.add('hidden'); // Ẩn dropdown sau khi click
                            });
                        });

                        // Đóng dropdown khi click ra ngoài
                        document.addEventListener('click', function(event) {
                            if (!event.target.closest('.dropdown-btn') && !event.target.closest('.dropdown-menu')) {
                                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                                    menu.classList.add('hidden');
                                });
                            }
                        });
                    }

                    // Hàm xóa ghi chú (không load lại trang)
                    function deleteNote(id, noteItem) {
                        fetch(`/notes/delete/${id}`, {
                                method: 'POST'
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    noteItem.remove(); // Xóa phần tử khỏi DOM mà không load lại trang
                                    Swal.fire("Đã xóa!", "Ghi chú đã được xóa.", "success");
                                } else {
                                    Swal.fire("Lỗi!", "Không thể xóa ghi chú.", "error");
                                }
                            })
                            .catch(error => {
                                console.error('Lỗi khi xóa ghi chú:', error);
                                Swal.fire("Lỗi!", "Có lỗi xảy ra khi xóa ghi chú.", "error");
                            });
                    }

                    // Mở modal chỉnh sửa ghi chú
                    function openEditModal(noteText) {
                        editNoteContent.innerHTML = noteText;
                        document.getElementById('editNoteId').value = currentEditingNoteId;
                        editNoteModal.classList.remove('hidden');
                    }

                    // Xử lý submit form chỉnh sửa
                    document.getElementById('editNoteForm').addEventListener('submit', function(event) {
                        event.preventDefault();
                        const noteId = document.getElementById('editNoteId').value;
                        const noteContent = editNoteContent.innerHTML;
                        fetch(`/notes/edit/${noteId}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: `note=${encodeURIComponent(noteContent)}`
                            })
                            .then(response => response.text())
                            .then(text => {
                                console.log('Phản hồi từ server:', text);
                                try {
                                    const data = JSON.parse(text);
                                    if (data.status === 'success') {
                                        document.querySelector(`.note-item[data-id="${noteId}"] .note-text`).innerHTML = noteContent;
                                        Swal.fire("Thành công!", "Ghi chú đã được cập nhật!", "success");
                                        closeEditModal();
                                    } else {
                                        Swal.fire("Lỗi!", data.message || "Có lỗi khi cập nhật ghi chú.", "error");
                                    }
                                } catch (error) {
                                    console.error("Lỗi phân tích JSON:", error);
                                    Swal.fire("Lỗi!", "Dữ liệu trả về không hợp lệ.", "error");
                                }
                            })
                            .catch(error => {
                                console.error("Lỗi khi cập nhật ghi chú:", error);
                                Swal.fire("Lỗi!", "Có lỗi xảy ra khi cập nhật ghi chú.", "error");
                            });
                    });

                    // Xóa ghi chú
                    function deleteNote(id, noteItem) {
                        fetch(`/notes/delete/${id}`, {
                                method: 'POST'
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    noteItem.remove();
                                    Swal.fire("Đã xóa!", "Ghi chú đã được xóa.", "success");
                                    loadNotes(); // Tải lại danh sách sau khi xóa
                                } else {
                                    Swal.fire("Lỗi!", "Không thể xóa ghi chú.", "error");
                                }
                            })
                            .catch(error => {
                                console.error('Lỗi khi xóa ghi chú:', error);
                                Swal.fire("Lỗi!", "Có lỗi xảy ra khi xóa ghi chú.", "error");
                            });
                    }

                    // Xử lý form tạo ghi chú
                    document.getElementById("noteForm").addEventListener("submit", function(event) {
                        event.preventDefault();
                        let noteContent = document.getElementById("noteContent").innerHTML;
                        document.getElementById("note").value = noteContent;
                        let formData = new FormData(this);
                        fetch("/notes", {
                                method: "POST",
                                body: formData,
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === "success") {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Thành công!',
                                        text: data.message,
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        closeModal();
                                        if (notesSidebar.classList.contains('open')) {
                                            loadNotes(); // Tải lại danh sách sau khi thêm
                                        }
                                        document.getElementById("noteContent").innerHTML = "";
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Lỗi!',
                                        text: data.message,
                                        confirmButtonText: 'OK'
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi!',
                                    text: 'Có lỗi xảy ra khi gửi ghi chú.',
                                    confirmButtonText: 'OK'
                                });
                                console.error("Lỗi:", error);
                            });
                    });
                });

                // Đưa hàm closeEditModal ra toàn cục
                function closeEditModal() {
                    const editNoteModal = document.getElementById('editNoteModal');
                    editNoteModal.classList.add('hidden');
                }

                // Các hàm hỗ trợ khác
                function openModal() {
                    document.getElementById('noteModal').classList.remove('hidden');
                }

                function closeModal() {
                    document.getElementById('noteModal').classList.add('hidden');
                }

                function toggleActive(button, command) {
                    document.execCommand(command, false, null);
                    button.classList.toggle('bg-gray-500');
                }

                function toggleList(button, command) {
                    document.execCommand(command, false, null);
                    button.classList.toggle('bg-gray-500');
                }

                function insertImage() {
                    const url = prompt("Nhập URL hình ảnh:");
                    if (url) {
                        document.execCommand('insertImage', false, url);
                    }
                }

                function openColorPopup(popupId) {
                    document.getElementById(popupId).style.display = 'block';
                }

                function closeColorPopup(popupId) {
                    document.getElementById(popupId).style.display = 'none';
                }

                function changeTextColor() {
                    const color = document.getElementById('textColorPicker').value;
                    document.execCommand('foreColor', false, color);
                }

                function changeBgColor() {
                    const color = document.getElementById('bgColorPicker').value;
                    document.execCommand('backColor', false, color);
                }
            </script>

            <!-- Modal chỉnh sửa ghi chú -->
            <div id="editNoteModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 hidden">
                <div class="w-full max-w-2xl p-6 bg-gray-900 text-white rounded-2xl shadow-lg">
                    <div class="flex justify-between items-center border-b border-gray-700 pb-3 mb-4">
                        <p id="note-time" class="text-xl font-semibold">Chỉnh sửa ghi chú</p>
                        <button onclick="closeEditModal()" class="text-gray-400 hover:text-white">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                    <div class="bg-gray-800 p-4 rounded-lg">
                        <form id="editNoteForm">
                            <div class="flex flex-wrap gap-2 mb-3">
                                <button type="button" onclick="toggleActive(this, 'bold')" class="editor-btn"><i class="fas fa-bold"></i></button>
                                <button type="button" onclick="toggleActive(this, 'italic')" class="editor-btn"><i class="fas fa-italic"></i></button>
                                <button type="button" onclick="toggleList(this, 'insertUnorderedList')" class="editor-btn"><i class="fas fa-list-ul"></i></button>
                                <button type="button" onclick="toggleList(this, 'insertOrderedList')" class="editor-btn"><i class="fas fa-list-ol"></i></button>
                                <button type="button" onclick="insertImage()" class="editor-btn"><i class="fas fa-image"></i></button>
                                <button type="button" onclick="openColorPopup('textColorPopup')" class="editor-btn"><i class="fas fa-paint-brush"></i></button>
                                <button type="button" onclick="openColorPopup('bgColorPopup')" class="editor-btn"><i class="fas fa-fill-drip"></i></button>
                            </div>
                            <div contenteditable="true" id="editNoteContent" class="w-full bg-gray-700 text-white p-3 rounded-lg h-36 overflow-auto border border-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500"></div>
                            <input type="hidden" id="editNoteId">
                            <input type="hidden" id="users_id" name="users_id" value="<?php echo isset($users['id']) ? $users['id'] : ''; ?>">
                            <input type="hidden" name="courses_id" value="<?php echo $course['id']; ?>">
                            <input type="hidden" id="lessons_id" name="lessons_id" value="<?php echo $lessons_id = $_POST['lessons_id'] ?? null; ?>">
                            <div class="flex justify-end mt-4 gap-2">
                                <button type="button" onclick="closeEditModal()" class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">HỦY</button>
                                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-500 transition">LƯU</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>




            <h2 class="text-xl font-semibold mb-2 mt-3">Nội dung khóa học</h2>
            <div class="course-content">
                <?php
                $sectionIds = array_column($sections, 'id');
                $sectionOrderNumbers = array_column($sections, 'order_number', 'id');
                ?>
                <?php foreach ($sections as $section): ?>
                    <div class="chapter" onclick="toggleDropdown(this)">
                        <span>Phần <?php echo $section['order_number']; ?>: <?php echo htmlspecialchars($section['title'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                    <ul class="lesson-list">
                        <?php if (isset($lessonsBySection[$section['id']]) && is_array($lessonsBySection[$section['id']])): ?>
                            <?php
                            $isSectionLocked = false;
                            if ($section['order_number'] > 1) {
                                $prevSectionIndex = array_search($section['order_number'] - 1, $sectionOrderNumbers);
                                if ($prevSectionIndex !== false && isset($sectionIds[$prevSectionIndex])) {
                                    $prevSectionId = $sectionIds[$prevSectionIndex];
                                    if (isset($lessonsBySection[$prevSectionId]) && is_array($lessonsBySection[$prevSectionId])) {
                                        foreach ($lessonsBySection[$prevSectionId] as $prevLesson) {
                                            if (isset($lessonProgressById[$prevLesson['id']]) && !$lessonProgressById[$prevLesson['id']]['completed']) {
                                                $isSectionLocked = true;
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                            ?>
                            <?php foreach ($lessonsBySection[$section['id']] as $lesson): ?>
                                <?php
                                $lessonProgress = $lessonProgressById[$lesson['id']] ?? ['progress' => 0, 'completed' => false];
                                $isLocked = $isSectionLocked;
                                if (!$isSectionLocked && $lesson['order_number'] > 1) {
                                    $prevLessonIndex = $lesson['order_number'] - 2;
                                    if (isset($lessonsBySection[$section['id']][$prevLessonIndex])) {
                                        $prevLessonId = $lessonsBySection[$section['id']][$prevLessonIndex]['id'];
                                        $isLocked = !(isset($lessonProgressById[$prevLessonId]) && $lessonProgressById[$prevLessonId]['completed']);
                                    }
                                }
                                if ($lessonProgress['completed'] || ($lesson['order_number'] > 1 && isset($lessonsBySection[$section['id']][$prevLessonIndex]) && (isset($lessonProgressById[$lessonsBySection[$section['id']][$prevLessonIndex]['id']]) && $lessonProgressById[$lessonsBySection[$section['id']][$prevLessonIndex]['id']]['completed']))) {
                                    $isLocked = false;
                                }
                                ?>
                                <li class="lesson-item <?php echo $isLocked ? 'locked' : ''; ?>"
                                    data-section-id="<?php echo $section['id']; ?>">
                                    <a href="/courses/learning/<?php echo $course['id']; ?>?course=<?php echo $course['id']; ?>&lesson=<?php echo $lesson['id']; ?>"
                                        data-video="<?php echo htmlspecialchars($lesson['video_url'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-title="<?php echo htmlspecialchars($lesson['title'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-description="<?php echo htmlspecialchars($lesson['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                        data-lesson-id="<?php echo $lesson['id']; ?>"
                                        data-order-number="<?php echo $lesson['order_number']; ?>"
                                        class="<?php echo $isLocked ? 'disabled' : ''; ?>">
                                        Bài <?php echo $lesson['order_number']; ?>: <?php echo htmlspecialchars($lesson['title'], ENT_QUOTES, 'UTF-8'); ?>
                                        <span class="lesson-progress"><?php echo round($lessonProgress['progress']); ?>%</span>
                                    </a>
                                    <?php if ($isLocked): ?>
                                        <span class="lock-icon">🔒</span>
                                    <?php endif; ?>
                                    <hr class="my-1">
                                </li>
                            <?php endforeach; ?>
                            <a href="/quizzes/section/<?= htmlspecialchars($section['id']); ?>" class="btn btn-primary text-muted">Bài tập</a>
                        <?php endif; ?>
                    </ul>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        // Toggle dropdown cho các chapter
        function toggleDropdown(element) {
            const lessonList = element.nextElementSibling;
            const icon = element.querySelector("i");

            lessonList.classList.toggle("active");
            icon.classList.toggle("fa-chevron-right");
            icon.classList.toggle("fa-chevron-down");
        }

        // Xử lý click vào lesson để cập nhật video
        document.querySelectorAll(".lesson-item a").forEach(link => {
            link.addEventListener("click", function(e) {
                if (this.classList.contains("disabled")) {
                    e.preventDefault();
                    return;
                }

                const videoUrl = this.getAttribute("data-video");
                const title = this.getAttribute("data-title");
                const description = this.getAttribute("data-description");
                const orderNumber = this.getAttribute("data-order-number");

                const iframe = document.getElementById("video-iframe");
                const videoDescription = document.getElementById("video-description");

                iframe.src = videoUrl + "&enablejsapi=1";
                videoDescription.innerHTML = `
                    <p class="text-3xl m-5 mb-0"><strong>Bài ${orderNumber}: ${title}</strong></p>
                    <p class="mx-5">${description}</p>
                `;
            });
        });
    </script>
    <?php
    $courseId = $course['id'] ?? null;
    ?>

    <br>
    <br>

    
    <div class="reviews-container mt-8 p-4 bg-white rounded shadow">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Đánh giá từ học viên</h2>

        <!-- Danh sách đánh giá sẽ được JavaScript cập nhật -->
        <div id="reviews-list">
            <p>Chưa có đánh giá nào.</p>
        </div>
    </div>



    <div class="review-section mt-8 p-4 bg-white rounded shadow ">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Đánh giá khóa học</h2>

        <form id="review-form" class="space-y-4">
            <input type="hidden" name="course_id" value="<?= $courseId ?>">
            <input type="hidden" id="deleted-images" name="deleted_images">

            <!-- Chọn số sao -->
            <label class="block text-lg font-medium text-gray-700">Đánh giá của bạn:</label>
            <div class="flex space-x-1">
                <?php for ($i = 1; $i <= 5; $i++) : ?>
                    <button type="button" id="rating-<?= $i ?>" class="star-button text-gray-400 text-3xl" data-value="<?= $i ?>">★</button>
                <?php endfor; ?>
                <input type="hidden" name="rating" id="rating" required>
            </div>

            <!-- Nhận xét -->
            <label for="comment" class="block text-lg font-medium text-gray-700">Nhận xét:</label>
            <textarea name="comment" id="comment" rows="4" class="w-full border border-gray-300 rounded-lg p-3 focus:ring focus:ring-blue-300 focus:outline-none" placeholder="Nhập nhận xét của bạn..." required></textarea>

            <!-- Upload hình ảnh -->
            <label for="images" class="block text-lg font-medium text-gray-700">Hình ảnh (tùy chọn):</label>
            <input type="file" name="images[]" id="images" multiple class="border border-gray-300 p-2 w-full rounded-lg">
            <div id="image-preview" class="flex space-x-2 mt-2"></div>

            <!-- Nút gửi -->
            <button type="submit" id="submit-button" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg text-lg font-medium hover:bg-blue-700 transition duration-200">
                Gửi đánh giá
            </button>
        </form>

        <div id="review-message" class="mt-4 text-center text-lg font-medium"></div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("review-form");
            const ratingInput = document.getElementById("rating");
            const starButtons = document.querySelectorAll(".star-button");
            const imageInput = document.getElementById("images");
            const imagePreview = document.getElementById("image-preview");
            const submitButton = document.getElementById("submit-button");

            // Kiểm tra nếu không tìm thấy starButtons
            if (!starButtons || starButtons.length === 0) {
                console.error("Không tìm thấy phần tử .star-button nào!");
                return;
            }

            // Chọn số sao
            starButtons.forEach((star, index) => {
                star.addEventListener("click", function() {
                    let value = this.getAttribute("data-value");
                    ratingInput.value = value;

                    // Đổi màu các sao theo đánh giá
                    starButtons.forEach((s, i) => {
                        if (i < value) {
                            s.classList.add("text-yellow-400");
                        } else {
                            s.classList.remove("text-yellow-400");
                        }
                    });
                });
            });

            // Xem trước hình ảnh
            imageInput.addEventListener("change", function() {
                imagePreview.innerHTML = ""; // Xóa ảnh đã có trong preview
                Array.from(this.files).forEach((file) => {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let img = document.createElement("img");
                        img.src = e.target.result;
                        img.classList.add("w-16", "h-16", "object-cover", "rounded-md", "shadow-md");

                        // Kiểm tra ảnh đã được thêm chưa để tránh trùng lặp
                        let existingImgs = imagePreview.getElementsByTagName("img");
                        let isDuplicate = false;
                        for (let existingImg of existingImgs) {
                            if (existingImg.src === img.src) {
                                isDuplicate = true;
                                break;
                            }
                        }

                        if (!isDuplicate) {
                            imagePreview.appendChild(img);
                        }
                    };
                    reader.readAsDataURL(file);
                });
            });

            // Gửi đánh giá (thêm hoặc sửa)
            form.addEventListener("submit", function(event) {
                event.preventDefault(); // Ngăn chặn hành động mặc định của form

                let formData = new FormData(this);
                let reviewId = form.dataset.editId || ""; // Nếu có reviewId, nghĩa là đang sửa

                if (reviewId) {
                    formData.append("review_id", reviewId); // Đảm bảo review_id có giá trị hợp lệ
                }

                // Lấy giá trị từ các input
                let rating = ratingInput.value;
                formData.append("rating", rating); // Thêm rating vào formData

                let comment = document.getElementById("comment").value;
                formData.append("comment", comment); // Thêm comment vào formData

                // Thêm ảnh vào formData nếu có (chỉ thêm ảnh mới nếu đang thêm đánh giá)
                if (imageInput.files.length > 0) {
                    Array.from(imageInput.files).forEach((file) => {
                        formData.append("images[]", file); // Thêm mỗi ảnh vào formData
                    });
                }

                // Xử lý ảnh đã xóa
                let deletedImages = document.getElementById("deleted-images").value;
                if (deletedImages) {
                    formData.append("deleted_images", deletedImages); // Thêm ảnh đã xóa vào formData
                }

                let apiUrl = reviewId ? "/courses/review/update" : "/courses/review/add"; // Chọn URL API tùy thuộc vào việc đang thêm hay cập nhật

                fetch(apiUrl, {
                        method: "POST",
                        body: formData,
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        console.log("Response data:", data); // Kiểm tra nội dung trả về từ server
                        document.getElementById("review-message").innerText = data.message;
                        if (data.status === "success") {
                            location.reload(); // Nếu thành công, load lại trang để cập nhật danh sách đánh giá
                        }
                    })
                    .catch((error) => {
                        console.error("Lỗi khi gửi yêu cầu:", error);
                    });
            });


            // Lấy danh sách đánh giá
            let courseId = <?= json_encode($course['id']) ?>;
            let userId = <?= json_encode($_SESSION['user']['id'] ?? null) ?>;
            let isAdmin = <?= json_encode($_SESSION['user']['role'] === 'admin') ?>;

            fetch(`/courses/review/get?course_id=${courseId}&user_id=${userId}`)
                .then((response) => response.json())
                .then((data) => {
                    if (data.status === "success") {
                        let reviewsList = document.getElementById("reviews-list");
                        reviewsList.innerHTML = "";

                        if (data.reviews.length === 0) {
                            reviewsList.innerHTML = "<p>Chưa có đánh giá nào.</p>";
                        } else {
                            data.reviews.forEach((review) => {
                                let imageHtml = "";
                                if (review.images.length > 0) {
                                    review.images.forEach((image) => {
                                        imageHtml += `<img src="/${image}" class="w-16 h-16 object-cover rounded-md shadow-md mr-2">`;
                                    });
                                }

                                let isOwner = review.user_id == <?= json_encode($_SESSION['user']['id'] ?? null) ?>;

                                // 👉 **Tạo danh sách phản hồi**
                                let repliesHtml = review.replies?.map(reply => `
                        <div class="reply-item ml-6 p-2 border-l-2 border-gray-300">
                            <p class="font-semibold text-blue-600">${reply.admin_name} (Admin)</p>
                            <p>${reply.comment}</p>
                            <small class="text-gray-500">${reply.created_at}</small>
                        </div>
                    `).join("") || "";
                                let replyForm = isAdmin ? `
                        <div class="reply-form hidden ml-6 mt-2">
                            <textarea class="reply-text border p-2 w-full" placeholder="Viết phản hồi..."></textarea>
                            <button class="send-reply-btn bg-green-500 text-white px-3 py-1 mt-2 rounded-md" data-review-id="${review.id}">Gửi</button>
                        </div>
                    ` : "";

                                let reviewHtml = `
                        <div class="review-item p-3 border-b border-gray-300 relative" data-review-id="${review.id}">
                            <p class="font-semibold">${review.user_name}</p>
                            <p>${"★".repeat(review.rating)}${"☆".repeat(5 - review.rating)}</p>
                            <p>${review.comment}</p>
                            ${imageHtml ? `<div class="mt-2">${imageHtml}</div>` : ""}
                            <small class="text-gray-500">${review.created_at}</small>

                            <!-- Dropdown ba chấm -->
                            ${isOwner ? `
                                <div class="absolute right-0 top-0 mt-2">
                                    <button class="dropdown-btn text-gray-600 hover:text-black focus:outline-none">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm0 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm0 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"></path>
                                        </svg>
                                    </button>
                                    <div class="dropdown-menu absolute right-0 mt-2 w-28 bg-white border rounded-md shadow-lg hidden">
                                        <button class="edit-review w-full text-left px-4 py-2 text-sm hover:bg-gray-100" 
                                            data-id="${review.id}" 
                                            data-rating="${review.rating}" 
                                            data-comment="${review.comment}" 
                                            data-images='${JSON.stringify(review.images)}'>Sửa</button>
                                        <button class="delete-review w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100" 
                                            data-id="${review.id}">Xóa</button>
                                    </div>
                                </div>
                            ` : ""}

                            <!-- Nút Like -->
                            <div class="flex space-x-4 mt-2">
                                <button class="like-btn flex items-center space-x-2" data-review-id="${review.id}">
                                    <svg class="like-icon w-6 h-6 ${review.liked ? 'text-blue-500' : 'text-gray-500'}" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14 9V5a3 3 0 0 0-6 0v4H5a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-5zM9 5a1 1 0 0 1 2 0v4H9V5zm8 14H5v-8h3.5l.5-4h6l.5 4H19v8z"></path>
                                    </svg>
                                    <span class="like-count">${review.like_count}</span>
                                </button>

                                <button class="reply-btn text-blue-500 underline" data-review-id="${review.id}">Trả lời</button>
                            </div>
        
                            

                            <!-- Hiển thị phản hồi -->
                            <div class="replies-container ml-6 mt-2">${repliesHtml}</div>
                            ${replyForm}
                        </div>
                    `;

                                reviewsList.innerHTML += reviewHtml;
                            });

                            // Event delegation: Sự kiện click cho nút ba chấm


                            document.getElementById("reviews-list").addEventListener("click", function(event) {
                                let target = event.target;

                                if (event.target.closest(".dropdown-btn")) {
                                    let dropdownMenu = event.target.closest(".dropdown-btn").nextElementSibling;
                                    document.querySelectorAll(".dropdown-menu").forEach(menu => {
                                        if (menu !== dropdownMenu) menu.classList.add("hidden");
                                    });
                                    dropdownMenu.classList.toggle("hidden");
                                    event.stopPropagation();
                                }

                                // Xử lý nút like
                                if (target.closest(".like-btn")) {
                                    let button = target.closest(".like-btn");
                                    let reviewId = button.getAttribute("data-review-id");
                                    toggleLike(reviewId, button);
                                }

                                // Xử lý nút trả lời
                                if (target.classList.contains("reply-btn")) {
                                    let reviewId = target.getAttribute("data-review-id");
                                    toggleReplyInput(reviewId);
                                }

                                // Xử lý gửi phản hồi
                                if (target.classList.contains("send-reply-btn")) {
                                    let reviewId = target.getAttribute("data-review-id");
                                    let comment = target.previousElementSibling.value.trim();
                                    if (comment) {
                                        sendReply(reviewId, comment, target);
                                    }
                                }
                            });



                            // Đóng dropdown khi click ra ngoài
                            document.addEventListener("click", function() {
                                document.querySelectorAll(".dropdown-menu").forEach(menu => {
                                    menu.classList.add("hidden");
                                });
                            });

                        }
                    }
                })
                .catch((error) => console.error("Lỗi:", error));
        });

        // Hiển thị hộp nhập phản hồi
        function toggleReplyInput(reviewId) {
            let reviewItem = document.querySelector(`.review-item[data-review-id="${reviewId}"]`);
            if (!reviewItem) {
                console.error("Không tìm thấy review-item với ID:", reviewId);
                return;
            }
            let replyForm = reviewItem.querySelector(".reply-form");
            if (replyForm) {
                replyForm.classList.toggle("hidden");
            } else {
                console.error("Không tìm thấy reply-form trong review-item:", reviewId);
            }
        }



        function sendReply(reviewId, comment, button) {
            fetch(`/courses/review/reply`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: `review_id=${reviewId}&comment=${encodeURIComponent(comment)}`
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Dữ liệu phản hồi:", data);

                    if (data.status === "success") {
                        let reply = data.reply;

                        let replyHtml = `
                    <div class="reply-item ml-6 p-2 border-l-2 border-gray-300">
                        <p class="font-semibold text-blue-600">${reply.admin_name} (Admin)</p>
                        <p>${reply.comment}</p>
                        <small class="text-gray-500">Vừa xong</small>
                    </div>
                `;

                        let reviewItem = document.querySelector(`.review-item[data-review-id="${reviewId}"]`);
                        if (reviewItem) {
                            let replyContainer = reviewItem.querySelector(".replies-container");
                            if (!replyContainer) {
                                replyContainer = document.createElement("div");
                                replyContainer.classList.add("replies-container", "ml-6", "mt-2");
                                reviewItem.appendChild(replyContainer);
                            }
                            replyContainer.insertAdjacentHTML("beforeend", replyHtml);
                        }

                        button.previousElementSibling.value = "";
                        button.closest(".reply-form").classList.add("hidden");
                    } else {
                        alert("Lỗi khi gửi phản hồi!");
                    }
                })
                .catch(error => console.error("Lỗi:", error));
        }




        // Xử lý xóa đánh giá
        document.addEventListener("click", function(event) {
            if (event.target.classList.contains("delete-review")) {
                let reviewId = event.target.getAttribute("data-id");

                if (confirm("Bạn có chắc muốn xóa đánh giá này?")) {
                    let formData = new FormData();
                    formData.append("review_id", reviewId);

                    fetch("/courses/review/delete", {
                            method: "POST",
                            body: formData,
                        })
                        .then((response) => response.json())

                        .then((data) => {
                            alert(data.message);
                            if (data.status === "success") {
                                event.target.closest(".review-item").remove();
                            }
                        })
                        .catch((error) => console.error("Lỗi:", error));
                }
            }
        });

        // Xử lý sửa đánh giá
        document.addEventListener("click", function(event) {
            if (event.target.classList.contains("edit-review")) {
                let reviewId = event.target.getAttribute("data-id");
                let rating = parseInt(event.target.getAttribute("data-rating"));
                let comment = event.target.getAttribute("data-comment");
                let images = JSON.parse(event.target.getAttribute("data-images"));

                document.getElementById("rating").value = rating;
                document.getElementById("comment").value = comment;
                document.getElementById("review-form").dataset.editId = reviewId;
                document.getElementById("submit-button").textContent = "Cập nhật đánh giá";

                // Cập nhật giao diện sao
                document.querySelectorAll(".star-button").forEach((star, index) => {
                    star.classList.toggle("text-yellow-400", index < rating);
                });

                // Hiển thị ảnh cũ
                let imagePreview = document.getElementById("image-preview");
                imagePreview.innerHTML = "";
                let deletedImages = [];

                images.forEach((imgSrc) => {
                    let imgContainer = document.createElement("div");
                    imgContainer.classList.add("relative", "inline-block", "mr-2");

                    let img = document.createElement("img");
                    img.src = "/" + imgSrc;
                    img.classList.add("w-16", "h-16", "object-cover", "rounded-md", "shadow-md");

                    let removeBtn = document.createElement("button");
                    removeBtn.innerHTML = "x";
                    removeBtn.classList.add("absolute", "top-0", "right-0", "bg-red-500", "text-white", "text-xs", "rounded-full", "px-1");

                    removeBtn.onclick = function() {
                        imgContainer.remove();
                        deletedImages.push(imgSrc); // Thêm ảnh vào mảng deletedImages khi xóa
                        document.getElementById("deleted-images").value = JSON.stringify(deletedImages); // Cập nhật lại giá trị input hidden
                    };

                    imgContainer.appendChild(img);
                    imgContainer.appendChild(removeBtn);
                    imagePreview.appendChild(imgContainer);
                });
            }
        });





        function toggleLike(reviewId, button) {
            fetch(`/courses/review/like`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: `review_id=${reviewId}`
                })
                .then(response => response.json())
                .then(data => {
                    let likeIcon = button.querySelector(".like-icon");
                    let likeCount = button.querySelector(".like-count");

                    if (data.status === "liked") {
                        likeIcon.classList.add("text-blue-500");
                        likeIcon.classList.remove("text-gray-500");
                    } else if (data.status === "unliked") {
                        likeIcon.classList.remove("text-blue-500");
                        likeIcon.classList.add("text-gray-500");
                    }

                    // Cập nhật số like
                    likeCount.innerText = data.like_count;
                })
                .catch(error => console.error("Lỗi:", error));
        }
    </script>
</main>
<script src="https://www.youtube.com/iframe_api"></script>
<script>
    console.log('Script loaded');
    let player;
    let currentLessonId;

    try {
        console.log('Loading YouTube IFrame API');
        window.onYouTubeIframeAPIReady = function() {
            console.log('YouTube IFrame API ready');

            // Khởi tạo player
            player = new YT.Player('video-iframe', {
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange,
                    'onError': (event) => console.log('Player error:', event.data)
                }
            });

            // Gọi updateCourseProgressAndEnrollment khi trang được tải
            updateCourseProgressAndEnrollment();

            document.querySelectorAll(".lesson-item a:not(.disabled)").forEach(item => {
                item.addEventListener("click", function(e) {
                    e.preventDefault();
                    let videoUrl = this.dataset.video;
                    let title = this.dataset.title;
                    let description = this.dataset.description;
                    let lessonId = this.dataset.lessonId;
                    let orderNumber = this.dataset.orderNumber;
                    let pdf_file = this.dataset.pdf_file;
                    changeVideo(videoUrl, title, description, lessonId, orderNumber, pdf_file);
                });
            });

            let params = new URLSearchParams(window.location.search);
            let lessonId = params.get("lesson");
            console.log('Initial lesson ID:', lessonId);

            if (lessonId) {
                let activeLesson = document.querySelector(`[data-lesson-id='${lessonId}']`);
                if (activeLesson && !activeLesson.classList.contains('disabled')) {
                    changeVideo(activeLesson.dataset.video, activeLesson.dataset.title, activeLesson.dataset.description, lessonId, activeLesson.dataset.orderNumber);
                } else {
                    console.log('Lesson not found or locked:', lessonId);
                }
            } else {
                let firstLesson = document.querySelector(".lesson-item a:not(.disabled)");
                if (firstLesson) {
                    changeVideo(firstLesson.dataset.video, firstLesson.dataset.title, firstLesson.dataset.description, firstLesson.dataset.lessonId, firstLesson.dataset.orderNumber);
                } else {
                    console.log('No unlocked lesson found');
                }
            }
        };

        function changeVideo(videoUrl, title, description, lessonId, orderNumber) {
            console.log('Changing video to:', videoUrl, 'Lesson ID:', lessonId);
            currentLessonId = lessonId;
            if (player) {
                player.destroy();
            }
            document.getElementById("lessons_id").value = lessonId;
            let iframe = document.getElementById("video-iframe");
            if (!iframe) {
                console.error('Iframe not found! Attempting to recover...');
                const videoPlayer = document.querySelector('.video-player');
                if (videoPlayer) {
                    iframe = document.createElement('iframe');
                    iframe.id = 'video-iframe';
                    iframe.width = '100%';
                    iframe.height = '400';
                    iframe.frameBorder = '0';
                    iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
                    iframe.allowFullscreen = true;
                    videoPlayer.prepend(iframe);
                    console.log('Iframe recreated:', iframe);
                } else {
                    console.error('Video player container not found!');
                    return;
                }
            }
            iframe.src = videoUrl + "&enablejsapi=1";
            document.getElementById("video-description").innerHTML = `<p class="text-3xl m-5 mb-0"><strong>Bài ${orderNumber}: ${title}</strong></p><p class="mx-5">${description}</p>`;
            history.pushState(null, "", `?course=<?php echo $course['id']; ?>&lesson=${lessonId}`);

            console.log('Initializing YT.Player with iframe:', iframe);
            player = new YT.Player('video-iframe', {
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange,
                    'onError': (event) => console.log('Player error:', event.data)
                }
            });
            console.log('YT.Player initialized:', player);
        }

        function onPlayerReady(event) {
            console.log('Player ready');
        }

        let progressInterval;
        let lastProgress = 0;

        function onPlayerStateChange(event) {
            console.log('Player state changed:', event.data);
            if (event.data === YT.PlayerState.PLAYING) {
                console.log('Video is playing');
                clearInterval(progressInterval);
                progressInterval = setInterval(() => {
                    let currentTime = player.getCurrentTime();
                    let duration = player.getDuration();
                    let progress = (currentTime / duration) * 100 || 0;
                    let completed = progress >= 95;

                    // Cập nhật thời gian trên nút "Thêm Ghi Chú"
                    updateNoteButtonTime();

                    document.getElementById("current-lesson-progress").textContent = `Tiến độ bài học: ${Math.round(progress)}%`;
                    let lessonLink = document.querySelector(`[data-lesson-id='${currentLessonId}']`);
                    if (lessonLink) {
                        lessonLink.querySelector('.lesson-progress').textContent = `${Math.round(progress)}%`;
                    }

                    console.log('Current time:', currentTime, 'Duration:', duration, 'Progress:', progress);
                    if (Math.abs(progress - lastProgress) >= 5 || completed) {
                        let payload = {
                            user_id: <?php echo $_SESSION['user']['id']; ?>,
                            lesson_id: currentLessonId,
                            progress: progress,
                            completed: completed
                        };
                        console.log('Sending payload:', payload);
                        fetch('/update_progress', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        }).then(response => {
                            console.log('Response status:', response.status);
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.json();
                        }).then(data => {
                            console.log('Response data:', data);
                            if (data.status === 'success') {
                                lastProgress = progress;
                                if (completed) {
                                    unlockNextLesson(currentLessonId);
                                    goToNextLesson(currentLessonId);
                                    updateCourseProgressAndEnrollment();
                                }
                            }
                        }).catch(error => {
                            console.error('Fetch error:', error);
                        });
                    }
                }, 1000);
            } else if (event.data === YT.PlayerState.ENDED) {
                console.log('Video ended');
                clearInterval(progressInterval);
                let payload = {
                    user_id: <?php echo $_SESSION['user']['id']; ?>,
                    lesson_id: currentLessonId,
                    progress: 100,
                    completed: true
                };
                document.getElementById("current-lesson-progress").textContent = `Tiến độ bài học: 100%`;
                let lessonLink = document.querySelector(`[data-lesson-id='${currentLessonId}']`);
                if (lessonLink) {
                    lessonLink.querySelector('.lesson-progress').textContent = '100%';
                }
                fetch('/update_progress', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                }).then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                }).then(data => {
                    console.log('Response data:', data);
                    if (data.status === 'success') {
                        unlockNextLesson(currentLessonId);
                        goToNextLesson(currentLessonId);
                        updateCourseProgressAndEnrollment();
                    }
                }).catch(error => {
                    console.error('Fetch error:', error);
                });
            } else {
                console.log('Video stopped or paused');
                clearInterval(progressInterval);
            }
        }

        // Hàm cập nhật thời gian trên nút "Thêm Ghi Chú"
        function updateNoteButtonTime() {
            if (player && typeof player.getCurrentTime === 'function') {
                let currentTime = player.getCurrentTime();
                let minutes = Math.floor(currentTime / 60);
                let seconds = Math.floor(currentTime % 60);
                let formattedTime = `${minutes < 10 ? '0' + minutes : minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
                document.getElementById('note-button').innerHTML = `<i class="fas fa-plus mr-2"></i> Thêm Ghi Chú ${formattedTime}`;
            }
        }

        // Hàm mở modal
        function openModal() {
            player.pauseVideo();
            let currentTime = player.getCurrentTime();
            let minutes = Math.floor(currentTime / 60);
            let seconds = Math.floor(currentTime % 60);
            let formattedTime = `${minutes < 10 ? '0' + minutes : minutes}:${seconds < 10 ? '0' + seconds : seconds}`;

            document.getElementById('note-time').innerHTML = `Thêm ghi chú tại <span class="bg-purple-500 text-white px-2 py-1 rounded">${formattedTime}</span>`;
            document.getElementById('video_time').value = formattedTime;
            document.getElementById('noteModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('noteModal').classList.add('hidden');
        }

        function toggleActive(button, command) {
            document.execCommand(command, false, null);
            button.classList.toggle('bg-purple-500');
        }

        function toggleList(button, command) {
            let editor = document.getElementById("noteContent");
            editor.focus();
            document.execCommand(command, false, null);
        }

        function insertImage() {
            let url = prompt("Nhập URL hình ảnh:");
            if (url) {
                document.execCommand('insertImage', false, url);
            }
        }

        function changeTextColor() {
            let color = document.getElementById('textColorPicker').value;
            document.execCommand('foreColor', false, color);
        }

        function changeBgColor() {
            let color = document.getElementById('bgColorPicker').value;
            document.getElementById('noteContent').style.backgroundColor = color;
        }

        function openColorPopup(popupId) {
            document.getElementById(popupId).style.display = "block";
        }

        function closeColorPopup(popupId) {
            document.getElementById(popupId).style.display = "none";
        }

        function unlockNextLesson(currentLessonId) {
            console.log('Unlocking next lesson after:', currentLessonId);
            let allLessons = document.querySelectorAll('.lesson-item a');
            let currentLessonIndex = -1;
            let currentSectionId = null;

            for (let i = 0; i < allLessons.length; i++) {
                if (allLessons[i].dataset.lessonId === currentLessonId) {
                    currentLessonIndex = i;
                    currentSectionId = allLessons[i].parentElement.dataset.sectionId;
                    break;
                }
            }

            if (currentLessonIndex !== -1) {
                let nextLessonIndex = currentLessonIndex + 1;
                if (nextLessonIndex < allLessons.length) {
                    let nextLesson = allLessons[nextLessonIndex];
                    let nextLessonItem = nextLesson.parentElement;
                    let nextSectionId = nextLessonItem.dataset.sectionId;

                    if (currentSectionId !== nextSectionId) {
                        let currentSectionLessons = document.querySelectorAll(`.lesson-item[data-section-id='${currentSectionId}'] a`);
                        let allCompleted = true;
                        currentSectionLessons.forEach(lesson => {
                            let progress = parseInt(lesson.querySelector('.lesson-progress').textContent);
                            if (progress < 95) {
                                allCompleted = false;
                            }
                        });

                        if (allCompleted) {
                            let nextSectionLessons = document.querySelectorAll(`.lesson-item[data-section-id='${nextSectionId}']`);
                            nextSectionLessons.forEach(item => {
                                let lessonLink = item.querySelector('a');
                                lessonLink.classList.remove('disabled');
                                item.classList.remove('locked');
                                item.querySelector('.lock-icon')?.remove();
                                lessonLink.onclick = function(e) {
                                    e.preventDefault();
                                    changeVideo(lessonLink.dataset.video, lessonLink.dataset.title, lessonLink.dataset.description, lessonLink.dataset.lessonId, lessonLink.dataset.orderNumber);
                                };
                            });
                            console.log('Unlocked section:', nextSectionId);
                        }
                    } else {
                        nextLesson.classList.remove('disabled');
                        nextLessonItem.classList.remove('locked');
                        nextLessonItem.querySelector('.lock-icon')?.remove();
                        nextLesson.onclick = function(e) {
                            e.preventDefault();
                            changeVideo(nextLesson.dataset.video, nextLesson.dataset.title, nextLesson.dataset.description, nextLesson.dataset.lessonId, nextLesson.dataset.orderNumber);
                        };
                        console.log('Unlocked next lesson:', nextLesson.dataset.lessonId);
                    }
                }
            }
        }

        function goToNextLesson(currentLessonId) {
            console.log('Going to next lesson from:', currentLessonId);
            let allLessons = document.querySelectorAll('.lesson-item a');
            let currentLessonIndex = -1;

            for (let i = 0; i < allLessons.length; i++) {
                if (allLessons[i].dataset.lessonId === currentLessonId) {
                    currentLessonIndex = i;
                    break;
                }
            }

            if (currentLessonIndex !== -1) {
                let nextLessonIndex = currentLessonIndex + 1;
                if (nextLessonIndex < allLessons.length) {
                    let nextLesson = allLessons[nextLessonIndex];
                    let videoUrl = nextLesson.dataset.video;
                    let title = nextLesson.dataset.title;
                    let description = nextLesson.dataset.description;
                    let lessonId = nextLesson.dataset.lessonId;
                    let orderNumber = nextLesson.dataset.orderNumber;
                    let file_path = nextLesson.dataset.file_path;
                    changeVideo(videoUrl, title, description, lessonId, orderNumber, file_path);
                    console.log('Moved to next lesson:', lessonId);
                } else {
                    console.log('No next lesson available');
                }
            }
        }

        function updateCourseProgressAndEnrollment() {
            console.log('Starting updateCourseProgressAndEnrollment');
            const payload = {
                user_id: <?php echo $_SESSION['user']['id']; ?>,
                course_id: <?php echo $course['id']; ?>
            };
            fetch('/calculate_progress', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            }).then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            }).then(data => {
                if (data.status === 'success') {
                    let courseProgress = Math.round(data.progress * 100) / 100;
                    document.getElementById("course-progress").textContent = `Tiến độ khóa học: ${courseProgress}%`;
                    document.getElementById("progress-bar").style.width = `${courseProgress}%`;

                    let status = courseProgress >= 100 ? 'completed' : (courseProgress > 0 ? 'in-progress' : 'enrolled');
                    const enrollmentPayload = {
                        user_id: <?php echo $_SESSION['user']['id']; ?>,
                        course_id: <?php echo $course['id']; ?>,
                        enrollment_date: '<?php echo date('Y-m-d H:i:s'); ?>',
                        status: status,
                        progress: courseProgress
                    };
                    return fetch('/update_enrollment', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(enrollmentPayload)
                    });
                }
            }).then(response => {
                if (!response) return;
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            }).then(data => {
                console.log('Update enrollment response:', data);
                if (data.status === 'success' && data.certificate && data.certificate.status === 'success') {
                    const notice = document.getElementById('certificate-notice');
                    notice.style.display = 'block';
                    console.log('Certificate sent to email:', data.certificate.certificate_code);
                }
            }).catch(error => console.error('Error:', error));
        }

        function toggleDropdown(element) {
            let content = element.nextElementSibling;
            let icon = element.querySelector("i");
            document.querySelectorAll(".lesson-list").forEach(ul => {
                if (ul !== content) ul.style.display = "none";
            });
            content.style.display = (content.style.display === "block") ? "none" : "block";
            icon.classList.toggle("rotate-90");
        }

        setTimeout(() => {
            if (!window.YT) console.error('YouTube API not loaded after 5 seconds');
            else console.log('YouTube API loaded:', window.YT);
        }, 5000);

        setTimeout(() => {
            if (typeof window.onYouTubeIframeAPIReady === 'function') {
                console.log('Manually triggering onYouTubeIframeAPIReady');
                window.onYouTubeIframeAPIReady();
            }
        }, 2000);

    } catch (error) {
        console.error('JavaScript error:', error);
    }
</script>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f9f9f9;
    }

    .container-learning {
        display: flex;
        max-width: 1900px;
        margin: 0 auto;
    }

    .video-section {
        flex: 3;
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .video-player iframe {
        width: 100%;
        height: 600px;
        border-radius: 10px;
    }

    .sidebar {
        flex: 1;
        background: white;
        padding: 20px;
        border-radius: 10px;
        
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .chapter {
        font-size: 16px;
        font-weight: bold;
        background: #f1f1f1;
        padding: 10px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .lesson-list {
        list-style: none;
        padding-left: 15px;
        display: none;
    }

    .lesson-item {
        padding: 5px 0;
        font-size: 14px;
        color: #333;
        position: relative;
    }

    .lesson-item a {
        text-decoration: none;
        color: inherit;
        display: block;
        padding: 5px;
    }

    .lesson-item a:hover {
        background: #f1f1f1;
    }
    .lesson-item.locked a {
        color: #999;
        cursor: not-allowed;
    }

    .lesson-item .disabled {
        pointer-events: none;
    }

    .lesson-progress {
        float: right;
        color: #007bff;
        font-size: 12px;
    }

    .rotate-90 {
        transform: rotate(90deg);
        transition: transform 0.3s ease;
    }

    .progress-container {
        width: 100%;
        background: #e9ecef;
        border-radius: 5px;
        height: 10px;
        margin-top: 5px;
    }

    .progress-fill {
        background: #007bff;
        height: 100%;
        border-radius: 5px;
        transition: width 0.3s ease;
    }

    #current-lesson-progress {
        display: block;
        margin-top: 5px;
        font-size: 14px;
        color: #333;
    }

    .lock-icon {
        margin-left: 5px;
    }
</style>
<style>
    .editor-btn {
        @apply bg-gray-700 text-white p-2 rounded-lg hover:bg-gray-600 transition;
    }
</style>

<style>
    /* Đảm bảo editNoteModal hiển thị trên tất cả */
    #editNoteModal {
        z-index: 100;
        /* Giá trị cao hơn sidebar hoặc các phần tử khác */
    }
</style>
<style>
    .note-item {
        position: relative;
    }

    .dropdown-btn {
        background: none;
        border: none;
        cursor: pointer;
    }

    .dropdown-menu {
        z-index: 50;
        /* Đảm bảo dropdown hiển thị trên các phần tử khác */
    }

    .dropdown-menu.hidden {
        display: none;
    }
</style>
<style>
    #notes-sidebar {
        width: 500px;
        /* Thay 500px bằng giá trị bạn muốn */
    }

    /* Đảm bảo sidebar nằm trên cùng các phần tử khác */
    #notes-sidebar {
        z-index: 50;
        /* Đặt z-index cao để hiển thị trên các phần tử khác */
    }

    /* Khi sidebar mở */
    #notes-sidebar.open {
        transform: translateX(0);
        /* Dịch chuyển vào màn hình */
    }

    /* Tùy chỉnh giao diện danh sách ghi chú (sau này bạn có thể thêm style cho danh sách) */
    #notes-list {
        max-height: calc(100% - 60px);
        /* Trừ chiều cao của header sidebar */
        overflow-y: auto;
        /* Cuộn nếu danh sách dài */
    }
</style>