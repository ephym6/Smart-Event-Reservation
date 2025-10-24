<?php
require_once __DIR__ . '/../config/Database.php';

class Reservation {
    private $conn;
    private $table = 'reservations';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function create($user_id, $venue_id, $start_time, $end_time, $total_cost) {
        // Convert to proper DATETIME format
        $start = date('Y-m-d H:i:s', strtotime($start_time));
        $end = date('Y-m-d H:i:s', strtotime($end_time));

        $sql = "INSERT INTO {$this->table} 
                (user_id, venue_id, start_time, end_time, total_cost)
                VALUES (:uid, :vid, :start, :end, :cost)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':uid' => $user_id,
            ':vid' => $venue_id,
            ':start' => $start,
            ':end' => $end,
            ':cost' => $total_cost
        ]);
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT r.*, u.name AS user_name, v.venue_name 
                                    FROM reservations r
                                    JOIN users u ON r.user_id = u.user_id
                                    JOIN venues v ON r.venue_id = v.venue_id
                                    ORDER BY r.created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserReservations($user_id) {
        $stmt = $this->conn->prepare("SELECT r.*, v.venue_name 
                                      FROM reservations r 
                                      JOIN venues v ON r.venue_id = v.venue_id
                                      WHERE r.user_id = :uid
                                      ORDER BY r.start_time DESC");
        $stmt->execute([':uid' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
