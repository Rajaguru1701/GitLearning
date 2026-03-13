<?php
require_once __DIR__ . '/config/db.php';
$db = getDB();
$waNum    = getSetting('whatsapp_number') ?: '919994264756';
$shopName = getSetting('shop_name')       ?: 'MILIR BANGLES';
$categories = $db->query("SELECT * FROM categories ORDER BY sort_order, name")->fetchAll();
$featured   = $db->query("SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.is_featured=1 AND p.stock>0 LIMIT 6")->fetchAll();
$new_items  = $db->query("SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.is_new=1 AND p.stock>0 ORDER BY p.created_at DESC LIMIT 6")->fetchAll();
$posters    = $db->query("SELECT * FROM posters WHERE is_active=1 ORDER BY created_at DESC")->fetchAll();
$tickerText = getSetting('shop_ticker') ?: 'New Collection 2026 is now live! Discover the latest sparkling bangles designed to add beauty and shine to your style.';
$popupPoster = $db->query("SELECT * FROM posters WHERE is_popup=1 LIMIT 1")->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>

<!--  -->
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta name="description" content="MILIR BANGLES — Premium Men's Fashion. Shop Shirts, T-Shirts, Trousers, Jeans, Ethnic Wear & more. Order via WhatsApp."/>
<title><?= htmlspecialchars($shopName) ?> | Premium Men's Fashion</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="assets/css/style.css"/>
</head>
<body>

<!-- NAVBAR ____ -->
 
<nav class="navbar navbar-expand-lg" id="mainNavbar">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <img src="assets/images/logo.jpeg" alt="<?= htmlspecialchars($shopName) ?> Logo" class="brand-logo"/>
      <div>
        <span class="brand-name"><?= htmlspecialchars($shopName) ?></span>
        <span class="brand-sub">மிளிர்</span>
      </div>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="categories.php">Categories</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item position-relative">
          <a class="nav-link" href="cart.php" id="cartNavLink">
            <i class="bi bi-bag" style="font-size:1.1rem;"></i>
            <span class="cart-badge" id="navCartCount" style="display:none;">0</span>
          </a>
        </li>
        <li class="nav-item ms-lg-2">
          <a href="categories.php" class="btn-gold btn">Shop Now <i class="bi bi-arrow-right"></i></a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- TICKER -->
<div class="ticker-wrapper">
  <div class="ticker-content">
    <span><?= htmlspecialchars($tickerText) ?></span>
    <span><?= htmlspecialchars($tickerText) ?></span> <!-- Duplicate for seamless loop -->
  </div>
</div>

<!-- HERO -->
<section class="hero" id="hero">
  <div class="hero-bg"></div>
  <div class="hero-grid"></div>
  <div class="container position-relative" style="z-index:2;">
    <div class="row align-items-center">
      <div class="col-lg-7">
        <div class="reveal-3d">
          <span class="hero-badge"><i class="bi bi-stars"></i> Exclusive Collection 2026</span>
          <h1 class="hero-title">
            Redefining <br/><span class="gold">Modern Elegance.</span>
          </h1>
        </div>
        <div class="fade-up" data-delay="300">
          <p class="hero-sub">Welcome to மிளிர் (Milir) Bangles, your destination for elegant and stylish bangles that add sparkle to every moment.</p>
          <div class="hero-ctas">
            <a href="categories.php" class="btn-gold">
              <i class="bi bi-grid-3x3-gap"></i> Explore Collections
            </a>
            <a href="https://wa.me/<?= $waNum ?>" target="_blank" class="btn-wa">
              <i class="bi bi-whatsapp"></i> Consult with Us
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>



