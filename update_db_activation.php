<?php
// update_db_activation.php
require_once __DIR__ . '/app/Config/Database.php';

use App\Config\Database;

$db = new Database();
$conn = $db->getConnection();

try {
    // Add is_active column if not exists
    $stmt = $conn->query("SHOW COLUMNS FROM users LIKE 'is_active'");
    if ($stmt->rowCount() == 0) {
        $sql = "ALTER TABLE users ADD COLUMN is_active TINYINT(1) DEFAULT 0";
        $conn->exec($sql);
        echo "Added 'is_active' column.<br>";
        
        // Activate all current users so they aren't locked out
        $conn->exec("UPDATE users SET is_active = 1");
        echo "Activated existing users.<br>";
    }

    // Add activation_token column if not exists
    $stmt = $conn->query("SHOW COLUMNS FROM users LIKE 'activation_token'");
    if ($stmt->rowCount() == 0) {
        $sql = "ALTER TABLE users ADD COLUMN activation_token VARCHAR(64) NULL";
        $conn->exec($sql);
        echo "Added 'activation_token' column.<br>";
    }
    
    echo "Database schema updated successfully.";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
