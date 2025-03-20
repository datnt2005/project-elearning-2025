<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F8 - Học Lập Trình Để Đi Làm</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://accounts.google.com/gsi/client" async defer></script>
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
            <h2 class="text-center mb-4">Đăng nhập</h2>

            <?php if (!empty($error)): ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi đăng nhập!',
                        text: "<?php echo htmlspecialchars($error); ?>",
                        confirmButtonText: 'Thử lại'
                    });
                </script>
            <?php endif; ?>

            <?php if (!empty($_SESSION['success_message'])): ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: "<?php echo htmlspecialchars($_SESSION['success_message']); ?>",
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = "/";
                    });
                </script>

                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <form method="POST" action="/login">
                <div class="form-floating mb-3">
                    <input type="email" id="email" name="email" class="form-control" required
                        placeholder="Nhập email của bạn" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                        title="Vui lòng nhập đúng định dạng email">
                    <label for="email">Email:</label>
                </div>

                <div class="form-floating mb-3 position-relative">
                    <input type="password" id="password" name="password" class="form-control" required
                        placeholder="Nhập mật khẩu của bạn" minlength="6">
                    <label for="password">Mật khẩu:</label>
                    <span class="toggle-password position-absolute top-50 end-0 translate-middle-y me-3"
                        onclick="togglePassword()" style="cursor: pointer;">
                        👁️
                    </span>
                </div>


                <button
                    class="w-100 py-2 bg-[#f05123] text-white font-medium rounded-lg hover:bg-[#d9431f] transition duration-300">Đăng
                    nhập</button>
            </form>

            <div class="text-center mt-3">
                <p>Bạn chưa có tài khoản? <a href="/register">Đăng ký ngay</a></p>
                <p><a href="/forgot_password">Quên mật khẩu?</a></p>
            </div>

            <a href='google/login' style="text-decoration: none">
                <div id="g_id_onload"
                    data-client_id="284544138294-js7c7nrmu14e6a34dlmer48r384vcvh2.apps.googleusercontent.com"
                    data-context="signin" data-ux_mode="redirect" data-login_uri="http://localhost:8000/google/login"
                    data-auto_prompt="false">
                </div>

                <div class="g_id_signin" data-type="standard" data-shape="pill" data-theme="outline"
                    data-text="signin_with" data-size="medium" data-logo_alignment="left" data-width="200px">
                </div>
            </a>
        </div>
    </div>


    <script>
        function togglePassword() {
            const passwordField = document.getElementById("password");
            passwordField.type = (passwordField.type === "password") ? "text" : "password";
        }
    </script>
</body>

</html>