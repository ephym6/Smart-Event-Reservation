<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Auth.php';

$auth = new Auth();
$errors = [];
$info = null;

$pendingId = $_SESSION['pending_user_id'] ?? null;
$sentTo = $_SESSION['otp_sent_to'] ?? null;
$fallbackLogged = $_SESSION['otp_fallback_logged'] ?? false;

// If already logged in, go to dashboard
if ($auth->isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

// Info about OTP status
if ($sentTo) {
    $info = 'A verification code was sent to ' . htmlspecialchars($sentTo) . '.';
    if ($fallbackLogged) $info .= ' (Email failed to send; OTP written to storage/otp-log.txt for dev testing.)';
    // don't clear here; let user see it
}

// Process verify submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otp'])) {
    $entered = trim($_POST['otp'] ?? '');
    if (!$entered) $errors[] = 'Enter the code.';
    if (empty($errors)) {
        // If Auth has verifyOTP, use it
        if (method_exists($auth, 'verifyOTP')) {
            $res = $auth->verifyOTP($pendingId, $entered);
            if ($res['success']) {
                header('Location: dashboard.php');
                exit;
            } else {
                $errors[] = $res['message'] ?? 'Verification failed.';
            }
        } else {
            // manual verify: compare to stored otp_hash in DB
            $db = new Database();
            $conn = $db->connect();
            $stmt = $conn->prepare("SELECT otp_hash, otp_expires FROM users WHERE user_id = :uid LIMIT 1");
            $stmt->execute([':uid' => $pendingId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row || !$row['otp_hash']) {
                $errors[] = 'No OTP found. Request a new code.';
            } else {
                if ($row['otp_expires'] && strtotime($row['otp_expires']) < time()) {
                    $errors[] = 'Code expired. Please request a new code.';
                } elseif (!password_verify((string)$entered, $row['otp_hash'])) {
                    $errors[] = 'Invalid code.';
                } else {
                    // mark verified
                    $upd = $conn->prepare("UPDATE users SET is_verified = 1, otp_hash = NULL, otp_expires = NULL WHERE user_id = :uid");
                    $upd->execute([':uid' => $pendingId]);
                    // log user in
                    $_SESSION['user_id'] = $pendingId;
                    unset($_SESSION['pending_user_id'], $_SESSION['otp_sent_to'], $_SESSION['otp_fallback_logged']);
                    header('Location: dashboard.php');
                    exit;
                }
            }
        }
    }
}

// Show fallback note: if email failed, developer can find OTP in storage/otp-log.txt
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Verify - Smart Event Reservation</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="login-page">
  <div class="container">
    <div class="auth-card">
      <h2>Enter verification code</h2>

      <?php if ($info): ?>
        <div class="msg-success"><?= htmlspecialchars($info) ?></div>
      <?php endif; ?>

      <?php if ($errors): ?>
        <div class="msg-error"><?php foreach($errors as $e) echo '<div>' . htmlspecialchars($e) . '</div>'; ?></div>
      <?php endif; ?>

      <form method="POST">
        <input type="text" name="otp" placeholder="6-digit code" required>
        <button class="btn" type="submit">Verify</button>
      </form>

      <form method="POST" style="margin-top:10px;">
        <button class="btn" name="resend" type="submit" formaction="resend.php">Resend Code</button>
      </form>

      <p style="margin-top:10px;"><a href="login.php" style="color:#ffb703;">Back to login</a></p>
    </div>
  </div>
</body>
</html>