<!-- CATEGORIES -->
<section class="section" id="categories">
  <div class="container">
    <div class="text-center mb-5 reveal-3d">
      <span class="section-badge">Curated Collections</span>
      <h2 class="section-title">The Art of <span>Elegance</span></h2>
      <div class="divider-gold"></div>
      <p class="section-sub">A beautifully selected collection of bangles designed to add sparkle and grace to every woman’s style. ✨</p>
    </div>
    <div class="row g-4">
      <?php if (empty($categories)): ?>
      <div class="col-12"><div class="empty-state"><i class="bi bi-grid-3x3-gap"></i><p>No categories yet. Check back soon!</p></div></div>
      <?php else: ?>
      <?php foreach ($categories as $cat): ?>
      <div class="col-sm-6 col-lg-4 fade-up">
        <a href="products.php?category_id=<?= $cat['id'] ?>" class="cat-card">
          <div class="cat-card-img">
            <?php if ($cat['image']): ?>
            <img src="uploads/<?= htmlspecialchars($cat['image']) ?>" alt="<?= htmlspecialchars($cat['name']) ?>" loading="lazy"/>
            <?php else: ?>
            <div class="cat-placeholder">
              <i class="bi bi-handbag"></i>
              <span style="font-size:0.9rem;font-weight:700;"><?= htmlspecialchars($cat['name']) ?></span>
            </div>
            <?php endif; ?>
          </div>
          <div class="cat-card-body">
            <span class="cat-card-arrow"><i class="bi bi-arrow-right-circle-fill"></i></span>
            <div class="cat-card-name"><?= htmlspecialchars($cat['name']) ?></div>
            <div class="cat-card-count">Explore Collection</div>
          </div>
        </a>
      </div>
      <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- FEATURED PRODUCTS -->
<?php if (!empty($featured)): ?>
<section class="section" style="background:var(--black-2);padding-top:60px;padding-bottom:80px;">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-badge">Handpicked</span>
      <h2 class="section-title">Featured <span>Products</span></h2>
      <div class="divider-gold"></div>
    </div>
    <div class="row g-4">
      <?php foreach ($featured as $p): ?>
      <div class="col-sm-6 col-lg-4 fade-up">
        <div class="product-card">
          <div class="product-img-wrap">
            <?php if ($p['image']): ?>
            <img src="uploads/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy"/>
            <?php else: ?>
            <div class="product-placeholder"><i class="bi bi-person-standing"></i></div>
            <?php endif; ?>
            <?php if ($p['is_featured']): ?><span class="product-badge">★ Featured</span><?php endif; ?>
            <?php if ($p['offer_percent'] > 0): ?><span class="product-badge-offer"><?= $p['offer_percent'] ?>% OFF</span><?php endif; ?>
          </div>
          <div class="product-body">
            <div class="product-cat"><?= htmlspecialchars($p['cat_name']) ?></div>
            <div class="product-name"><?= htmlspecialchars($p['name']) ?></div>
            <div class="product-price">
              ₹<?= number_format($p['price'],2) ?>
              <?php if($p['offer_percent'] > 0): ?>
              <?php $original = $p['price'] / (1 - ($p['offer_percent']/100)); ?>
              <span class="price-original">₹<?= number_format($original,2) ?></span>
              <?php endif; ?>
            </div>
            <div class="product-sizes">
              <?php foreach (explode(',', $p['sizes']) as $sz): ?>
              <span class="size-pill"><?= trim($sz) ?></span>
              <?php endforeach; ?>
            </div>
            <button class="btn-add-cart"
              data-id="<?= $p['id'] ?>"
              data-name="<?= htmlspecialchars($p['name']) ?>"
              data-price="<?= $p['price'] ?>"
              data-image="<?= $p['image'] ? 'uploads/'.$p['image'] : '' ?>">
              <i class="bi bi-bag-plus"></i> Add to Cart
            </button>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-5">
      <a href="categories.php" class="btn-outline-gold">View All Products <i class="bi bi-arrow-right"></i></a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ADVERTISEMENT POSTERS -->
