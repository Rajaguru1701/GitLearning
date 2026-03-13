<?php
require_once __DIR__ . '/config/db.php';
$db = getDB();
$waNum    = getSetting('whatsapp_number') ?: '919994264756';
$shopName = getSetting('shop_name')       ?: 'MILIR BANGLES';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>About Us | <?= htmlspecialchars($shopName) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="assets/css/style.css"/>
</head>
<body>

<nav class="navbar navbar-expand-lg" id="mainNavbar">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <img src="assets/images/logo.jpeg" alt="Logo" class="brand-logo"/>
      <div><span class="brand-name"><?= htmlspecialchars($shopName) ?></span><span class="brand-sub">மிளிர்</span></div>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="categories.php">Categories</a></li>
        <li class="nav-item"><a class="nav-link active" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item position-relative">
          <a class="nav-link" href="cart.php"><i class="bi bi-bag" style="font-size:1.1rem;"></i><span class="cart-badge" id="navCartCount" style="display:none;">0</span></a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="page-hero">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active">About Us</li>
      </ol>
    </nav>
    <h1 class="page-hero-title mt-2">About <span>Us</span></h1>
  </div>
</div>

<section class="section">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 reveal-3d">
        <div style="background:var(--black-3);border:1px solid var(--glass-border);border-radius:var(--radius);padding:50px;text-align:center;box-shadow:var(--shadow-premium);">
          <img src="assets/images/logo.jpeg" alt="<?= htmlspecialchars($shopName) ?>" style="width:150px;height:150px;border-radius:50%;object-fit:cover;border:3px solid var(--gold);margin-bottom:24px;"/>
          <h2 style="color:var(--gold);font-weight:800;margin-bottom:8px;"><?= htmlspecialchars($shopName) ?></h2>
          <p style="color:var(--text-muted);letter-spacing:0.1em;text-transform:uppercase;font-size:0.8rem;">Est. 2026</p>
        </div>
      </div>
      <div class="col-lg-6 fade-up">
        <span class="section-badge">Our Story</span>
        <h2 class="section-title">Crafting Beauty,<br/><span>Creating Sparkle</span></h2>
        <p style="color:var(--text-muted);line-height:1.8;margin-top:16px;">
          Welcome to <strong style="color:var(--gold);"><?= htmlspecialchars($shopName) ?></strong> — a place where elegance meets tradition. Established in 2026, Milir Bangles was created with a vision to bring beautiful, glittering bangles that enhance every woman's style and confidence.
        <p style="color:var(--text-muted);line-height:1.8;margin-top:12px;">
          Our journey is built on passion and attention to detail. Every bangle in our collection is carefully selected to ensure quality, beauty, and comfort. From traditional designs to modern styles, Milir Bangles are made to make your hands shine with grace.
        </p>
        <p style="color:var(--text-muted);line-height:1.8;margin-top:12px;">
          At Milir Bangles, we believe that a simple accessory can brighten your entire look. Browse our collection easily and connect with us through WhatsApp for a smooth and personalized shopping experience that values your style and time. ✨
        </p>
        <div class="hero-ctas mt-4">
          <a href="categories.php" class="btn-gold"><i class="bi bi-grid-3x3-gap"></i> Shop Now</a>
          <a href="https://wa.me/<?= $waNum ?>" target="_blank" class="btn-wa"><i class="bi bi-whatsapp"></i> Chat with Us</a>
        </div>
      </div>
    </div>

    <!-- Values -->
    <div class="row g-4 mt-5">
      <div class="col-12 text-center mb-3">
        <span class="section-badge">Our Values</span>
        <h2 class="section-title">Why Women <span>Choose Us</span></h2>
        <div class="divider-gold"></div>
      </div>
      <div class="col-sm-6 col-lg-3 fade-up"><div class="why-card">
        <div class="why-icon">✨</div><h5>Beautiful Bangles</h5><p>Elegant bangles that add sparkle and beauty to your hands.</p></div></div>
      <div class="col-sm-6 col-lg-3 fade-up"><div class="why-card"><div class="why-icon">💎 </div><h5>High Quality Designs
</h5><p>Carefully crafted designs made with premium quality materials.</p></div></div>
      <div class="col-sm-6 col-lg-3 fade-up"><div class="why-card"><div class="why-icon">🎨</div><h5>Trendy & Traditional Styles</h5>
      <p>A perfect blend of modern fashion and traditional charm.</p></div></div>
      <div class="col-sm-6 col-lg-3 fade-up"><div class="why-card"><div class="why-icon">❤️</div>
      <h5>Affordable Elegance</h5><p>Stylish bangles that bring beauty at a price everyone can enjoy.</p></div></div>
    </div>
  </div>
</section>

<?php require_once 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
