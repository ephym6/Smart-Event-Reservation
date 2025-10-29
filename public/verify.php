<?php
// public/verify.php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../classes/Auth.php';
$auth = new Auth();

$errors = [];
$info = null;

// If already logged in, redirect
if ($auth->isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$pendingId = $_SESSION['pending_user_id'] ?? null;
$sentTo = $_SESSION['otp_sent_to'] ?? null;
$fallbackLogged = $_SESSION['otp_fallback_logged'] ?? false;

if ($sentTo) {
    // only show: "A verification code was sent to <email>."
    $info = 'A verification code was sent to ' . htmlspecialchars($sentTo) . '.';
    if ($fallbackLogged) $info .= ' (Email failed to send; OTP written to storage/otp-log.txt for dev testing.)';
}

// handle verification submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otp'])) {
    $entered = trim($_POST['otp'] ?? '');
    if (!$entered) {
        $errors[] = 'Enter the code.';
    } else {
        $res = $auth->verifyOTP($pendingId, $entered);
        if ($res['success']) {
            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = $res['message'] ?? 'Verification failed.';
        }
    }
}
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

      <form method="POST" action="resend.php" style="margin-top:10px;">
        <button class="btn" name="resend" type="submit">Resend Code</button>
      </form>

      <p style="margin-top:10px;"><a href="login.php" style="color:#ffb703;">Back to login</a></p>
    </div>
  </div>
</body>
</html>
