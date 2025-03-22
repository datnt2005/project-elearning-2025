<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="bg-white p-6 rounded-lg shadow-md max-w-sm w-full">
        <h2 class="text-xl font-semibold text-center mb-4">Quên mật khẩu</h2>
        <form method="POST" action="/forgot_password">
            <div class="mb-3">
                <label for="email" class="form-label block text-sm font-medium text-gray-700">Nhập email của bạn:</label>
                <input type="email" id="email" name="email" class="form-control w-full px-3 py-2 border rounded-md focus:ring focus:ring-[#f05123]" required placeholder="Nhập email đã đăng ký">
            </div>
            <div class="mt-4">
                <button type="submit" class="w-full bg-[#f05123] text-white py-2 px-4 rounded-md font-medium hover:bg-[#d0411f]">Gửi OTP</button>
            </div>
        </form>
    </div>
</div>
    