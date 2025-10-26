<?php
require_once __DIR__ . '/../classes/Venue.php';
$venue = new Venue();
$venues = $venue->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Available Venues - Smart Event Reservation</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="venues-page">
  <div class="container">
    <h2>Available Venues</h2>
    <table class="table" style="margin-top:12px;">
      <tr><th>Venue</th><th>Description</th><th>Location</th><th>Capacity</th><th>Price/hr</th><th></th></tr>
      <?php foreach($venues as $v): ?>
        <tr>
          <td><?= htmlspecialchars($v['venue_name']) ?></td>
          <td><?= htmlspecialchars($v['description']) ?></td>
          <td><?= htmlspecialchars($v['location']) ?></td>
          <td><?= intval($v['capacity']) ?></td>
          <td>Ksh.<?= number_format($v['price_per_hour'],2) ?></td>
          <td><a class="btn" href="reserve.php?venue_id=<?= $v['venue_id'] ?>">Book</a></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>
</body>
</html>
