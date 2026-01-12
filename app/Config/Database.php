<?php

namespace App\Config;

use PDO;
use PDOException;

class Database {
    // Configuration: Update these with your live credentials
    private $host = 'sdb-83.hosting.stackcp.net'; // Use 127.0.0.1 instead of localhost to force TCP
    private $db_name = 'greatdb-35303934d481'; 
    private $username = 'greatdb-35303934d481'; 
    private $password = 'z>~96da[hMxk'; 
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
