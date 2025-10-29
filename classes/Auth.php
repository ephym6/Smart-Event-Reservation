<?php
// classes/Auth.php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/email.php'; // uses sendOTPEmail()

class Auth {
    private $conn;
    private $usersTable = 'users';
    private $otpTTL = 300; // seconds (5 minutes)

    public function __construct() {
        $db = new Database();
        // Your Database.php provides connect()
        $this->conn = $db->connect();
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    /**
     * Register user
     * Returns ['success'=>bool, 'message'=>string, 'user_id'=>int?]
     */
    public function registerUser($name, $email, $password, $phone_number = null, $role = 'user') {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email'];
        }

        // check existing
        $stmt = $this->conn->prepare("SELECT user_id FROM {$this->usersTable} WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) return ['success'=>false,'message'=>'Email already registered'];

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO {$this->usersTable} (name, email, password_hash, phone_number, role, is_verified, created_at, updated_at)
                VALUES (:name, :email, :pass, :phone, :role, 0, NOW(), NOW())";
        $s = $this->conn->prepare($sql);
        $ok = $s->execute([
            ':name'=>$name, ':email'=>$email, ':pass'=>$hash, ':phone'=>$phone_number, ':role'=>$role
        ]);
        if ($ok) {
            $id = intval($this->conn->lastInsertId());
            return ['success'=>true,'message'=>'Registered','user_id'=>$id];
        }
        return ['success'=>false,'message'=>'DB error'];
    }

    /**
     * Login: validate credentials, generate & send OTP immediately.
     * Returns ['success'=>bool, 'message'=>string]
     */
    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->usersTable} WHERE email = :email LIMIT 1");
        $stmt->execute([':email'=>$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) return ['success'=>false,'message'=>'Invalid credentials'];

        if (!password_verify($password, $user['password_hash'])) {
            return ['success'=>false,'message'=>'Invalid credentials'];
        }

        // credentials ok -> generate and send OTP
        $_SESSION['pending_user_id'] = $user['user_id'];

        $sent = $this->generateAndSendOTP($user['user_id'], $user['email']);

        if ($sent) {
            return ['success'=>true,'message'=>'OTP sent'];
        } else {
            // still return success (OTP generated) but flag message about sending
            return ['success'=>true,'message'=>'OTP generated but failed to send email (check logs).'];
        }
    }

    /**
     * Generate OTP, store hashed OTP + expiry in DB, and send using sendOTPEmail()
     * Returns true if send succeeded, false otherwise.
     */
    public function generateAndSendOTP($user_id, $email) {
        // generate
        $otp = random_int(100000, 999999);
        $otpHash = password_hash((string)$otp, PASSWORD_BCRYPT);
        $expiresAt = date('Y-m-d H:i:s', time() + $this->otpTTL);

        // store
        $stmt = $this->conn->prepare("UPDATE {$this->usersTable} SET otp_hash = :otp, otp_expires = :exp, updated_at = NOW() WHERE user_id = :uid");
        $stmt->execute([':otp'=>$otpHash, ':exp'=>$expiresAt, ':uid'=>$user_id]);

        // session info for UX
        $_SESSION['otp_sent_to'] = $email;
        $_SESSION['otp_expires_at'] = $expiresAt;

        // try to send using PHPMailer wrapper in config/email.php
        $sent = false;
        try {
            $sent = sendOTPEmail($email, $otp);
        } catch (\Throwable $t) {
            $sent = false;
            error_log("sendOTPEmail exception: " . $t->getMessage());
        }

        // fallback: log OTP locally for dev if email fails
        if (!$sent) {
            $storage = __DIR__ . '/../storage';
            if (!is_dir($storage)) @mkdir($storage, 0755, true);
            $logfile = $storage . '/otp-log.txt';
            $line = date('Y-m-d H:i:s') . " | {$email} | OTP: {$otp} | user_id={$user_id}\n";
            @file_put_contents($logfile, $line, FILE_APPEND);
            $_SESSION['otp_fallback_logged'] = true;
        } else {
            $_SESSION['otp_fallback_logged'] = false;
        }

        return $sent;
    }

    /**
     * Resend OTP for given pending user id
     * Returns ['success'=>bool,'message'=>string]
     */
    public function resendOTP($user_id) {
        $stmt = $this->conn->prepare("SELECT email FROM {$this->usersTable} WHERE user_id = :uid LIMIT 1");
        $stmt->execute([':uid'=>$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) return ['success'=>false,'message'=>'User not found'];
        $sent = $this->generateAndSendOTP($user_id, $user['email']);
        if ($sent) return ['success'=>true,'message'=>'OTP resent'];
        return ['success'=>false,'message'=>'Failed to resend OTP'];
    }

    /**
     * Verify OTP for given pending user id
     * Returns ['success'=>bool,'message'=>string]
     */
    public function verifyOTP($user_id, $otp) {
        if (!$user_id) return ['success'=>false,'message'=>'No pending user'];

        $stmt = $this->conn->prepare("SELECT otp_hash, otp_expires FROM {$this->usersTable} WHERE user_id = :uid LIMIT 1");
        $stmt->execute([':uid'=>$user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row || empty($row['otp_hash'])) {
            return ['success'=>false,'message'=>'No OTP found. Request a new code.'];
        }

        // expiry
        if (!empty($row['otp_expires']) && strtotime($row['otp_expires']) < time()) {
            return ['success'=>false,'message'=>'Code expired. Please resend.'];
        }

        if (!password_verify((string)$otp, $row['otp_hash'])) {
            return ['success'=>false,'message'=>'Invalid code.'];
        }

        // mark verified & clear OTP
        $upd = $this->conn->prepare("UPDATE {$this->usersTable} SET is_verified = 1, otp_hash = NULL, otp_expires = NULL, updated_at = NOW() WHERE user_id = :uid");
        $upd->execute([':uid'=>$user_id]);

        // set session as logged in
        $_SESSION['user_id'] = $user_id;
        unset($_SESSION['pending_user_id'], $_SESSION['otp_sent_to'], $_SESSION['otp_expires_at'], $_SESSION['otp_fallback_logged']);

        return ['success'=>true,'message'=>'Verified'];
    }

    // helpers
    public function isLoggedIn() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return !empty($_SESSION['user_id']);
    }

    public function requireAuth($redirect = 'login.php') {
        if (!$this->isLoggedIn()) {
            header('Location: ' . $redirect);
            exit;
        }
    }

    public function currentUser() {
        if ($this->isLoggedIn()) {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->usersTable} WHERE user_id = :uid LIMIT 1");
            $stmt->execute([':uid'=>$_SESSION['user_id']]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_unset();
        session_destroy();
    }
}
