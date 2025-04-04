<script src="https://accounts.google.com/gsi/client" async defer></script>
<style>
    body {
        background-color: #f8f9fa;
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        margin-left: 35px;

    }

    .login-container {
        background-color: #fff;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        width: 100%;
        text-align: center;
        margin: auto auto;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .login-container h2 {
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

    .form-group .toggle-password {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 16px;
    }

    .btn-login {
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

    .btn-login:hover {
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

    .google-login {
        display: block;
        margin: 16px auto 0;
        text-align: center;
    }
</style>
</head>

<body  class="bg-light">
    <div class="login-container">
        <h2>Đăng nhập</h2>

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
            <div class="form-group">
                <input type="email" id="email" name="email" required placeholder=" "
                    pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                    title="Vui lòng nhập đúng định dạng email">
                <label for="email">Email:</label>
            </div>

            <div class="form-group">
                <input type="password" id="password" name="password" required placeholder=" " minlength="6">
                <label for="password">Mật khẩu:</label>
                <span class="toggle-password" onclick="togglePassword()">👁️</span>
            </div>

            <button class="btn-login">Đăng nhập</button>
        </form>

        <div class="text-center">
            <p>Bạn chưa có tài khoản? <a href="/register">Đăng ký ngay</a></p>
            <p><a href="/forgot_password">Quên mật khẩu?</a></p>
        </div>

        <a href="google/login" class="google-login" style="text-decoration: none;">
            <div id="g_id_onload"
                data-client_id="284544138294-js7c7nrmu14e6a34dlmer48r384vcvh2.apps.googleusercontent.com"
                data-context="signin" data-ux_mode="redirect" data-login_uri="http://localhost:8000/google/login"
                data-auto_prompt="false">
            </div>
            <div class="g_id_signin" data-type="standard" data-shape="pill" data-theme="outline" data-text="signin_with"
                data-size="medium" data-logo_alignment="left" data-width="200px">
            </div>
        </a>
    </div>


    <script>
        function togglePassword() {
            const passwordField = document.getElementById("password");
            passwordField.type = (passwordField.type === "password") ? "text" : "password";
        }
    </script>
</body>

</html>