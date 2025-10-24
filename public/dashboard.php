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
<body class="dashboard-page">
<div class="container">
  <h2>Welcome, <?= htmlspecialchars($user['name']) ?></h2>
  <div style="display:flex; gap:10px; justify-content:center; margin-bottom:18px;">
    <a href="reserve.php" class="btn" style="width:auto;">Reserve a Venue</a>
    <a href="venues.php" class="btn" style="width:auto;">Browse Venues</a>
    <a href="logout.php" class="btn" style="width:auto;">Logout</a>
  </div>

  <h3>Your Reservations</h3>
  <table class="table">
    <tr><th>Venue</th><th>Start</th><th>End</th><th>Status</th><th>Total Cost</th></tr>
    <?php if (empty($reservations)): ?>
      <tr><td colspan="5">You have no reservations yet.</td></tr>
    <?php else: ?>
      <?php foreach($reservations as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['venue_name']) ?></td>
          <td><?= htmlspecialchars($r['start_time']) ?></td>
          <td><?= htmlspecialchars($r['end_time']) ?></td>
          <td><?= htmlspecialchars($r['status']) ?></td>
          <td>$<?= number_format($r['total_cost'], 2) ?></td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </table>
</div>
</body>
</html>
