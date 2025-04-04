<?php
require_once "Database.php";

class AuthModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllUsers()
    {
        $query = "SELECT * FROM users";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id)
    {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($name, $email, $password, $image, $role, $status = "active")
    {
        $query = "INSERT INTO users (name, email, password, image, role, status) VALUES (:name, :email, :password, :image, :role, :status)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    public function editUser($id, $name, $email, $image, $role, $status)
    {
        $query = "UPDATE users SET name = ?, email = ?, image = ?, role = ?, status = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$name, $email, $image, $role, $status, $id]);
        return $stmt->rowCount();
    }

    public function deleteUser($id)
    {
        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function login($email, $password)
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function isEmailExists($email)
    {
        $query = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }

    public function register($name, $email, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        return $stmt->execute();
    }


    public function getUserByEmail($email)
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function updatePassword($email, $password)
    {
        // Mã hóa mật khẩu trước khi lưu vào cơ sở dữ liệu
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Cập nhật mật khẩu đã mã hóa trong cơ sở dữ liệu
        $query = "UPDATE users SET password = :password WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        return $stmt->execute();
    }


    public function getOTP($email)
    {
        $query = "SELECT otp FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function saveOTP($email, $otp)
    {
        $otpExpiration = date('Y-m-d H:i:s', strtotime('+10 minutes')); // OTP có hiệu lực 10 phút
        $sql = "UPDATE users SET otp = ?, otp_expired_at = ? WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$otp, $otpExpiration, $email]);
    }

    // Kiểm tra tính hợp lệ của OTP
    public function validateOTP($email, $otp)
    {
        $sql = "SELECT otp, otp_expired_at FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
        $result = $stmt->fetch();

        if ($result && $result['otp'] == $otp && strtotime($result['otp_expired_at']) > time()) {
            return true;
        }

        return false;
    }

    public function deleteOTP($email)
    {
        $sql = "UPDATE users SET otp = NULL, otp_expired_at = NULL WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
    }
}
