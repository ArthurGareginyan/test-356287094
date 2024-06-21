<?php

class Database
{
    private static $instance = null;
    private $conn;

    private $host = 'localhost';
    private $username = 'yourusername';
    private $password = 'yourpassword!';
    private $dbname = 'yourdatabase';

    private function __construct()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . htmlspecialchars($this->conn->connect_error));
        }
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    // Preventing cloning and unserialization
    private function __clone() {}
    private function __wakeup() {}
}

?>
