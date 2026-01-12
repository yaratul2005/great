<?php
require_once 'app/Config/Database.php';

use App\Config\Database;

$db = new Database();
$conn = $db->getConnection();

try {
    // Create product_images table
    $sql = "CREATE TABLE IF NOT EXISTS product_images (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        image_path VARCHAR(255) NOT NULL,
        is_primary TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )";
    
    $conn->exec($sql);
    echo "Table 'product_images' created successfully.<br>";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
