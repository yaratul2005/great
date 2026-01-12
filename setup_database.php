<?php
// setup_database.php
// automates the creation of the database and tables

$host = 'localhost';
$username = 'root';
$password = ''; // Default XAMPP password
$dbname = 'greatbd'; // The name you set in Config

try {
    // 1. Connect without DB name to create it
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to MySQL server successfully...<br>";

    // 2. Create Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database '$dbname' created or checks out...<br>";

    // 3. Connect to the new DB
    $pdo->exec("USE `$dbname`");

    // 4. Read Import SQL
    $sqlFile = __DIR__ . '/database.sql';
    if (!file_exists($sqlFile)) {
        die("Error: database.sql not found in " . __DIR__);
    }
    
    $sql = file_get_contents($sqlFile);
    
    // 5. Execute SQL (Split by ; to handle multiple statements if PDO doesn't support batch well, 
    // but usually PDO::exec handles multiple statements if emulation is on. 
    // For safety, we'll try direct execution first).
    
    $pdo->exec($sql);
    echo "Tables imported successfully from database.sql...<br>";
    
    echo "<h2 style='color:green'>Setup Complete!</h2>";
    echo "<p>You can now <a href='/great/'>Go to Homepage</a></p>";
    
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
