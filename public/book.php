<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Venue.php';

$auth = new Auth();
$auth->requireAuth('login.php');

$venueModel = new Venue();
$venues = $venueModel->getAll();

$preselect = isset($_GET['venue_id']) ? intval($_GET['venue_id']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Venue - Smart Event Reservation</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="book-page">
<div class="container">
  <h2>Book a Venue</h2>

  <form method="POST" action="reserve.php">
    <label>Venue</label>
    <select name="venue_id" required>
      <option value="">-- choose --</option>
      <?php foreach($venues as $v):
        $sel = ($preselect && intval($preselect) === intval($v['venue_id'])) ? 'selected' : '';
      ?>
        <option value="<?= $v['venue_id'] ?>" <?= $sel ?>><?= htmlspecialchars($v['venue_name']) ?> - Ksh.<?= number_format($v['price_per_hour'],2) ?>/hr</option>
      <?php endforeach; ?>
    </select>

    <label>Start</label>
    <input type="datetime-local" name="start_time" min="<?= date('Y-m-d\TH:i') ?>" required>

    <label>End</label>
    <input type="datetime-local" name="end_time" min="<?= date('Y-m-d\TH:i') ?>" required>

    <button class="btn" type="submit">Reserve</button>
  </form>

  <p style="margin-top:12px;"><a href="venues.php" style="color:#ffb703;">Back to venues</a></p>
</div>
</body>
</html>
