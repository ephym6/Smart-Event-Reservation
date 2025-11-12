<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Venue.php';

$auth = new Auth();
$auth->requireAuth('login.php');

$venueModel = new Venue();
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$venue = $venueModel->getById($id);
if (!$venue) {
    http_response_code(404);
    echo "<p>Venue not found. <a href=\"venues.php\">Back</a></p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($venue['venue_name']) ?> - Venue Details</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="venue-details-page">
<div class="container">
  <h2><?= htmlspecialchars($venue['venue_name']) ?></h2>
  <p><strong>Location:</strong> <?= htmlspecialchars($venue['location'] ?? '') ?></p>
  <p><strong>Capacity:</strong> <?= htmlspecialchars($venue['capacity'] ?? '') ?></p>
  <p><strong>Price per hour:</strong> Ksh.<?= number_format($venue['price_per_hour'], 2) ?></p>
  <p><?= nl2br(htmlspecialchars($venue['description'] ?? '')) ?></p>

  <p>
    <a class="btn" href="book.php?venue_id=<?= $venue['venue_id'] ?>">Book this venue</a>
    <a class="btn" href="venues.php">Back to list</a>
  </p>
</div>
</body>
</html>
