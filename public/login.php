<?php
// public/login.php
require_once __DIR__ . '/../classes/Auth.php';
$auth = new Auth();

// If already fully logged in, go to dashboard
if ($auth->isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$info = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $errors[] = 'All fields required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }

    if (empty($errors)) {
        $res = $auth->login($email, $password);
        if ($res['success']) {
            // show info on verify page
            header('Location: verify.php');
            exit;
        } else {
            $errors[] = $res['message'] ?? 'Login failed.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Smart Event Reservation</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="js/validation.js" defer></script>
</head>
<body class="login-page">
  <div class="container">
    <div class="auth-card">
      <h2>Welcome back</h2>

      <?php if ($errors): ?>
        <div class="msg-error">
          <?php foreach($errors as $e) echo '<div>' . htmlspecialchars($e) . '</div>'; ?>
        </div>
      <?php endif; ?>

      <?php if ($info): ?>
        <div class="msg-success"><?= htmlspecialchars($info) ?></div>
      <?php endif; ?>

      <form method="POST" onsubmit="return validateForm();" novalidate>
        <input type="email" id="email" name="email" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        <input type="password" id="password" name="password" placeholder="Password" required>
        <button class="btn" type="submit">Send OTP & Login</button>
      </form>

      <p style="text-align:center; margin-top:12px;">
        No account? <a href="register.php" style="color:#ffb703;">Register</a>
      </p>
    </div>
  </div>
</body>
</html>
