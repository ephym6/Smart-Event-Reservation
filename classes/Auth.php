<?php
// classes/Auth.php
require_once __DIR__ . '/../config/Database.php';

class Auth {
    private $conn;
    private $usersTable = 'users';
    private $otpTTL = 300; // seconds (5 minutes)

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    // Register user: returns ['success'=>bool, 'message'=>string]
    public function registerUser($name, $email, $password, $phone_number, $role = 'user') {
        // basic checks
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
   'success' => true,
   'otp' => $otp
];

        }
        // check existing
        $stmt = $this->conn->prepare("SELECT user_id FROM {$this->usersTable} WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) return ['success'=>false,'message'=>'Email already registered'];

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO {$this->usersTable} (name, email, password_hash, phone_number, role) 
                VALUES (:name, :email, :pass, :phone, :role)";
        $s = $this->conn->prepare($sql);
        $ok = $s->execute([
            ':name'=>$name, ':email'=>$email, ':pass'=>$hash, ':phone'=>$phone_number, ':role'=>$role
        ]);
        if ($ok) return ['success'=>true,'message'=>'Registered'];
        return ['success'=>false,'message'=>'DB error'];
    }

    // Login: checks credentials, then generates OTP and sets pending user in session
    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->usersTable} WHERE email = :email");
        $stmt->execute([':email'=>$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) return ['success'=>false,'message'=>'Invalid credentials'];

        if (!password_verify($password, $user['password_hash'])) {
            return ['success'=>false,'message'=>'Invalid credentials'];
        }

        // Credentials OK -> generate and send OTP
        $_SESSION['pending_user_id'] = $user['user_id'];
        $this->generateAndSendOTP($user['user_id'], $user['email']);
        return ['success'=>true,'message'=>'OTP sent'];
    }

    // generate OTP, store hash+expiry, send via mail()
    public function generateAndSendOTP($user_id, $email) {
        $otp = random_int(100000, 999999);
        $otpHash = password_hash((string)$otp, PASSWORD_BCRYPT);
        $expiresAt = date('Y-m-d H:i:s', time() + $this->otpTTL);

        // store in DB
        $stmt = $this->conn->prepare("UPDATE {$this->usersTable} SET otp_hash = :otp, otp_expires = :exp WHERE user_id = :uid");
        $stmt->execute([':otp'=>$otpHash, ':exp'=>$expiresAt, ':uid'=>$user_id]);

        // set session flash to show destination email (user-visible message)
        $_SESSION['otp_sent_to'] = $email;

        // Use PHP mail() to send the OTP (simple)
        $subject = "Your Smart Event Reservation Verification Code";
        $message = "Your verification code is: {$otp}\nThis code expires in " . intval($this->otpTTL/60) . " minutes.";
        $headers = "From: no-reply@smart-event.local\r\n";
        // Try mail (may land in spam on local dev)
        @mail($email, $subject, $message, $headers);

        // Do NOT return the OTP — we do not display it.
        return true;
    }

    // resend (re-generate)
    public function resendOTP($user_id) {
        $stmt = $this->conn->prepare("SELECT email FROM {$this->usersTable} WHERE user_id = :uid");
        $stmt->execute([':uid'=>$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) return ['success'=>false,'message'=>'User not found'];
        $this->generateAndSendOTP($user_id, $user['email']);
        return ['success'=>true,'message'=>'OTP resent'];
    }

    // verify otp string (plain). returns ['success'=>bool,'message'=>...]
    public function verifyOTP($user_id, $otp) {
        if (!$user_id) return ['success'=>false,'message'=>'No pending user'];

        $stmt = $this->conn->prepare("SELECT otp_hash, otp_expires FROM {$this->usersTable} WHERE user_id = :uid");
        $stmt->execute([':uid'=>$user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row || !$row['otp_hash']) return ['success'=>false,'message'=>'No OTP found. Request a new code.'];

        // check expiry
        if ($row['otp_expires'] && strtotime($row['otp_expires']) < time()) {
            return ['success'=>false,'message'=>'Code expired. Please resend.'];
        }

        if (!password_verify((string)$otp, $row['otp_hash'])) {
            return ['success'=>false,'message'=>'Invalid code.'];
        }

        // mark user verified and clear otp fields
        $upd = $this->conn->prepare("UPDATE {$this->usersTable} SET is_verified = 1, otp_hash = NULL, otp_expires = NULL WHERE user_id = :uid");
        $upd->execute([':uid'=>$user_id]);

        // set logged-in session
        $_SESSION['user_id'] = $user_id;
        unset($_SESSION['pending_user_id']);

        return ['success'=>true,'message'=>'Verified'];
    }

    // simple helpers
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
            $stmt = $this->conn->prepare("SELECT * FROM {$this->usersTable} WHERE user_id = :uid");
            $stmt->execute([':uid'=>$_SESSION['user_id']]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }

    // logout
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_unset();
        session_destroy();
    }
}
