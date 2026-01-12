<?php
// update_db_cms.php
require_once __DIR__ . '/app/Config/Database.php';

use App\Config\Database;

$db = new Database();
$conn = $db->getConnection();

try {
    // Create pages table
    $sql = "CREATE TABLE IF NOT EXISTS pages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        slug VARCHAR(255) UNIQUE NOT NULL,
        content LONGTEXT,
        meta_title VARCHAR(255),
        meta_description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $conn->exec($sql);
    echo "Table 'pages' created or already exists.<br>";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
