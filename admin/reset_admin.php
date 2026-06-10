<?php
/**
 * One-time admin account setup / reset.
 * Visit this URL once, then DELETE this file.
 * URL: http://localhost/DROGA%20PHARMA%20PLC/droga-pharma/admin/reset_admin.php
 */
require_once '../includes/config.php';
$db = getDB();

$name     = 'Super Admin';
$email    = 'admin@drogapharma.com';
$password = 'Admin@1234';   // ← change this to whatever you want
$hash     = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

// Check if user exists
$check = $db->prepare("SELECT id FROM admin_users WHERE email = ?");
$check->bind_param('s', $email);
$check->execute();
$existing = $check->get_result()->fetch_assoc();

if ($existing) {
    // Update password
    $stmt = $db->prepare("UPDATE admin_users SET password = ?, name = ?, is_active = 1 WHERE email = ?");
    $stmt->bind_param('sss', $hash, $name, $email);
    $stmt->execute();
    echo "<h2 style='font-family:sans-serif;color:green'>✅ Admin password updated!</h2>";
} else {
    // Insert new admin
    $stmt = $db->prepare("INSERT INTO admin_users (name, email, password, role, is_active) VALUES (?, ?, ?, 'superadmin', 1)");
    $stmt->bind_param('sss', $name, $email, $hash);
    $stmt->execute();
    echo "<h2 style='font-family:sans-serif;color:green'>✅ Admin account created!</h2>";
}

echo "<p style='font-family:sans-serif'><strong>Email:</strong> $email<br>";
echo "<strong>Password:</strong> $password</p>";
echo "<p style='font-family:sans-serif;color:red'><strong>⚠️ Delete this file immediately after use!</strong></p>";
echo "<p style='font-family:sans-serif'><a href='login.php'>→ Go to Login</a></p>";
?>