<?php if (!empty($posters)): ?>
<section class="section-posters" style="padding: 60px 0;">
  <div class="container">
    <div id="posterCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php foreach ($posters as $idx => $pst): ?>
        <div class="carousel-item <?= $idx === 0 ? 'active' : '' ?>">
          <div class="new-arrival-ad">
            <div class="row g-0 align-items-center">
              <div class="col-md-6 order-md-2">
                <div class="ad-image-wrap">
                  <img src="uploads/<?= htmlspecialchars($pst['image']) ?>" alt="<?= htmlspecialchars($pst['title']) ?>" class="ad-image"/>
                </div>
              </div>
              <div class="col-md-6 order-md-1">
                <div class="ad-content">
                  <span class="ad-badge">Special Offer</span>
                  <h2 class="ad-title"><?= htmlspecialchars($pst['title']) ?></h2>
                  <p class="ad-desc"><?= htmlspecialchars($pst['description']) ?></p>
                  <?php if ($pst['link']): ?>
                  <a href="<?= htmlspecialchars($pst['link']) ?>" class="btn-gold">Learn More <i class="bi bi-arrow-right"></i></a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php if (count($posters) > 1): ?>
      <button class="carousel-control-prev" type="button" data-bs-target="#posterCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#posterCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
      </button>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if (!empty($new_items)): ?>
<section class="section section-new-arrival" style="padding-top:60px;padding-bottom:80px;">
  <div class="container">
    <?php if (empty($posters)): ?>
    <!-- Fallback spotlight if no posters -->
    <?php $latest_new = $new_items[0]; ?>
    <div class="new-arrival-ad reveal-3d mb-5">
      <div class="row g-0 align-items-center">
        <div class="col-md-6 order-md-2">
          <div class="ad-image-wrap">
            <img src="<?= $latest_new['image'] ? 'uploads/'.htmlspecialchars($latest_new['image']) : 'assets/images/placeholder.jpg' ?>" alt="<?= htmlspecialchars($latest_new['name']) ?>" class="ad-image"/>
          </div>
        </div>
        <div class="col-md-6 order-md-1">
          <div class="ad-content">
            <span class="ad-badge">Latest Arrival</span>
            <h2 class="ad-title"><?= htmlspecialchars($latest_new['name']) ?></h2>
            <p class="ad-desc"><?= htmlspecialchars($latest_new['description'] ?: 'Elevate your wardrobe with our latest masterpiece. Crafted for the modern man who values excellence.') ?></p>
            <div class="ad-price">₹<?= number_format($latest_new['price'], 2) ?></div>
            <a href="products.php?category_id=<?= $latest_new['category_id'] ?>" class="btn-gold">Shop the Collection <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <div class="text-center mb-5 fade-up">
      <span class="section-badge">The Collection</span>
      <h2 class="section-title">New <span>Arrivals</span></h2>
      <div class="divider-gold"></div>
    </div>
    <div class="row g-4">
      <?php foreach ($new_items as $p): ?>
      <div class="col-sm-6 col-lg-4 fade-up">
        <div class="product-card">
          <div class="product-img-wrap">
            <?php if ($p['image']): ?>
            <img src="uploads/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy"/>
            <?php else: ?>
            <div class="product-placeholder"><i class="bi bi-person-standing"></i></div>
            <?php endif; ?>
            <span class="product-badge" style="background:#0dcaf0;color:#000;">Just Added</span>
            <?php if ($p['offer_percent'] > 0): ?><span class="product-badge-offer"><?= $p['offer_percent'] ?>% OFF</span><?php endif; ?>
          </div>
          <div class="product-body">
            <div class="product-cat"><?= htmlspecialchars($p['cat_name']) ?></div>
            <div class="product-name"><?= htmlspecialchars($p['name']) ?></div>
            <div class="product-price">
              ₹<?= number_format($p['price'],2) ?>
              <?php if($p['offer_percent'] > 0): ?>
              <?php $original = $p['price'] / (1 - ($p['offer_percent']/100)); ?>
              <span class="price-original">₹<?= number_format($original,2) ?></span>
              <?php endif; ?>
            </div>
            <div class="product-sizes">
              <?php foreach (explode(',', $p['sizes']) as $sz): ?>
              <span class="size-pill"><?= trim($sz) ?></span>
              <?php endforeach; ?>
            </div>
            <button class="btn-add-cart"
              data-id="<?= $p['id'] ?>"
              data-name="<?= htmlspecialchars($p['name']) ?>"
              data-price="<?= $p['price'] ?>"
              data-image="<?= $p['image'] ? 'uploads/'.$p['image'] : '' ?>">
              <i class="bi bi-bag-plus"></i> Add to Cart
            </button>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-5">
      <a href="categories.php" class="btn-outline-gold">View More <i class="bi bi-arrow-right"></i></a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- WHY US -->
