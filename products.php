<?php
require_once __DIR__ . '/config/db.php';
$db = getDB();
$waNum    = getSetting('whatsapp_number') ?: '919994264756';
$shopName = getSetting('shop_name')       ?: 'MILIR BANGLES';

// Get filters
$category_id    = isset($_GET['category_id'])    ? (int)$_GET['category_id']    : 0;
$subcategory_id = isset($_GET['subcategory_id']) ? (int)$_GET['subcategory_id'] : 0;

// Get category info
$currentCat = null;
if ($category_id) {
    $stmt = $db->prepare("SELECT * FROM categories WHERE id=?");
    $stmt->execute([$category_id]);
    $currentCat = $stmt->fetch();
}

// Get subcategories for sidebar
$subcategories = [];
if ($category_id) {
    $stmt = $db->prepare("SELECT * FROM subcategories WHERE category_id=? ORDER BY name");
    $stmt->execute([$category_id]);
    $subcategories = $stmt->fetchAll();
}

// Get all categories (for dropdown)
$allCategories = $db->query("SELECT * FROM categories ORDER BY sort_order, name")->fetchAll();

// Build products query
$sql    = "SELECT p.*, c.name as cat_name, s.name as sub_name FROM products p LEFT JOIN categories c ON p.category_id=c.id LEFT JOIN subcategories s ON p.subcategory_id=s.id WHERE p.stock>0";
$params = [];
if ($subcategory_id) {
    $sql .= " AND p.subcategory_id=?"; $params[] = $subcategory_id;
} elseif ($category_id) {
    $sql .= " AND p.category_id=?"; $params[] = $category_id;
}
$sql .= " ORDER BY p.is_featured DESC, p.created_at DESC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title><?= $currentCat ? htmlspecialchars($currentCat['name']).' — ' : '' ?>Products | <?= htmlspecialchars($shopName) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="assets/css/style.css"/>
</head>
<body>

<nav class="navbar navbar-expand-lg" id="mainNavbar">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <img src="assets/images/logo.jpeg" alt="Logo" class="brand-logo"/>
      <div><span class="brand-name"><?= htmlspecialchars($shopName) ?></span><span class="brand-sub">Men's Fashion</span></div>
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
        <li class="breadcrumb-item"><a href="categories.php">Categories</a></li>
        <?php if ($currentCat): ?>
        <li class="breadcrumb-item active"><?= htmlspecialchars($currentCat['name']) ?></li>
        <?php else: ?>
        <li class="breadcrumb-item active">All Products</li>
        <?php endif; ?>
      </ol>
    </nav>
    <h1 class="page-hero-title mt-2">
      <?= $currentCat ? '<span>'.htmlspecialchars($currentCat['name']).'</span> Collection' : 'All <span>Products</span>' ?>
    </h1>
    <p style="color:var(--text-muted);margin-top:6px;"><?= count($products) ?> product<?= count($products)!=1?'s':'' ?> found</p>
  </div>
</div>

