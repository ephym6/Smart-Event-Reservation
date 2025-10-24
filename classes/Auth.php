<?php
// classes/Auth.php
require_once __DIR__ . '/../config/Database.php';

class Auth {
    private $conn;
    private $usersTable = 'users';
    private $otpTTL = 300; // seconds (5 minutes)

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Register user (wrapper; User class can call this too)
    public function registerUser($name, $email, $password, $phone_number, $role = 'user') {
        // check existing
        $stmt = $this->conn->prepare("SELECT user_id FROM {$this->usersTable} WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Email already registered.'];
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("INSERT INTO {$this->usersTable} (name, email, password_hash, phone_number, role, is_verified, created_at) VALUES (:name,:email,:pass,:phone,:role,0,NOW())");
        $ok = $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':pass' => $hash,
            ':phone' => $phone_number,
            ':role' => $role
        ]);

        if (!$ok) return ['success' => false, 'message' => 'Registration failed.'];

        $user_id = $this->conn->lastInsertId();
        // generate OTP and store
        $this->generateAndSendOTP($user_id, $email);
        return ['success' => true, 'user_id' => $user_id];
    }

    // Attempt login (password check). If OK, create login session but set 2FA pending and send OTP.
    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->usersTable} WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) return ['success' => false, 'message' => 'Invalid credentials.'];

        if (!password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Invalid credentials.'];
        }

        // Save pending login in session, generate OTP
        $_SESSION['pending_user_id'] = $user['user_id'];
        $_SESSION['pending_email'] = $user['email'];
        $this->generateAndSendOTP($user['user_id'], $user['email']);
        return ['success' => true, 'message' => 'OTP sent'];
    }

    // Generate OTP, store hashed in DB, set expiry, attempt to email via mail() (configure SMTP in php.ini)
    public function generateAndSendOTP($user_id, $email) {
        // generate numeric OTP (6 digits)
        $otp = random_int(100000, 999999);
        $otpHash = password_hash((string)$otp, PASSWORD_BCRYPT);
        $expiresAt = date('Y-m-d H:i:s', time() + $this->otpTTL);

        // store
        $stmt = $this->conn->prepare("UPDATE {$this->usersTable} SET otp_hash = :otp, otp_expires = :exp WHERE user_id = :uid");
        $stmt->execute([':otp' => $otpHash, ':exp' => $expiresAt, ':uid' => $user_id]);

        // send using mail() — replace with PHPMailer for real SMTP
        $subject = "Your SmartEvent OTP";
        $message = "Your verification code is: {$otp}\nIt will expire in " . ($this->otpTTL/60) . " minutes.";
        $headers = "From: no-reply@yourevent.local\r\n";

        // best-effort; store last_otp in session so developer can test locally without mail server
        $_SESSION['last_plain_otp'] = $otp;

        @mail($email, $subject, $message, $headers);
        // Always return true because OTP stored; mail() may not be configured.
        return true;
    }

    // Verify OTP (from user input)
    public function verifyOTP($user_id, $otpInput) {
        $stmt = $this->conn->prepare("SELECT otp_hash, otp_expires FROM {$this->usersTable} WHERE user_id = :uid");
        $stmt->execute([':uid' => $user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return ['success' => false, 'message' => 'User not found.'];

        if (!$row['otp_expires'] || strtotime($row['otp_expires']) < time()) {
            return ['success' => false, 'message' => 'OTP expired.'];
        }

        if (!password_verify((string)$otpInput, $row['otp_hash'])) {
            return ['success' => false, 'message' => 'Invalid OTP.'];
        }

        // mark verified and clear OTP
        $stmt = $this->conn->prepare("UPDATE {$this->usersTable} SET is_verified = 1, otp_hash = NULL, otp_expires = NULL WHERE user_id = :uid");
        $stmt->execute([':uid' => $user_id]);

        // create session (fully logged in)
        $user = $this->getUserById($user_id);
        session_regenerate_id(true);
        $_SESSION['user'] = $user;

        // clear pending fields
        unset($_SESSION['pending_user_id'], $_SESSION['pending_email'], $_SESSION['last_plain_otp']);
        return ['success' => true, 'user' => $user];
    }

    public function logout() {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    public function requireAuth($redirectTo = 'login.php') {
        if (!$this->isLoggedIn()) {
            header("Location: {$redirectTo}");
            exit;
        }
    }

    public function isLoggedIn() {
        return isset($_SESSION['user']) && !empty($_SESSION['user']);
    }

    public function currentUser() {
        return $_SESSION['user'] ?? null;
    }

    public function getUserById($user_id) {
        $stmt = $this->conn->prepare("SELECT user_id, name, email, phone_number, role, is_verified, created_at FROM {$this->usersTable} WHERE user_id = :uid");
        $stmt->execute([':uid' => $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // helper for resending OTP (if needed)
    public function resendOTP($user_id) {
        $user = $this->getUserById($user_id);
        if (!$user) return false;
        return $this->generateAndSendOTP($user_id, $user['email']);
    }
}
