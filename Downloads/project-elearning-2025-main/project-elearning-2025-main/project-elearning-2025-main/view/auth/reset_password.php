<form method="POST" action="/reset_password">
    
    <div class="mb-3">
        <label for="email" class="form-label">email</label>
        <input type="email" id="email" name="email" class="form-control" required placeholder="Enter OTP">
    </div>

    <div class="mb-3">
        <label for="otp" class="form-label">Enter OTP:</label>
        <input type="text" id="otp" name="otp" class="form-control" required placeholder="Enter OTP">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">New Password:</label>
        <input type="password" id="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="passwordConfirm" class="form-label">Confirm Password:</label>
        <input type="password" id="passwordConfirm" name="passwordConfirm" class="form-control" required placeholder="Confirm your password">
    </div>
    <div class="d-grid">
        <button type="submit" class="btn btn-primary">Reset Password</button>
    </div>
</form>
