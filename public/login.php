<?php
require_once __DIR__ . '/../classes/Auth.php';
$auth = new Auth();

if ($auth->isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) $errors[] = 'All fields required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format.';

    if (empty($errors)) {
        $res = $auth->login($email, $password);
        if ($res['success']) {
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
  <script src="js/validation.js"></script>
</head>
<body>
<div class="container">
  <h2>Login</h2>
  <?php if ($errors): ?>
    <div style="background: rgba(255,0,0,0.1); padding:10px; border-radius:8px; margin-bottom:15px;">
      <?php foreach($errors as $e) echo '<div>' . htmlspecialchars($e) . '</div>'; ?>
    </div>
  <?php endif; ?>
  <form method="POST" onsubmit="return validateForm();" novalidate>
    <input type="email" id="email" name="email" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    <input type="password" id="password" name="password" placeholder="Password">
    <button class="btn" type="submit">Send OTP & Login</button>
  </form>
  <p style="text-align:center; margin-top:12px;">No account? <a href="register.php" style="color:#ffb703;">Register</a></p>
</div>
</body>
</html>
