<?php
require_once __DIR__ . '/../config/Database.php';

class Auth {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function requireAuth($loginPage = 'login.php') {
        if (empty($_SESSION['user'])) {
            header('Location: ' . $loginPage);
            exit;
        }
    }

    public function currentUser() {
        return $_SESSION['user'] ?? null;
    }

    public function loginById($userId) {
        $db = new Database();
        $conn = $db->connect();
        $stmt = $conn->prepare('SELECT * FROM users WHERE user_id = :id LIMIT 1');
        $stmt->execute([':id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) $_SESSION['user'] = $user;
        return $user;
    }
}
