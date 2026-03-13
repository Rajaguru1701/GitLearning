<?php
require_once __DIR__ . '/config/db.php';
$db = getDB();
$waNum    = getSetting('whatsapp_number') ?: '919994264756';
$shopName = getSetting('shop_name')       ?: 'MILIR BANGLES';

$categories = $db->query("SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id=c.id AND p.stock>0) as prod_count FROM categories c ORDER BY c.sort_order, c.name")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta name="description" content="Browse all men's wear categories at <?= htmlspecialchars($shopName) ?>. Shirts, T-Shirts, Trousers, Ethnic Wear & more."/>
<title>Categories | <?= htmlspecialchars($shopName) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="assets/css/style.css"/>
</head>
<body>

<nav class="navbar navbar-expand-lg" id="mainNavbar">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <img src="assets/images/logo.jpeg" alt="Logo" class="brand-logo"/>
      <div>
        <span class="brand-name"><?= htmlspecialchars($shopName) ?></span>
        <span class="brand-sub">மிளிர்</span>
      </div>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link active" href="categories.php">Categories</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item position-relative">
          <a class="nav-link" href="cart.php"><i class="bi bi-bag" style="font-size:1.1rem;"></i><span class="cart-badge" id="navCartCount" style="display:none;">0</span></a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- PAGE HERO -->
<div class="page-hero">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active">Categories</li>
      </ol>
    </nav>
    <h1 class="page-hero-title mt-2">All <span>Categories</span></h1>
  </div>
</div>

<!-- CATEGORIES GRID -->
<section class="section">
  <div class="container">
    <?php if (empty($categories)): ?>
    <div class="empty-state"><i class="bi bi-grid-3x3-gap"></i><p>No categories available yet. Check back soon!</p></div>
    <?php else: ?>
    <div class="row g-4">
      <?php foreach ($categories as $cat): ?>
      <div class="col-sm-6 col-lg-4 fade-up">
        <a href="products.php?category_id=<?= $cat['id'] ?>" class="cat-card">
          <div class="cat-card-img">
            <?php if ($cat['image']): ?>
            <img src="uploads/<?= htmlspecialchars($cat['image']) ?>" alt="<?= htmlspecialchars($cat['name']) ?>" loading="lazy"/>
            <?php else: ?>
            <div class="cat-placeholder">
              <i class="bi bi-handbag"></i>
              <span style="font-size:0.85rem;font-weight:700;letter-spacing:0.05em;"><?= htmlspecialchars($cat['name']) ?></span>
            </div>
            <?php endif; ?>
          </div>
          <div class="cat-card-body">
            <span class="cat-card-arrow"><i class="bi bi-arrow-right-circle-fill"></i></span>
            <div class="cat-card-name"><?= htmlspecialchars($cat['name']) ?></div>
            <div class="cat-card-count"><?= $cat['prod_count'] ?> Product<?= $cat['prod_count']!=1?'s':'' ?> Available</div>
          </div>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php require_once 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
