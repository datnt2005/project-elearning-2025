<main class="ml-24 pt-20 px-4">
        <!-- Hero Section -->
        <div class="relative overflow-hidden rounded-2xl mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-8 flex items-center justify-between">
                <div class="max-w-xl">
                    <h1 class="text-4xl font-bold text-white mb-4">Học ReactJS Miễn Phí!</h1>
                    <p class="text-white/90 mb-6">
                        Khóa học ReactJS từ cơ bản tới nâng cao. Kết quả của khóa học này là bạn có thể làm hầu hết các dự án thường gặp với ReactJS.
                    </p>
                    <button class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-white/90">
                        ĐĂNG KÝ NGAY
                    </button>
                </div>
                <div class="flex-shrink-0">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a7/React-icon.svg/1200px-React-icon.svg.png" 
                         alt="ReactJS Logo" 
                         class="w-64 h-64 object-contain">
                </div>
            </div>
        </div>

        <!-- Pro Courses Section -->
        <section>
            <h2 class="text-2xl font-bold mb-6 flex items-center">
                Khóa học Pro
                <span class="ml-2 px-2 py-1 bg-blue-500 text-white text-xs rounded">MỚI</span>
            </h2>
            <div class="grid grid-cols-3 gap-6">
                <!-- HTML CSS Pro Course -->
               <?php foreach ($courses as $course) : ?>
                <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                    <a href="/courses/show/<?php echo $course['id']; ?>">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 aspect-video relative">
                            <span class="absolute top-2 left-2">
                                <i class="fas fa-crown text-yellow-400"></i>
                            </span>
                            <div class="absolute inset-0 flex items-center justify-center">
                                
                                <h3 class="text-2xl font-bold text-white"><?php echo $course['title']; ?></h3>
                            </div>
                        </div>
                        <div class="p-4">
                            <p class="text-gray-600 mb-4"><?php echo $course['description']; ?></p>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 line-through"> <?php echo $course['price']; ?></span>
                                <span class="text-[#f05123] font-bold"> <?php echo $course['discount_price']; ?></span>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>

                <!-- JavaScript Pro Course -->
            
            </div>
        </section>
    </main>