<!-- PRODUCTS -->
<section class="section" style="padding-top:40px;">
  <div class="container">
    <div class="row g-4">
      <!-- Sidebar Filter -->
      <?php if (!empty($subcategories) || !empty($allCategories)): ?>
      <div class="col-lg-3">
        <div class="filter-card">
          <div class="filter-title"><i class="bi bi-funnel me-1"></i> Filter</div>
          <!-- All Categories -->
          <div class="filter-title" style="margin-top:14px;">Categories</div>
          <?php foreach ($allCategories as $ac): ?>
          <a href="products.php?category_id=<?= $ac['id'] ?>" class="sub-filter-btn <?= $category_id==$ac['id']&&!$subcategory_id?'active':'' ?>">
            <?= htmlspecialchars($ac['name']) ?>
          </a>
          <?php endforeach; ?>

          <?php if (!empty($subcategories)): ?>
          <div class="filter-title" style="margin-top:16px;">Subcategories</div>
          <a href="products.php?category_id=<?= $category_id ?>" class="sub-filter-btn <?= !$subcategory_id?'active':'' ?>">All <?= htmlspecialchars($currentCat['name']) ?></a>
          <?php foreach ($subcategories as $sub): ?>
          <a href="products.php?category_id=<?= $category_id ?>&subcategory_id=<?= $sub['id'] ?>" class="sub-filter-btn <?= $subcategory_id==$sub['id']?'active':'' ?>">
            <?= htmlspecialchars($sub['name']) ?>
          </a>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
      <div class="col-lg-9">
      <?php else: ?>
      <div class="col-12">
      <?php endif; ?>

        <!-- Products Grid -->
        <?php if (empty($products)): ?>
        <div class="empty-state">
          <i class="bi bi-box-seam"></i>
          <p>No products found in this category yet.<br/>Check back soon or <a href="categories.php" style="color:var(--gold);">browse other categories</a>.</p>
        </div>
        <?php else: ?>
        <div class="row g-4">
          <?php foreach ($products as $p): ?>
          <div class="col-sm-6 col-xl-4 fade-up">
            <div class="product-card">
              <div class="product-img-wrap">
                <?php if ($p['image']): ?>
                <img src="uploads/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy"/>
                <?php else: ?>
                <div class="product-placeholder"><i class="bi bi-person-standing"></i></div>
                <?php endif; ?>
                <?php if ($p['is_featured']): ?><span class="product-badge">★ Featured</span><?php endif; ?>
                <?php if ($p['offer_percent'] > 0): ?><span class="product-badge-offer"><?= $p['offer_percent'] ?>% OFF</span><?php endif; ?>
                <?php if ($p['is_new']): ?><span class="product-badge" style="background:#0dcaf0;color:#000;top:<?= ($p['is_featured']||$p['offer_percent']>0)?'40px':'12px' ?>;">Just Added</span><?php endif; ?>
              </div>
              <div class="product-body">
                <div class="product-cat"><?= htmlspecialchars($p['cat_name']) ?><?= $p['sub_name'] ? ' / '.htmlspecialchars($p['sub_name']) : '' ?></div>
                <div class="product-name"><?= htmlspecialchars($p['name']) ?></div>
                <?php if ($p['description']): ?><div class="product-desc"><?= htmlspecialchars($p['description']) ?></div><?php endif; ?>
                <div class="product-price">
                  ₹<?= number_format($p['price'],2) ?>
                  <?php if($p['offer_percent'] > 0): ?>
                  <?php $original = $p['price'] / (1 - ($p['offer_percent']/100)); ?>
                  <span class="price-original">₹<?= number_format($original,2) ?></span>
                  <?php endif; ?>
                </div>
                <!-- Size Selector -->
                <?php $sizes = array_filter(array_map('trim', explode(',', $p['sizes']))); ?>
                <?php if (!empty($sizes)): ?>
                <div class="product-sizes" data-product-id="<?= $p['id'] ?>">
                  <?php foreach ($sizes as $sz): ?>
                  <span class="size-pill" data-size="<?= htmlspecialchars($sz) ?>" onclick="selectSize(this)"><?= htmlspecialchars($sz) ?></span>
                  <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <!-- Color Selector -->
                <?php $colors = array_filter(array_map('trim', explode(',', $p['colors']))); ?>
                <?php if (!empty($colors)): ?>
                <div class="product-colors" data-product-id="<?= $p['id'] ?>" style="display:flex;flex-wrap:wrap;gap:5px;margin-bottom:14px;">
                  <?php foreach ($colors as $col): ?>
                  <span class="size-pill color-pill" data-color="<?= htmlspecialchars($col) ?>" onclick="selectColor(this)"><?= htmlspecialchars($col) ?></span>
                  <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <button class="btn-add-cart"
                  onclick="addToCart(this)"
                  data-id="<?= $p['id'] ?>"
                  data-name="<?= htmlspecialchars($p['name'], ENT_QUOTES) ?>"
                  data-price="<?= $p['price'] ?>"
                  data-image="<?= $p['image'] ? 'uploads/'.$p['image'] : '' ?>">
                  <i class="bi bi-bag-plus"></i> Add to Cart
                </button>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php require_once 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
<script>
function selectSize(el) {
  const wrap = el.closest('.product-sizes');
  wrap.querySelectorAll('.size-pill').forEach(p => p.classList.remove('selected'));
  el.classList.add('selected');
}
function selectColor(el) {
  const wrap = el.closest('.product-colors');
  wrap.querySelectorAll('.color-pill').forEach(p => p.classList.remove('selected'));
  el.classList.add('selected');
}
function addToCart(btn) {
  const card    = btn.closest('.product-card');
  const sizePill= card.querySelector('.product-sizes .size-pill.selected');
  const size    = sizePill ? sizePill.dataset.size : 'Free Size';
  const colPill = card.querySelector('.product-colors .color-pill.selected');
  const color   = colPill ? colPill.dataset.color : 'Default';
  const data    = {
    action : 'add',
    product_id: btn.dataset.id,
    name  : btn.dataset.name,
    price : btn.dataset.price,
    image : btn.dataset.image,
    size  : size,
    color : color,
    qty   : 1
  };
  fetch('api/cart.php', { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body: new URLSearchParams(data) })
    .then(r=>r.json()).then(res=>{
      if (res.success) {
        showToast('✓ Added to cart!');
        updateCartBadge(res.cart_count);
        btn.innerHTML = '<i class="bi bi-check2-circle"></i> Added!';
        setTimeout(() => btn.innerHTML = '<i class="bi bi-bag-plus"></i> Add to Cart', 1500);
      }
    });
}
</script>
</body>
</html>
