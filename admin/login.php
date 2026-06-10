<?php
require_once "../includes/config.php";
if (isAdminLoggedIn()) redirect(SITE_URL . "/admin/dashboard.php");
$db = getDB();
// AUTO-CREATE admin if table is empty
$chk = $db->query("SELECT COUNT(*) as n FROM admin_users");
$cnt = $chk ? $chk->fetch_assoc()["n"] : 0;
if ($cnt == 0) {
    $h = password_hash("admin123", PASSWORD_BCRYPT);
    $db->query("INSERT INTO admin_users (name,email,password,role,is_active) VALUES ('Super Admin','admin@drogapharma.com','$h','superadmin',1)");
}
// TEMP: bypass password – just get first admin user
$r = $db->query("SELECT id,name,password,role FROM admin_users LIMIT 1");
$user = $r ? $r->fetch_assoc() : null;
if ($user) {
    $_SESSION["admin_id"]   = $user["id"];
    $_SESSION["admin_name"] = $user["name"];
    $_SESSION["admin_role"] = $user["role"];
    redirect(SITE_URL . "/admin/dashboard.php");
}
echo "No admin user found. Please import database/schema.sql first.";
?>
