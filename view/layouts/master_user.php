 
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>P6 - Passion</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="view/layouts/style.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0 !important;
            }
        }

        @media (max-width: 640px) {
            .search-input {
                display: none;
            }
        }
    </style>
</head>

<body style="margin: 0;" class="bg-gray-100">
    <nav class="bg-white shadow-sm fixed w-full top-0 z-10">
        <div class="container mx-auto px-4">
            <div class="flex flex-col sm:flex-row justify-between items-center h-auto sm:h-16 py-4 sm:py-0">
                <div class="flex items-center justify-between w-full sm:w-auto">
                    <a href="/" class="text-2xl font-bold text-[#f05123]">P6</a>
                    <span class="ml-2 text-gray-700 hidden sm:inline">Học online không khó</span>
                    <!-- Mobile menu button -->
                    <button class="sm:hidden text-gray-700 focus:outline-none" @click="dropdownOpen = !dropdownOpen">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
                <div class="flex items-center flex-col sm:flex-row mt-4 sm:mt-0 w-full sm:w-auto"
                    x-data="{ loggedIn: <?= json_encode($loggedIn) ?>, dropdownOpen: false, userName: '<?= $userName ?>' }">
                    <div class="relative mx-0 sm:mx-4 w-full sm:w-[420px] search-input">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <i class="fas fa-search text-gray-400"></i>
                        </span>
                        <input type="text" placeholder="Tìm kiếm khóa học, bài viết, video, ..."
                            class="pl-10 pr-4 py-2 w-full rounded-full border border-gray-300 focus:outline-none focus:border-[#f05123]">
                    </div>

                    <!-- User Section -->
                    <div class="mt-4 sm:mt-0">
                        <template x-if="!loggedIn">
                            <div class="flex space-x-4">
                                <a href="/register"
                                    class="px-4 py-2 text-[#f05123] font-medium hover:text-[#f05123]/90 no-underline">Đăng
                                    ký</a>
                                <a href="/login"
                                    class="px-4 py-2 bg-[#f05123] text-white rounded-full font-medium hover:bg-[#f05123]/90 no-underline">Đăng
                                    nhập</a>
                            </div>
                        </template>

                        <template x-if="loggedIn">
                            <div class="relative">
                                <button @click="dropdownOpen = !dropdownOpen"
                                    class="flex items-center px-4 py-2 text-gray-700 font-medium focus:outline-none">
                                    <img src="http://localhost:8000/<?= !empty($user['image']) ? 'uploads/' . $user['image'] : 'uploads/avatar/default-avatar.png' ?>"
                                        alt="User Avatar" class="w-8 h-8 rounded-full">
                                    <span class="ml-2" x-text="userName"></span>
                                </button>
                                <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                                    class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg">
                                    <a href="/profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Trang cá
                                        nhân</a>
                                    <a href="/orderList" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Khóa
                                        học đã mua</a>
                                    <a href="/settings" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Cài
                                        đặt</a>
                                    <a href="/logout" class="block px-4 py-2 text-red-500 hover:bg-gray-100">Đăng
                                        xuất</a>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar fixed left-0 top-16 w-[96px] h-screen bg-white border-r border-gray-200">
        <div class="flex flex-col items-center py-6 space-y-8">
            <a href="/" class="flex flex-col items-center text-gray-700 hover:text-[#f05123]">
                <i class="fas fa-home text-xl"></i>
                <span class="mt-1 text-xs">Trang chủ</span>
            </a>
            <a href="#" class="flex flex-col items-center text-gray-700 hover:text-[#f05123]">
                <i class="fas fa-road text-xl"></i>
                <span class="mt-1 text-xs">Lộ trình</span>
            </a>
            <a href="/posts" class="flex flex-col items-center text-gray-700 hover:text-[#f05123]">
                <i class="fas fa-newspaper text-xl"></i>
                <span class="mt-1 text-xs">Bài viết</span>
            </a>
        </div>
    </div>


    <!-- Main Content -->
    <main class="main-content container mx-auto px-4 pt-20 ">
        <?= $content ?>
    </main>

    
   <!-- Chatbot Toggler -->
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


   

     

        




    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/emoji-mart@latest/dist/browser.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>
    <script src="view/layouts/script.js"></script>
</body>

</html>