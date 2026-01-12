<?php
require_once 'app/Config/Database.php';

use App\Config\Database;

$db = new Database();
$conn = $db->getConnection();

try {
    // Add external_link column to products table
    $sql = "ALTER TABLE products ADD COLUMN external_link VARCHAR(500) DEFAULT NULL";
    
    $conn->exec($sql);
    echo "Column 'external_link' added to 'products' table successfully.<br>";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage(); // Will fail gracefully if exists usually, or I can check.
}
