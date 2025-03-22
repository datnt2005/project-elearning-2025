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


    <section>
        <h2 class="text-2xl font-bold mb-6 flex items-center">
            Khóa học Pro
            <span class="ml-2 px-2 py-1 bg-blue-500 text-white text-xs rounded">MỚI</span>
        </h2>
        <div class="grid grid-cols-3 gap-6">
            <!-- HTML CSS Pro Course -->
            <?php foreach ($courses as $course) : ?>
                <?php
                $isFavourite = in_array($course['id'], (array) $favourites);
                $isPurchased = in_array($course['id'], (array) $orders);
                ?>
                <div class="bg-slate-100 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                    <a href="<?php echo $isPurchased ? "/courses/learning/{$course['id']}" : "/courses/show/{$course['id']}"; ?>">
                        <div class="relative aspect-video">
                            <img class="" width="100%" height="200px" src="http://localhost:8000/<?= htmlspecialchars($course['image']) ?>" alt="">
                        </div>
                        <div class="p-4">

                            <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-800"><?php echo $course['title']; ?></h3>
                            <?php if (!$isPurchased) : ?>
                                <button class="favorite-btn" data-course-id="<?= $course['id']; ?>" data-favorite="<?= $isFavourite ? 'true' : 'false'; ?>">
                                    <i class="fas fa-heart <?= $isFavourite ? 'text-red-500' : 'text-gray-400'; ?>"></i>
                                </button>
                            <?php endif; ?>
                            </div>
                            <div class="flex items-center ">
                                <?php if ($isPurchased) : ?>
                                    <span class="text-green-600 font-bold block mt-2">Bạn đã mua khóa học này!</span>
                                <?php else : ?>
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-gray-500 line-through">
                                            <?php echo number_format($course['price'], 0, ',', '.'); ?> VND
                                        </span>
                                        <span class="text-[#f05123] font-bold">
                                            <?php echo number_format($course['discount_price'], 0, ',', '.'); ?> VND
                                        </span>
                                    </div>
                                <?php endif; ?>
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
    <!-- Pro Courses Section -->
    <section>
    <?php if (!empty($favouriteList)) : ?>
    <h2 class="text-2xl font-bold mb-6 flex items-center">
            Khóa học Yêu thích
            <span class="ml-2 px-2 py-1 bg-blue-500 text-white text-xs rounded">MỚI</span>
        </h2>
        <div class="grid grid-cols-3 gap-6">
            <!-- HTML CSS Pro Course -->
            <?php foreach ($courses as $course) : ?>
                
                <?php
                $isPurchased = in_array($course['id'], (array) $orders);
                ?>
                <?php if (in_array($course['id'], $favouriteList)) : ?>
                <div class="bg-slate-100 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                    <a href="<?php echo $isPurchased ? "/courses/learning/{$course['id']}" : "/courses/show/{$course['id']}"; ?>">
                        <div class="relative aspect-video">
                            <img class="" width="100%" height="200px" src="http://localhost:8000/<?= htmlspecialchars($course['image']) ?>" alt="">
                        </div>
                        <div class="p-4">

                            <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-800"><?php echo $course['title']; ?></h3>
                            <?php if (!$isPurchased) : ?>
                                <button class="favorite-btn" data-course-id="<?= $course['id']; ?>" data-favorite="<?= $isFavourite ? 'true' : 'false'; ?>">
                                    <i class="fas fa-heart <?= $isFavourite ? 'text-red-500' : 'text-gray-400'; ?>"></i>
                                </button>
                            <?php endif; ?>
                            </div>
                            <div class="flex items-center ">
                                <?php if ($isPurchased) : ?>
                                    <span class="text-green-600 font-bold block mt-2">Bạn đã mua khóa học này!</span>
                                <?php else : ?>
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-gray-500 line-through">
                                            <?php echo number_format($course['price'], 0, ',', '.'); ?> VND
                                        </span>
                                        <span class="text-[#f05123] font-bold">
                                            <?php echo number_format($course['discount_price'], 0, ',', '.'); ?> VND
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="instructor flex items-center">
                                <img class="rounded-full" width="30px" height="30px" src="http://localhost:8000/<?= htmlspecialchars($course['instructor_avatar']) ?>" alt="avt">
                                <span class="mx-2 text-gray-600"> <?php echo $course['instructor_name']; ?> </span>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php endif; ?>

            <!-- JavaScript Pro Course -->

        </div>


        



    </section>




</main>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let favList = JSON.parse(localStorage.getItem('favourites')) || [];

        document.querySelectorAll('.favorite-btn').forEach(button => {
            let courseId = button.getAttribute('data-course-id');
            if (favList.includes(courseId)) {
                button.querySelector('i').classList.remove('text-gray-400');
                button.querySelector('i').classList.add('text-red-500');
                button.setAttribute('data-favorite', 'true');
            }
        });

        document.querySelectorAll('.favorite-btn').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                let courseId = this.getAttribute('data-course-id');
                let isFavorite = this.getAttribute('data-favorite') === 'true';
                let url = isFavorite ? '/favourite/remove' : '/favourite/add';

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            course_id: courseId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'added') {
                            this.querySelector('i').classList.remove('text-gray-400');
                            this.querySelector('i').classList.add('text-red-500');
                            this.setAttribute('data-favorite', 'true');
                            favList.push(courseId);
                        } else if (data.status === 'removed') {
                            this.querySelector('i').classList.remove('text-red-500');
                            this.querySelector('i').classList.add('text-gray-400');
                            this.setAttribute('data-favorite', 'false');
                            favList = favList.filter(id => id !== courseId);
                        }

                        // Cập nhật danh sách yêu thích trong Local Storage
                        localStorage.setItem('favourites', JSON.stringify(favList));
                    });
            });
        });
    });
</script>