<?php
// check_stripe_settings.php
require_once __DIR__ . '/app/Config/Database.php';
require_once __DIR__ . '/app/Core/Model.php';
require_once __DIR__ . '/app/Models/Setting.php';

use App\Models\Setting;

$settingModel = new Setting();
$stripeKey = $settingModel->get('stripe_secret_key');
$stripePub = $settingModel->get('stripe_publishable_key');

echo "<h2>Stripe Settings Debug</h2>";
echo "Secret Key: " . ($stripeKey ? "SET (Length: " . strlen($stripeKey) . ")" : "NOT SET") . "<br>";
echo "Publishable Key: " . ($stripePub ? "SET (Length: " . strlen($stripePub) . ")" : "NOT SET") . "<br>";
