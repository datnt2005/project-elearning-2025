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
                <div class="bg-slate-100 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                    <a href="/courses/show/<?php echo $course['id']; ?>">
                        <div class="relative aspect-video">
                            <img class="" width="100%" height="200px" src="http://localhost:8000/<?= htmlspecialchars($course['image']) ?>" alt="">
                        </div>
                        <div class="p-4">
                        <h3 class="text-xl font-bold text-gray-800"><?php echo $course['title']; ?></h3>
                            <div class="flex items-center ">
                                <span class="text-gray-500 line-through "> <?php echo number_format($course['price']); ?>đ</span>
                                <span class="text-[#f05123] font-bold mx-3"> <?php echo number_format($course['discount_price']); ?>đ</span>
                            </div>
                            <div class="instructor flex items-center">
                                <img class="rounded-full" width="30px" height="30px" src="http://localhost:8000/uploads/<?= htmlspecialchars($course['instructor_image']) ?>" alt="avt">
                                <span class="mx-2 text-gray-600"> <?php echo $course['instructor_name']; ?> </span>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>

                <!-- JavaScript Pro Course -->
            
            </div>
        </section>
    </main>