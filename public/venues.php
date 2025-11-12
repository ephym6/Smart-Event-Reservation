<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Venue.php';

$auth = new Auth();
$auth->requireAuth('login.php');

$venueModel = new Venue();
$venues = $venueModel->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Venues - Smart Event Reservation</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="venues-page">
<div class="container">
  <h2>Available Venues</h2>

  <?php if (empty($venues)): ?>
    <p>No venues available.</p>
  <?php else: ?>
    <div class="venue-grid">
      <?php foreach($venues as $v): ?>
        <div class="venue-card">
          <h3><?= htmlspecialchars($v['venue_name']) ?></h3>
          <p><?= htmlspecialchars($v['location'] ?? '') ?></p>
          <p>Capacity: <?= htmlspecialchars($v['capacity'] ?? '') ?></p>
          <p>Price: Ksh.<?= number_format($v['price_per_hour'], 2) ?>/hr</p>
          <p>
            <a class="btn" href="venue.php?id=<?= $v['venue_id'] ?>">Details</a>
            <a class="btn" href="book.php?venue_id=<?= $v['venue_id'] ?>">Book</a>
          </p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <p style="margin-top:12px;"><a href="dashboard.php" style="color:#ffb703;">Back to dashboard</a></p>
</div>
</body>
</html>
