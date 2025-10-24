<?php
require_once __DIR__ . '/../config/Database.php';

class User {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function register($name, $email, $password, $phone_number, $role = 'user') {
        $sql = "INSERT INTO {$this->table} (name, email, password_hash, phone_number, role)
                VALUES (:name, :email, :password, :phone, :role)";
        $stmt = $this->conn->prepare($sql);
        $hash = password_hash($password, PASSWORD_BCRYPT);
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hash,
            ':phone' => $phone_number,
            ':role' => $role
        ]);
    }

    public function allUsers() {
        $stmt = $this->conn->query("SELECT user_id, name, email, phone_number, role, is_verified FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
