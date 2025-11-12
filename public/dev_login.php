<?php
// Dev-only: set a session user for quick UI testing and redirect to /venues.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/Database.php';

$user = null;
try {
    $db = new Database();
    $conn = $db->connect();
    $stmt = $conn->prepare('SELECT * FROM users WHERE user_id = :id LIMIT 1');
    $stmt->execute([':id' => 1]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // ignore DB errors; fallback to a minimal user
}

if (!$user) {
    $user = [
        'user_id' => 1,
        'name' => 'Dev User',
        'email' => 'dev@example.local'
    ];
}

$_SESSION['user'] = $user;
header('Location: venues.php');
exit;
