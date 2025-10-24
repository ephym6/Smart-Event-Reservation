<?php
require_once __DIR__ . '/../classes/Auth.php';
$auth = new Auth();

$pendingId = $_SESSION['pending_user_id'] ?? null;
$errors = [];
$info = null;

if (!$pendingId && $auth->isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

// allow resend
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resend'])) {
    if ($pendingId) {
        $auth->resendOTP($pendingId);
        $info = 'OTP resent.';
    }
}

// verify posted OTP
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otp'])) {
    $otp = trim($_POST['otp']);
    if (!$otp) $errors[] = 'Enter the code.';
    if (empty($errors)) {
        $res = $auth->verifyOTP($pendingId, $otp);
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
  <meta charset="UTF-8">
  <title>Verify - Smart Event Reservation</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
  <h2>Enter verification code</h2>
  <?php if ($errors): ?>
    <div style="background: rgba(255,0,0,0.1); padding:10px; border-radius:8px; margin-bottom:15px;">
      <?php foreach($errors as $e) echo '<div>' . htmlspecialchars($e) . '</div>'; ?>
    </div>
  <?php endif; ?>
  <?php if ($info): ?>
    <div style="background: rgba(0,255,0,0.06); padding:8px; border-radius:8px; margin-bottom:10px;"><?= htmlspecialchars($info) ?></div>
  <?php endif; ?>
  <form method="POST">
    <input type="text" name="otp" placeholder="6-digit code">
    <button class="btn" type="submit">Verify</button>
  </form>
  <form method="POST" style="margin-top:10px;">
    <button class="btn" name="resend" type="submit">Resend Code</button>
  </form>

  <?php if (!empty($_SESSION['last_plain_otp'])): ?>
    <!-- developer helper: shows OTP when mail() not configured. Remove on production -->
    <div style="margin-top:12px; color:#ddd; font-size:13px;">
      Dev OTP (only visible locally): <?= htmlspecialchars($_SESSION['last_plain_otp']) ?>
    </div>
  <?php endif; ?>

  <p style="text-align:center; margin-top:12px;"><a href="login.php" style="color:#ffb703;">Back to login</a></p>
</div>
</body>
</html>