<section class="section">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-badge">Why Choose Us</span>
      <h2 class="section-title">Our <span>Promise</span> To You</h2>
      <div class="divider-gold"></div>
    </div>
    <div class="row g-4">
      <div class="col-sm-6 col-lg-3 fade-up"><div class="why-card"><div class="why-icon">💎</div><h5>Premium Quality</h5><p>Every bangle is carefully crafted with quality materials to ensure lasting shine and beauty.</p></div></div>
      <div class="col-sm-6 col-lg-3 fade-up"><div class="why-card"><div class="why-icon">✨</div><h5>Elegant & Trendy Designs</h5><p>Discover beautiful bangles that blend traditional charm with modern style.</p></div></div>
      <div class="col-sm-6 col-lg-3 fade-up"><div class="why-card"><div class="why-icon">📱</div><h5>Easy WhatsApp Order</h5><p>Browse our collection, add your favorites, and order easily through WhatsApp.</p></div></div>
      <div class="col-sm-6 col-lg-3 fade-up"><div class="why-card"><div class="why-icon">🌟</div><h5>Trusted by Many Women</h5><p>Loved by many happy customers who trust Milir Bangles for style and elegance.</p></div></div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <div class="container">
    <div class="row align-items-center gy-4">
      <div class="col-lg-7">
        <span class="section-badge">Let's Connect</span>
        <h2 class="section-title">Ready to Elevate<br/><span>Your Style?</span></h2>
        <p style="color:var(--text-muted);margin-top:12px;">Browse our collections and order directly via WhatsApp — fast, easy, no account needed!</p>
      </div>
      <div class="col-lg-5 text-lg-end">
        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-lg-end">
          <a href="categories.php" class="btn-outline-gold"><i class="bi bi-grid-3x3-gap"></i> Browse Now</a>
          <a href="https://wa.me/<?= $waNum ?>" target="_blank" class="btn-wa"><i class="bi bi-whatsapp"></i> WhatsApp Us</a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once 'footer.php'; ?>

<!-- ADVERTISEMENT POPUP MODAL -->
<?php if ($popupPoster): ?>
<div class="ad-popup-overlay" id="adPopup">
  <div class="ad-popup-content reveal-3d">
    <button class="ad-popup-close" id="closeAdPopup"><i class="bi bi-x-lg"></i></button>
    <div class="ad-popup-body">
      <div class="ad-popup-img-wrap">
        <img src="uploads/<?= htmlspecialchars($popupPoster['image']) ?>" alt="<?= htmlspecialchars($popupPoster['title']) ?>"/>
      </div>
      <div class="ad-popup-text">
        <span class="ad-badge">Limited Time Offer</span>
        <h3 class="ad-popup-title"><?= htmlspecialchars($popupPoster['title']) ?></h3>
        <p class="ad-popup-desc"><?= htmlspecialchars($popupPoster['description']) ?></p>
        <?php if ($popupPoster['link']): ?>
        <a href="<?= htmlspecialchars($popupPoster['link']) ?>" class="btn-gold w-100">Claim Offer Now <i class="bi bi-arrow-right"></i></a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const adPopup = document.getElementById('adPopup');
  const closeBtn = document.getElementById('closeAdPopup');
  
  // Show popup after 1.5 seconds if not closed this session
  if (!sessionStorage.getItem('ad_popup_closed')) {
    setTimeout(() => {
      adPopup.classList.add('active');
      document.body.style.overflow = 'hidden'; // Prevent scroll
    }, 1500);
  }

  closeBtn.addEventListener('click', () => {
    adPopup.classList.remove('active');
    document.body.style.overflow = ''; // Restore scroll
    sessionStorage.setItem('ad_popup_closed', 'true');
  });

  // Close on backdrop click
  adPopup.addEventListener('click', (e) => {
    if (e.target === adPopup) {
      closeBtn.click();
    }
  });
});
</script>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>