<?php
require_once __DIR__ . '/../config/Database.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$email || !$password) {
        $errors[] = 'Email and password are required.';
    } else {
        try {
            $db = new Database();
            $conn = $db->connect();
            $stmt = $conn->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($password, $user['password_hash'])) {
                // logged in
                $_SESSION['user'] = $user;
                header('Location: venues.php');
                exit;
            } else {
                $errors[] = 'Invalid credentials.';
            }
        } catch (Exception $e) {
            $errors[] = 'Server error: ' . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login - Smart Event Reservation</title>
  <link rel="stylesheet" href="css/style.css">
  <style>body{display:flex;align-items:center;justify-content:center;height:100vh}.login-box{width:360px;background:#fff;padding:24px;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,.06)}</style>
</head>
<body>
  <div class="login-box">
    <h2>Login</h2>
    <?php if ($errors): ?><div class="msg-error"><?php foreach($errors as $e) echo '<div>'.htmlspecialchars($e).'</div>'; ?></div><?php endif; ?>
    <form method="POST">
      <label>Email</label>
      <input type="email" name="email" required>
      <label>Password</label>
      <input type="password" name="password" required>
      <div style="margin-top:12px">
        <button class="btn" type="submit">Login</button>
        <a style="margin-left:8px" href="venues.php">Browse</a>
      </div>
    </form>
  </div>
</body>
</html>
