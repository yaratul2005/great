<?php
// create_admin.php
require_once __DIR__ . '/app/Config/Database.php';

use App\Config\Database;

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    
    if ($email) {
        $db = new Database();
        $conn = $db->getConnection();
        
        $stmt = $conn->prepare("UPDATE users SET role = 'admin' WHERE email = :email");
        if ($stmt->execute([':email' => $email])) {
            if ($stmt->rowCount() > 0) {
                $message = "<p style='color:green'>Success! User '$email' is now an Admin.</p>";
            } else {
                $message = "<p style='color:red'>User not found.</p>";
            }
        } else {
            $message = "<p style='color:red'>Database error.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Make Admin</title>
    <style>body { font-family: sans-serif; padding: 50px; text-align: center; }</style>
</head>
<body>
    <h1>Promote User to Admin</h1>
    <?= $message ?>
    <form method="POST">
        <label>Enter Email of Registered User:</label><br><br>
        <input type="email" name="email" required placeholder="user@example.com" style="padding: 10px; width: 300px;"><br><br>
        <button type="submit" style="padding: 10px 20px; cursor: pointer;">Make Admin</button>
    </form>
    <br>
    <a href="/great/">Go Home</a> | <a href="/great/login">Login</a> | <a href="/great/admin/dashboard">Go to Dashboard</a>
</body>
</html>
