<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="bg-white p-6 rounded-lg shadow-md max-w-sm w-full">
        <h2 class="text-xl font-semibold text-center mb-4">Đặt lại mật khẩu</h2>
        <form method="POST" action="/reset_password">
            <div class="mb-3">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="w-full px-3 py-2 border rounded-md focus:ring focus:ring-[#f05123]" required placeholder="Nhập email">
            </div>

            <div class="mb-3">
                <label for="otp" class="block text-sm font-medium text-gray-700">Nhập OTP</label>
                <input type="text" id="otp" name="otp" class="w-full px-3 py-2 border rounded-md focus:ring focus:ring-[#f05123]" required placeholder="Nhập OTP">
            </div>

            <div class="mb-3">
                <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu mới</label>
                <input type="password" id="password" name="password" class="w-full px-3 py-2 border rounded-md focus:ring focus:ring-[#f05123]" required placeholder="Nhập mật khẩu mới">
            </div>

            <div class="mb-3">
                <label for="passwordConfirm" class="block text-sm font-medium text-gray-700">Xác nhận mật khẩu</label>
                <input type="password" id="passwordConfirm" name="passwordConfirm" class="w-full px-3 py-2 border rounded-md focus:ring focus:ring-[#f05123]" required placeholder="Nhập lại mật khẩu">
            </div>

            <div class="mt-4">
                <button type="submit" class="w-full bg-[#f05123] text-white py-2 px-4 rounded-md font-medium hover:bg-[#d0411f]">Đặt lại mật khẩu</button>
            </div>
        </form>
    </div>
</div>
