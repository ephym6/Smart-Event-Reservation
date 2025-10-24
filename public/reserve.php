<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Venue.php';
require_once __DIR__ . '/../classes/Reservation.php';

$auth = new Auth();
$auth->requireAuth('login.php');

$venueModel = new Venue();
$venues = $venueModel->getAll();

$errors = [];
$success = null;
$user = $auth->currentUser();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $venue_id = intval($_POST['venue_id'] ?? 0);
    $start = trim($_POST['start_time'] ?? '');
    $end = trim($_POST['end_time'] ?? '');

    if (!$venue_id || !$start || !$end) $errors[] = 'All fields are required.';

    // basic validation: start < end
    if (strtotime($start) === false || strtotime($end) === false) $errors[] = 'Invalid dates.';
    if (empty($errors) && strtotime($start) >= strtotime($end)) $errors[] = 'Start must be before end.';

    // calculate hours and cost
    if (empty($errors)) {
        $venue = $venueModel->getById($venue_id);
        if (!$venue) $errors[] = 'Venue not found.';
        else {
            $hours = (strtotime($end) - strtotime($start)) / 3600;
            if ($hours <= 0) $errors[] = 'Duration must be positive.';
            else {
                $total = floatval($venue['price_per_hour']) * $hours;
                $resModel = new Reservation();
                $ok = $resModel->create($user['user_id'], $venue_id, $start, $end, $total);
                if ($ok) {
                    $success = 'Reservation created. Total cost: $' . number_format($total,2);
                } else {
                    $errors[] = 'Failed to create reservation.';
                }
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reserve Venue - Smart Event Reservation</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
  <h2>Reserve a Venue</h2>
  <?php if ($errors): ?>
    <div style="background: rgba(255,0,0,0.08); padding:10px; border-radius:8px; margin-bottom:10px;">
      <?php foreach($errors as $e) echo '<div>' . htmlspecialchars($e) . '</div>'; ?>
    </div>
  <?php endif; ?>
  <?php if ($success): ?>
    <div style="background: rgba(0,255,0,0.06); padding:10px; border-radius:8px; margin-bottom:10px;"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>
  <form method="POST">
    <label>Venue</label>
    <select name="venue_id">
      <option value="">-- choose --</option>
      <?php foreach($venues as $v): ?>
        <option value="<?= $v['venue_id'] ?>"><?= htmlspecialchars($v['venue_name']) ?> (<?= htmlspecialchars($v['location']) ?>) - $<?= $v['price_per_hour'] ?>/hr</option>
      <?php endforeach; ?>
    </select>

    <label>Start (YYYY-MM-DD HH:MM)</label>
    <input type="text" name="start_time" placeholder="2025-12-31 14:00" value="<?= htmlspecialchars($_POST['start_time'] ?? '') ?>">

    <label>End (YYYY-MM-DD HH:MM)</label>
    <input type="text" name="end_time" placeholder="2025-12-31 18:00" value="<?= htmlspecialchars($_POST['end_time'] ?? '') ?>">

    <button class="btn" type="submit">Reserve</button>
  </form>
  <p style="margin-top:12px;"><a href="dashboard.php" style="color:#ffb703;">Back to dashboard</a></p>
</div>
</body>
</html>
