<?php
require_once __DIR__ . '/../classes/User.php';
$user = new User();
$users = $user->allUsers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Users</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
  <h2>Registered Users</h2>
  <table class="table">
    <tr><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Verified</th></tr>
    <?php foreach($users as $u): ?>
      <tr>
        <td><?= htmlspecialchars($u['name']) ?></td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td><?= htmlspecialchars($u['phone_number']) ?></td>
        <td><?= $u['role'] ?></td>
        <td><?= $u['is_verified'] ? 'Yes' : 'No' ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
</body>
</html>
