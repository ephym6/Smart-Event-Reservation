<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/email.php';

$pendingId = $_SESSION['pending_user_id'] ?? null;
$email = $_SESSION['otp_sent_to'] ?? null;

if (!$pendingId || !$email) {
    header('Location: register.php');
    exit;
}

// Generate new OTP
$otp = random_int(100000, 999999);
$otpHash = password_hash((string)$otp, PASSWORD_BCRYPT);
$expires = date('Y-m-d H:i:s', time() + 300);

// Update in database
$db = new Database();
$conn = $db->connect();
$upd = $conn->prepare("UPDATE users SET otp_hash = :otp, otp_expires = :exp WHERE user_id = :uid");
$upd->execute([':otp' => $otpHash, ':exp' => $expires, ':uid' => $pendingId]);

// Send email
$mailSent = sendOTPEmail($email, $otp);

// Set session variables
$_SESSION['otp_sent_to'] = $email;
$_SESSION['otp_fallback_logged'] = !$mailSent;

header('Location: verify.php');
exit;
?>