<?php
require_once __DIR__ . '/config/db.php';
$db = getDB();
$waNum    = getSetting('whatsapp_number') ?: '919994264756';
$shopName = getSetting('shop_name')       ?: 'MILIR BANGLES';
$address  = getSetting('shop_address')    ?: '123, Fashion Street, Chennai, Tamil Nadu';
$phone    = getSetting('shop_phone')      ?: '+91 9994264756';
$email    = getSetting('shop_email')      ?: 'milirbangles@gmail.com';
$hours    = getSetting('shop_hours')      ?: 'Mon–Sat: 10 AM – 8 PM';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Contact | <?= htmlspecialchars($shopName) ?></title>
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
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
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
        <li class="breadcrumb-item active">Contact</li>
      </ol>
    </nav>
    <h1 class="page-hero-title mt-2">Get in <span>Touch</span></h1>
  </div>
</div>

<section class="section" style="padding-top:50px;">
  <div class="container">
    <div class="row g-4">
      <!-- Contact Info -->
      <div class="col-lg-5">
        <span class="section-badge">Contact Info</span>
        <h2 class="section-title mb-4">Reach <span>Us</span></h2>

        <div class="why-card mb-3" style="text-align:left;flex-direction:row;display:flex;gap:16px;align-items:flex-start;">
          <div style="width:44px;height:44px;background:rgba(201,168,76,0.12);border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--gold);font-size:1.2rem;flex-shrink:0;"><i class="bi bi-whatsapp"></i></div>
          <div>
            <h6 style="color:var(--gold);font-weight:700;margin-bottom:4px;">WhatsApp</h6>
            <a href="https://wa.me/<?= $waNum ?>" target="_blank" style="color:var(--text-muted);font-size:0.9rem;">+<?= htmlspecialchars($waNum) ?></a>
            <p style="font-size:0.78rem;color:#666;margin-top:2px;">Fastest way to reach us — order, enquire, and more.</p>
          </div>
        </div>
        <div class="why-card mb-3" style="text-align:left;flex-direction:row;display:flex;gap:16px;align-items:flex-start;">
          <div style="width:44px;height:44px;background:rgba(201,168,76,0.12);border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--gold);font-size:1.2rem;flex-shrink:0;"><i class="bi bi-telephone"></i></div>
          <div>
            <h6 style="color:var(--gold);font-weight:700;margin-bottom:4px;">Phone</h6>
            <a href="tel:<?= preg_replace('/[^0-9+]/','',$phone) ?>" style="color:var(--text-muted);font-size:0.9rem;"><?= htmlspecialchars($phone) ?></a>
          </div>
        </div>
        <div class="why-card mb-3" style="text-align:left;flex-direction:row;display:flex;gap:16px;align-items:flex-start;">
          <div style="width:44px;height:44px;background:rgba(201,168,76,0.12);border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--gold);font-size:1.2rem;flex-shrink:0;"><i class="bi bi-envelope"></i></div>
          <div>
            <h6 style="color:var(--gold);font-weight:700;margin-bottom:4px;">Email</h6>
            <a href="mailto:<?= htmlspecialchars($email) ?>" style="color:var(--text-muted);font-size:0.9rem;"><?= htmlspecialchars($email) ?></a>
          </div>
        </div>
        <div class="why-card mb-3" style="text-align:left;flex-direction:row;display:flex;gap:16px;align-items:flex-start;">
          <div style="width:44px;height:44px;background:rgba(201,168,76,0.12);border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--gold);font-size:1.2rem;flex-shrink:0;"><i class="bi bi-geo-alt"></i></div>
          <div>
            <h6 style="color:var(--gold);font-weight:700;margin-bottom:4px;">Address</h6>
            <p style="color:var(--text-muted);font-size:0.9rem;margin:0;"><?= htmlspecialchars($address) ?></p>
          </div>
        </div>
        <div class="why-card" style="text-align:left;flex-direction:row;display:flex;gap:16px;align-items:flex-start;">
          <div style="width:44px;height:44px;background:rgba(201,168,76,0.12);border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--gold);font-size:1.2rem;flex-shrink:0;"><i class="bi bi-clock"></i></div>
          <div>
            <h6 style="color:var(--gold);font-weight:700;margin-bottom:4px;">Business Hours</h6>
            <p style="color:var(--text-muted);font-size:0.9rem;margin:0;"><?= htmlspecialchars($hours) ?></p>
          </div>
        </div>
      </div>

      <!-- WhatsApp CTA -->
      <div class="col-lg-7">
        <div style="background:var(--black-3);border:1px solid rgba(201,168,76,0.2);border-radius:20px;padding:40px;height:100%;display:flex;flex-direction:column;justify-content:center;">
          <span class="section-badge" style="display:inline-block;width:fit-content;margin-bottom:16px;">Quick Connect</span>
          <h2 class="section-title mb-3">Order via <span>WhatsApp</span></h2>
          <p style="color:var(--text-muted);line-height:1.8;margin-bottom:24px;">
            The easiest way to shop at <?= htmlspecialchars($shopName) ?> is through WhatsApp. Browse our collections, add your favourite items to cart, and click "Order via WhatsApp" to send us your order directly.<br/><br/>
            We'll reply with availability, sizes in stock, and payment/delivery details. It's that simple!
          </p>
          <div style="background:var(--black-4);border-radius:14px;padding:20px;margin-bottom:24px;">
            <div style="color:var(--gold);font-weight:700;margin-bottom:10px;"><i class="bi bi-info-circle me-2"></i>How it works:</div>
            <ol style="color:var(--text-muted);font-size:0.88rem;line-height:2;padding-left:20px;">
              <li>Browse our categories and products</li>
              <li>Select size and add to cart</li>
              <li>Go to cart and click "Order via WhatsApp"</li>
              <li>WhatsApp opens with your full order pre-filled</li>
              <li>We confirm and complete your order!</li>
            </ol>
          </div>
          <div class="d-flex gap-3 flex-wrap">
            <a href="https://wa.me/<?= $waNum ?>" target="_blank" class="btn-wa">
              <i class="bi bi-whatsapp"></i> Chat on WhatsApp
            </a>
            <a href="categories.php" class="btn-outline-gold">
              <i class="bi bi-grid-3x3-gap"></i> Browse Collections
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
