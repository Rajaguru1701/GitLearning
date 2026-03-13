<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    if ($admin && ($admin['password'] === $password || password_verify($password, $admin['password']))) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id']        = $admin['id'];
        $_SESSION['admin_user']      = $admin['username'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
if (isset($_SESSION['admin_logged_in'])) {
    header('Location: dashboard.php'); exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Admin Login — MILIR BANGLES</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="assets/css/admin.css"/>
</head>
<body class="admin-login-body">
<div class="login-wrapper">
  <div class="login-box">
    <div class="login-logo">
      <img src="../assets/images/logo.jpeg" alt="MILIR BANGLES Logo"/>
    </div>
    <h2 class="login-title">Admin Panel</h2>
    <p class="login-sub">MILIR BANGLES — Management</p>

    <?php if ($error): ?>
    <div class="alert-error"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="form-group-admin">
        <label><i class="bi bi-person-fill"></i> Username</label>
        <input type="text" name="username" class="admin-input" placeholder="Enter username" required autocomplete="username"/>
      </div>
      <div class="form-group-admin">
        <label><i class="bi bi-lock-fill"></i> Password</label>
        <input type="password" name="password" class="admin-input" placeholder="Enter password" required autocomplete="current-password"/>
      </div>
      <button type="submit" class="btn-admin-login">
        <i class="bi bi-box-arrow-in-right me-2"></i>Login
      </button>
    </form>
   
  </div>
</div>
</body>
</html>
