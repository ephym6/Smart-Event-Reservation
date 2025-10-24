<?php
// public/register.php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/email.php';
require_once __DIR__ . '/../classes/Auth.php';

$auth = new Auth();
$errors = [];
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $phone = trim($_POST['phone'] ?? '');

    if (!$name || !$email || !$password) {
        $errors[] = 'Name, email and password are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }

    if (empty($errors)) {
        // Use Auth to register (assumes Auth->registerUser inserts into users)
        $res = $auth->registerUser($name, $email, $password, $phone);

        if (is_array($res) && !empty($res['success']) && $res['success'] === true) {
            // look up user id to set pending session
            $db = new Database();
            $conn = $db->connect();
            $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && !empty($row['user_id'])) {
                $user_id = intval($row['user_id']);
                $_SESSION['pending_user_id'] = $user_id;

                // Generate OTP, store hash & expiry, then send using sendOTPEmail()
                $otp = random_int(100000, 999999);
                $otpHash = password_hash((string)$otp, PASSWORD_BCRYPT);
                $expires = date('Y-m-d H:i:s', time() + 300); // 5 minutes

                $upd = $conn->prepare("UPDATE users SET otp_hash = :otp, otp_expires = :exp WHERE user_id = :uid");
                $upd->execute([':otp' => $otpHash, ':exp' => $expires, ':uid' => $user_id]);

                $mailSent = sendOTPEmail($email, $otp);

                // Always redirect to verify page. Show a friendly message there if mail was "sent" or not.
                $_SESSION['otp_sent_to'] = $email;
                if (!$mailSent) {
                    // Keep fallback message but still redirect
                    $_SESSION['otp_fallback_logged'] = true;
                }
                header('Location: verify.php');
                exit;
            } else {
                $errors[] = 'Registration succeeded but user not found. Try login.';
            }
        } else {
            $errors[] = is_array($res) && isset($res['message']) ? $res['message'] : 'Registration failed.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Register - Smart Event Reservation</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="login-page">
  <div class="container">
    <div class="auth-card">
      <h2>Create account</h2>

      <?php if ($errors): ?>
        <div class="msg-error"><?php foreach($errors as $e) echo '<div>' . htmlspecialchars($e) . '</div>'; ?></div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="msg-success"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>

      <form method="POST" novalidate>
        <input type="text" name="name" placeholder="Full name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="text" name="phone" placeholder="Phone number" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
        <button class="btn" type="submit">Register</button>
      </form>

      <p style="text-align:center; margin-top:12px;">
        Already have an account? <a href="login.php" style="color:#ffb703;">Login</a>
      </p>
    </div>
  </div>
</body>
</html>
