<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Reservation.php';

$auth = new Auth();
$auth->requireAuth('login.php');

$user = $auth->currentUser();
$res = new Reservation();
$reservations = $res->getUserReservations($user['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
  <h2>Welcome, <?= htmlspecialchars($user['name']) ?></h2>
  <a href="logout.php" class="btn">Logout</a>
  <h3>Your Reservations</h3>
  <table class="table">
    <tr><th>Venue</th><th>Start</th><th>End</th><th>Status</th><th>Total Cost</th></tr>
    <?php foreach($reservations as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['venue_name']) ?></td>
        <td><?= $r['start_time'] ?></td>
        <td><?= $r['end_time'] ?></td>
        <td><?= $r['status'] ?></td>
        <td>$<?= $r['total_cost'] ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
</body>
</html>
