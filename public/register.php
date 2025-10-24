<?php
require_once __DIR__ . '/../classes/Auth.php';
$auth = new Auth();

// If already logged in, redirect to dashboard
if ($auth->isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if (!$name || !$email || !$phone || !$password || !$password2) $errors[] = 'All fields are required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email.';
    if ($password !== $password2) $errors[] = 'Passwords do not match.';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';

    if (empty($errors)) {
        $res = $auth->registerUser($name, $email, $password, $phone, 'user');
        if ($res['success']) {
            // Set pending user id in session (Auth already stored OTP) and go to verify page
            $_SESSION = $_SESSION ?? [];
            $_SESSION['pending_user_id'] = $res['user_id'];
            header('Location: verify.php');
            exit;
        } else {
            $errors[] = $res['message'] ?? 'Registration error.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Smart Event Reservation</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="js/validation.js"></script>
</head>
<body>
<div class="container">
  <h2>Create an account</h2>
  <?php if ($errors): ?>
    <div style="background: rgba(255,0,0,0.1); padding:10px; border-radius:8px; margin-bottom:15px;">
      <?php foreach($errors as $e) echo '<div>' . htmlspecialchars($e) . '</div>'; ?>
    </div>
  <?php endif; ?>
  <form method="POST" onsubmit="return validateForm();" novalidate>
    <input type="text" id="name" name="name" placeholder="Full name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
    <input type="email" id="email" name="email" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    <input type="text" id="phone" name="phone" placeholder="Phone number" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
    <input type="password" id="password" name="password" placeholder="Password">
    <input type="password" id="password2" name="password2" placeholder="Confirm password">
    <button class="btn" type="submit">Register</button>
  </form>
  <p style="text-align:center; margin-top:12px;">Already have an account? <a href="login.php" style="color:#ffb703;">Login</a></p>
</div>
</body>
</html>
