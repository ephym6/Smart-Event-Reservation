<?php
require_once __DIR__ . '/../classes/Venue.php';
$venue = new Venue();
$venues = $venue->getAll();

// exact image mapping
$imageMap = [
  'Grand Ballroom'              => '../assets/grand-ballroom.jpg',
  'Garden Terrace'              => '../assets/garden-terrace.jpg',
  'Executive Hall'              => '../assets/executive-hall.jpg',
  'Sunset Lounge'               => '../assets/sunset-lounge.jpg',
  'Royal Conference Center'     => '../assets/conference-centre.jpg',
  'Skyline Rooftop'             => '../assets/rooftop.jpg',
  'Lakeview Pavilion'           => '../assets/lakeview-pav.jpg',
  'Tech Hub Hall'               => '../assets/tech-hub.jpg',
  'Cultural Dome'               => '../assets/cultural-dome.jpg',
  'Riverside Swimming Complex'  => '../assets/riverside-complex.jpg',
  'Artisan Gallery & Hall'      => '../assets/artisan-gallery.jpg',
  'Innovation Lab Auditorium'   => '../assets/innovation-lab.jpg',
  'Heritage Open-Air Arena'     => '../assets/heritage-arena.jpg',
  'Intimate Studio Room'        => '../assets/intimate-studio.jpg'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Available Venues - Smart Event Reservation</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f7f7f7;
      margin: 0;
      padding: 0;
    }
    .container { width: 90%; margin: 40px auto; }
    h2 { text-align: center; margin-bottom: 20px; }
    .back-btn {
      display: inline-block;
      margin-bottom: 20px;
      background-color: #333;
      color: white;
      text-decoration: none;
      padding: 10px 15px;
      border-radius: 6px;
    }
    .back-btn:hover { background-color: #555; }
    .venue-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }
    .venue-card {
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      transition: transform 0.2s;
    }
    .venue-card:hover { transform: scale(1.02); }
    .venue-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }
    .venue-info { padding: 15px; }
    .venue-info h3 {
      margin-top: 0;
      font-size: 18px;
      color: #222;
    }
    .venue-info p {
      margin: 5px 0;
      font-size: 14px;
      color: #555;
    }
    .book-btn {
      display: inline-block;
      background-color: #007bff;
      color: white;
      text-decoration: none;
      padding: 8px 12px;
      border-radius: 6px;
      margin-top: 10px;
    }
    .book-btn:hover { background-color: #0056b3; }
  </style>
</head>
<body>
  <div class="container">
    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
    <h2>Available Venues</h2>

    <?php if (!empty($venues)): ?>
      <div class="venue-grid">
        <?php foreach ($venues as $v): ?>
          <?php
            $venueName = $v['venue_name'];
            $imagePath = $imageMap[$venueName] ?? '../assets/default.jpg';
          ?>
          <div class="venue-card">
            <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($venueName) ?>">
            <div class="venue-info">
              <h3><?= htmlspecialchars($venueName) ?></h3>
              <p><?= htmlspecialchars($v['description']) ?></p>
              <p><strong>Location:</strong> <?= htmlspecialchars($v['location']) ?></p>
              <p><strong>Capacity:</strong> <?= intval($v['capacity']) ?></p>
              <p><strong>Price/hr:</strong> Ksh.<?= number_format($v['price_per_hour'], 2) ?></p>
              <a class="book-btn" href="reserve.php?venue_id=<?= $v['venue_id'] ?>">Book</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p style="text-align:center;">No venues available at the moment.</p>
    <?php endif; ?>
  </div>
</body>
</html>
