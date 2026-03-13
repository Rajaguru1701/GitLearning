<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/db.php';
session_start();

$db  = getDB();
$msg = ''; $msgType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current  = $_POST['current_pass'] ?? '';
    $new_pass = $_POST['new_pass']     ?? '';
    $admin_id = $_SESSION['admin_id'];

    $stmt = $db->prepare("SELECT password FROM admins WHERE id=?");
    $stmt->execute([$admin_id]);
    $admin = $stmt->fetch();

    if (!$admin || !password_verify($current, $admin['password'])) {
        $msg = 'Current password is incorrect.'; $msgType = 'error';
    } elseif (strlen($new_pass) < 6) {
        $msg = 'New password must be at least 6 characters.'; $msgType = 'error';
    } else {
        $hash = password_hash($new_pass, PASSWORD_DEFAULT);
        $db->prepare("UPDATE admins SET password=? WHERE id=?")->execute([$hash, $admin_id]);
        $msg = 'Password changed successfully!';
    }
}
header('Location: settings.php?msg=' . urlencode($msg) . '&type=' . $msgType);
exit;
?>
