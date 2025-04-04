<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Lại Mật Khẩu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-4 shadow-lg" style="max-width: 400px; width: 100%;">
            <h2 class="text-center mb-4">Đặt Lại Mật Khẩu</h2>

            <?php if (!empty($_SESSION['error_message'])): ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: "<?php echo htmlspecialchars($_SESSION['error_message']); ?>",
                        confirmButtonText: 'Thử lại'
                    });
                </script>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['success_message'])): ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: "<?php echo htmlspecialchars($_SESSION['success_message']); ?>",
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = "/login";
                    });
                </script>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <form method="POST" action="/reset_password">
                <div class="form-floating mb-3">
                    <input type="email" id="email" name="email" class="form-control" required
                        placeholder="Nhập email của bạn" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                        title="Vui lòng nhập đúng định dạng email">
                    <label for="email">Email:</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" id="otp" name="otp" class="form-control" required placeholder="Nhập OTP">
                    <label for="otp">Mã OTP:</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" id="password" name="password" class="form-control" required
                        placeholder="Nhập mật khẩu mới" minlength="6">
                    <label for="password">Mật khẩu mới:</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" id="passwordConfirm" name="passwordConfirm" class="form-control" required
                        placeholder="Xác nhận mật khẩu" minlength="6">
                    <label for="passwordConfirm">Xác nhận mật khẩu:</label>
                </div>

                <button class="w-100 py-2 bg-[#f05123] text-white font-medium rounded-lg hover:bg-[#d9431f] transition duration-300">
                    Đặt lại mật khẩu
                </button>
            </form>

            <div class="text-center mt-3">
                <p><a href="/login">Quay lại Đăng nhập</a></p>
            </div>
        </div>
    </div>
</body>

</html>
