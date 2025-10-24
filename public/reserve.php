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

// Preselected venue via GET
$preselect_venue_id = isset($_GET['venue_id']) ? intval($_GET['venue_id']) : 0;

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
<body class="reserve-page">
<div class="container">
  <h2>Reserve a Venue</h2>

  <?php if ($errors): ?>
    <div class="msg-error"><?php foreach($errors as $e) echo '<div>' . htmlspecialchars($e) . '</div>'; ?></div>
  <?php endif; ?>
  <?php if ($success): ?>
    <div class="msg-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <form method="POST">
    <label>Venue</label>
    <select name="venue_id" required>
      <option value="">-- choose --</option>
      <?php 
        $selectedValue = $_POST['venue_id'] ?? $preselect_venue_id;
        foreach($venues as $v): 
          $sel = ($selectedValue && intval($selectedValue) === intval($v['venue_id'])) ? 'selected' : '';
      ?>
        <option value="<?= $v['venue_id'] ?>" <?= $sel ?>><?= htmlspecialchars($v['venue_name']) ?> (<?= htmlspecialchars($v['location']) ?>) - $<?= number_format($v['price_per_hour'],2) ?>/hr</option>
      <?php endforeach; ?>
    </select>

    <label>Start</label>
    <input type="datetime-local" name="start_time" value="<?= htmlspecialchars($_POST['start_time'] ?? '') ?>" required>

    <label>End</label>
    <input type="datetime-local" name="end_time" value="<?= htmlspecialchars($_POST['end_time'] ?? '') ?>" required>

    <button class="btn" type="submit">Reserve</button>
  </form>

  <p style="margin-top:12px;"><a href="dashboard.php" style="color:#ffb703;">Back to dashboard</a></p>
</div>
</body>
</html>
