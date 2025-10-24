<?php
class Database {
    private $host = '127.0.0.1';
    private $db_name = 'smart_event_reservation';
    private $username = 'root';
    private $password = 'Quodra_4s';
    public $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Database Connection Error: ' . $e->getMessage());
        }
        return $this->conn;
    }
}
