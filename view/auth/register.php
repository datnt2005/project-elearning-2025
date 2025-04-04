<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    body {
        background-color: #f8f9fa;
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
    }

    .register-container {
        background-color: #fff;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        width: 100%;
        margin: auto auto;
    }

    .register-container h2 {
        text-align: center;
        margin-bottom: 24px;
        font-size: 24px;
        font-weight: bold;
    }

    .form-group {
        position: relative;
        margin-bottom: 16px;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        box-sizing: border-box;
        outline: none;
        transition: border-color 0.3s;
    }

    .form-group input:focus {
        border-color: #f05123;
    }

    .form-group label {
        position: absolute;
        top: 50%;
        left: 10px;
        transform: translateY(-50%);
        font-size: 16px;
        color: #6c757d;
        transition: all 0.3s;
        pointer-events: none;
        background-color: #fff;
        padding: 0 4px;
    }

    .form-group input:focus+label,
    .form-group input:not(:placeholder-shown)+label {
        top: 0;
        font-size: 12px;
        color: #f05123;
    }

    .toggle-password {
        position: absolute;
        top: 20px;
        right: 10px;
        cursor: pointer;
        font-size: 14px;
        color: #6c757d;
        border: 1px solid #ced4da;
        padding: 2px 6px;
        border-radius: 4px;
        background-color: #f8f9fa;
    }

    .toggle-password:hover {
        background-color: #e9ecef;
    }

    .btn-register {
        width: 100%;
        padding: 10px;
        background-color: #f05123;
        color: #fff;
        font-size: 16px;
        font-weight: 500;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn-register:hover {
        background-color: #d9431f;
    }

    .text-center {
            text-align: center;
            margin-top: 16px;
        }
        .text-center p {
            margin: 8px 0;
            font-size: 14px;
        }
        .text-center a {
            color: #f05123;
            text-decoration: none;
        }
        .text-center a:hover {
            text-decoration: underline;
        }
</style>

<div class="register-container">
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
    <h2>Đăng ký</h2>

    <form method="POST" action="/register">
        <div class="form-group">
            <input type="text" id="name" name="name" required placeholder=" "
                value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
            <label for="name">Họ Và Tên</label>
        </div>

        <div class="form-group">
            <input type="email" id="email" name="email" required placeholder=" "
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            <label for="email">Email</label>
        </div>

        <div class="form-group">
            <input type="password" id="password" name="password" required placeholder=" " minlength="6"
                autocomplete="off">
            <label for="password">Mật khẩu</label>
        </div>

        <div class="form-group">
            <input type="password" id="confirm_password" name="confirm_password" required placeholder=" " minlength="6"
                autocomplete="off">
            <label for="confirm_password">Xác nhận mật khẩu</label>
            <button type="button" class="toggle-password"
                onclick="togglePassword('password'); togglePassword('confirm_password')">
                <i class="fas fa-eye"></i>
            </button>
        </div>

        <button type="submit" class="btn-register">Đăng ký</button>
        <div style="margin-top: 16px" class="text-center">
            <p>Bạn có tài khoản? <a href="/login">Đăng nhập ngay</a></p>
        </div>
    </form>
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