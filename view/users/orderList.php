<main class="ml-24 pt-20 px-4">
    <h2 class="text-2xl font-bold mb-6">Khóa học đã mua</h2>

    <?php if (empty($orders)) : ?>
        <p class="text-gray-500">Bạn chưa mua khóa học nào.</p>
    <?php else : ?>
        <div class="grid grid-cols-3 gap-6">
            <!-- HTML CSS Pro Course -->
            <?php foreach ($orders as $order) : ?>
                <div class="bg-slate-100 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                <a href="/courses/learning/<?php echo $order['course_id']; ?>">
                        <div class="relative aspect-video">
                            <img class="" width="100%" height="200px" src="http://localhost:8000/<?= htmlspecialchars($order['course_image']) ?>" alt="">
                        </div>
                        <div class="p-4">

                            <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-800"><?php echo $order['title']; ?></h3>
                            </div>
                            <div class="flex items-center text-green-600">
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-gray-500 line-through mx-3">
                                        <?php echo number_format($order['total_price'], 0, ',', '.'); ?> VND
                                        </span>
                                        <?php echo $order['payment_status'] === 'completed' ? 'Đã thanh toán' : 'Chờ thanh toán'; ?>
                                    </div>
                            </div>
                            <div class="instructor flex items-center">
                                <img class="rounded-full" width="30px" height="30px" src="http://localhost:8000/uploads/<?= htmlspecialchars($order['instructor_image']) ?>" alt="avt">
                                <span class="mx-2 text-gray-600"> <?php echo $order['instructor_name']; ?> </span>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>

            <!-- JavaScript Pro Course -->

        </div>

      
    <?php endif; ?>
</main>