<br>
<br>
<br>
<br>
<div class="container mx-auto px-4 py-6">
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-3">
            <div class="bg-white shadow-md rounded-lg">
                <ul class="p-4 space-y-3">
                    <li>
                        <a href="#" id="menuProfile" onclick="showSection('profileSection', 'menuProfile')" class="block p-2 font-semibold text-red-500">Hồ Sơ</a>
                    </li>
                    <li>
                        <a href="#" id="menuChangePassword" onclick="showSection('changePasswordSection', 'menuChangePassword')" class="block p-2 text-gray-600 hover:text-red-500">Đổi Mật Khẩu</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-span-9">
            <div class="bg-white shadow-md rounded-lg p-6">
                
                <div id="profileSection">
                    <h2 class="text-lg font-semibold text-gray-700">Hồ Sơ Của Tôi</h2>
                    <p class="text-sm text-gray-500">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>

                    <form action="/update-profile.php" method="POST" enctype="multipart/form-data" class="mt-4">
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-8 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Tên đăng nhập</label>
                                    <input type="text" class="w-full px-4 py-2 border rounded-md bg-gray-100 cursor-not-allowed" value="F8-Học Lập Trình Để Đi Làm" readonly>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Email</label>
                                    <input type="email" class="w-full px-4 py-2 border rounded-md bg-gray-100 cursor-not-allowed" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Tên</label>
                                    <input type="text" name="name" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-red-300" value="<?= htmlspecialchars($user['name']) ?>" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Số điện thoại</label>
                                    <input type="text" name="phone" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-red-300" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                                </div>

                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">Lưu</button>
                            </div>

                            <div class="col-span-4 flex flex-col items-center">
                                <img src="https://i.pravatar.cc/40" alt="Avatar" class="w-24 h-24 rounded-full border">
                                <input style="margin-left: 160px" type="file" name="avatar" class="mt-3 w-full text-sm">
                                <p class="text-xs text-gray-500">Dụng lượng file tối đa 1MB. Định dạng: JPEG, PNG</p>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="changePasswordSection" class="hidden">
                    <h3 class="text-lg font-semibold text-gray-700">Đổi Mật Khẩu</h3>
                    <form action="/update-password.php" method="POST" class="mt-4">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Mật khẩu hiện tại</label>
                                <input type="password" name="current_password" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-red-300" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600">Mật khẩu mới</label>
                                <input type="password" name="new_password" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-red-300" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600">Xác nhận mật khẩu mới</label>
                                <input type="password" name="confirm_password" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-red-300" required>
                            </div>

                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">Cập Nhật</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function showSection(sectionId, menuId) {
        document.getElementById('profileSection').classList.add('hidden');
        document.getElementById('changePasswordSection').classList.add('hidden');

        document.getElementById(sectionId).classList.remove('hidden');

        document.getElementById('menuProfile').classList.remove('font-semibold', 'text-red-500');
        document.getElementById('menuProfile').classList.add('text-gray-600');

        document.getElementById('menuChangePassword').classList.remove('font-semibold', 'text-red-500');
        document.getElementById('menuChangePassword').classList.add('text-gray-600');

        document.getElementById(menuId).classList.add('font-semibold', 'text-red-500');
        document.getElementById(menuId).classList.remove('text-gray-600');
    }
</script>
