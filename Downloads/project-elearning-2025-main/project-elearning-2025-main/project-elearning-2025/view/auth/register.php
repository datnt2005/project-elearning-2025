<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-4 shadow-lg" style="max-width: 400px; width: 100%;">
            <h2 class="text-center mb-4">Đăng Ký</h2>
            <?php if (!empty($_SESSION['success_message1'])): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Đăng ký thành công!',
            text: "<?php echo htmlspecialchars($_SESSION['success_message1']); ?>",
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = "/login";
        });
    </script>
    <?php unset($_SESSION['success_message1']); ?>
<?php endif; ?>


            <?php if (!empty($error1)): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error1) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/register">
                <div class="form-floating mb-3">
                    <input type="text" id="name" name="name" class="form-control" required placeholder="Họ Và Tên"
                        value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                    <label for="name">Họ Và Tên</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="email" id="email" name="email" class="form-control" required placeholder="Email"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    <label for="email">Email</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" id="password" name="password" class="form-control" required
                        placeholder="Mật khẩu" minlength="6" autocomplete="off">
                    <label for="password">Mật khẩu</label>
                   
                </div>

                <div class="form-floating mb-3">
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required
                        placeholder="Xác nhận mật khẩu" minlength="6" autocomplete="off">
                    <label for="confirm_password">Xác nhận mật khẩu</label>
                </div>
                <button type="button" class="btn btn-sm btn-light position-absolute end-0 me-2 mt-2"
                        onclick="togglePassword('password'), togglePassword('confirm_password')">
                        <i class="fas fa-eye"></i>
                    </button>
<br>                    <br>
                <div class="d-grid">
                    <button type="submit" class="btn btn-danger">Đăng ký</button>
                </div>
            </form>

            <div class="text-center mt-3">
                <p>Đã có tài khoản? <a href="/login">Đăng nhập</a></p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            var input = document.getElementById(id);
            input.type = (input.type === "password") ? "text" : "password";
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>