<?php
// check_settings.php
require_once __DIR__ . '/app/Config/Database.php';
require_once __DIR__ . '/app/Core/Model.php';
require_once __DIR__ . '/app/Models/Setting.php';

use App\Models\Setting;

$settingModel = new Setting();
$apiKey = $settingModel->get('helio_api_key');
$publicKey = $settingModel->get('helio_public_key');
$currencyId = $settingModel->get('helio_currency_id');

echo "<h2>Settings Debug</h2>";
echo "API Key: " . ($apiKey ? "SET (Length: " . strlen($apiKey) . ")" : "NOT SET") . "<br>";
echo "Public Key: " . ($publicKey ? "SET (Length: " . strlen($publicKey) . ")" : "NOT SET") . "<br>";
echo "Currency ID: " . ($currencyId ? "SET (Length: " . strlen($currencyId) . ")" : "NOT SET") . "<br>";

echo "<hr>";
echo "Raw Table Data:<br>";
$db = new \App\Config\Database();
$conn = $db->getConnection();
$stmt = $conn->query("SELECT * FROM settings");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<pre>";
print_r($rows);
echo "</pre>";
