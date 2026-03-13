<?php
// Admin shared header — included by all admin pages
// $pageTitle must be set before including this
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title><?= htmlspecialchars($pageTitle ?? 'Admin') ?> — MILIR BANGLES</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="assets/css/admin.css"/>
</head>
<body class="admin-body">

<!-- Sidebar -->
<div class="admin-sidebar" id="adminSidebar">
  <div class="sidebar-brand">
    <img src="../assets/images/logo.jpeg" alt="Logo" class="sidebar-logo"/>
    <div>
      <div class="sidebar-brand-name">MILIR BANGLES</div>
      <div class="sidebar-brand-sub">Admin Panel</div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <a href="dashboard.php" class="sidebar-link <?= $currentPage==='dashboard.php'?'active':'' ?>">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    <a href="categories.php" class="sidebar-link <?= $currentPage==='categories.php'?'active':'' ?>">
      <i class="bi bi-grid-3x3-gap"></i> Categories
    </a>
    <a href="products.php" class="sidebar-link <?= $currentPage==='products.php'?'active':'' ?>">
      <i class="bi bi-box-seam"></i> Products
    </a>
    <a href="posters.php" class="sidebar-link <?= $currentPage==='posters.php'?'active':'' ?>">
      <i class="bi bi-megaphone"></i> Posters
    </a>
    <a href="settings.php" class="sidebar-link <?= $currentPage==='settings.php'?'active':'' ?>">
      <i class="bi bi-gear"></i> Settings
    </a>
    <div class="sidebar-divider"></div>
    <a href="../index.php" class="sidebar-link" target="_blank">
      <i class="bi bi-globe"></i> View Website
    </a>
    <a href="logout.php" class="sidebar-link sidebar-logout">
      <i class="bi bi-box-arrow-right"></i> Logout
    </a>
  </nav>
</div>
<!-- Sidebar Backdrop for mobile -->
<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

<!-- Top Bar -->
<div class="admin-main" id="adminMain">
  <div class="admin-topbar">
    <button class="topbar-toggle" id="sidebarToggle" onclick="toggleSidebar()">
      <i class="bi bi-list"></i>
    </button>
    <div class="topbar-title"><?= htmlspecialchars($pageTitle ?? '') ?></div>
    <div class="topbar-user">
      <i class="bi bi-person-circle me-1"></i>
      <?= htmlspecialchars($_SESSION['admin_user'] ?? 'Admin') ?>
    </div>
  </div>
  <div class="admin-content">
