<?php
require_once 'app/Config/Database.php';

use App\Config\Database;

$db = new Database();
$conn = $db->getConnection();

try {
    // Add reset columns to users table
    $sql = "ALTER TABLE users ADD COLUMN reset_token VARCHAR(64) DEFAULT NULL, ADD COLUMN reset_expires DATETIME DEFAULT NULL";
    
    $conn->exec($sql);
    echo "Columns 'reset_token' and 'reset_expires' added to 'users' table successfully.<br>";

} catch(PDOException $e) {
    echo "Error (might already exist): " . $e->getMessage();
}
