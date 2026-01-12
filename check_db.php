<?php
// check_db.php
require_once __DIR__ . '/app/Config/Database.php';

use App\Config\Database;

$db = new Database();
$conn = $db->getConnection();

try {
    echo "<h2>Table: products</h2>";
    $stmt = $conn->query("SHOW COLUMNS FROM products");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($columns);
    echo "</pre>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
