<?php
require_once __DIR__ . '/config/db.php';
session_start();
$db = getDB();
$waNum    = getSetting('whatsapp_number') ?: '919994264756';
$shopName = getSetting('shop_name')       ?: 'IHTKAS FASHIONS';
$cart     = $_SESSION['cart'] ?? [];
$total    = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $cart));
$itemCount= array_sum(array_column($cart, 'qty'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Cart | <?= htmlspecialchars($shopName) ?></title>
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
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item position-relative">
          <a class="nav-link active" href="cart.php"><i class="bi bi-bag" style="font-size:1.1rem;"></i><span class="cart-badge" id="navCartCount" <?= $itemCount>0?'':'style="display:none;"' ?>><?= $itemCount ?></span></a>
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
        <li class="breadcrumb-item active">Cart</li>
      </ol>
    </nav>
    <h1 class="page-hero-title mt-2">Your <span>Cart</span></h1>
    <p style="color:var(--text-muted);margin-top:6px;"><?= $itemCount ?> item<?= $itemCount!=1?'s':'' ?></p>
  </div>
</div>

<section class="section" style="padding-top:40px;">
  <div class="container">
    <?php if (empty($cart)): ?>
    <!-- Empty Cart -->
    <div class="empty-state" style="padding:80px 20px;">
      <i class="bi bi-bag-x" style="font-size:4rem;color:rgba(201,168,76,0.3);margin-bottom:20px;display:block;"></i>
      <h3 style="color:var(--text);margin-bottom:10px;">Your cart is empty</h3>
      <p>Browse our collections and add items you love.</p>
      <a href="categories.php" class="btn-gold mt-4" style="display:inline-flex;"><i class="bi bi-grid-3x3-gap"></i> Browse Collections</a>
    </div>
    <?php else: ?>
    <div class="row g-4">
      <!-- Cart Items -->
      <div class="col-lg-8" id="cartItemsWrapper">
        <?php foreach ($cart as $key => $item): ?>
        <div class="cart-item" data-key="<?= htmlspecialchars($key) ?>" id="item-<?= htmlspecialchars($key) ?>">
          <?php if ($item['image']): ?>
          <img src="<?= htmlspecialchars($item['image']) ?>" alt="" class="cart-item-img"/>
          <?php else: ?>
          <div class="cart-item-img-placeholder"><i class="bi bi-person-standing"></i></div>
          <?php endif; ?>
          <div class="flex-grow-1">
            <div class="cart-item-name"><?= htmlspecialchars($item['name']) ?></div>
            <div class="cart-item-size"><i class="bi bi-tag me-1"></i>Size: <strong><?= htmlspecialchars($item['size']) ?></strong> | Color: <strong><?= htmlspecialchars($item['color'] ?? 'Default') ?></strong></div>
            <div class="cart-item-price">₹<?= number_format($item['price'],2) ?></div>
          </div>
          <div class="qty-control">
            <button class="qty-btn" onclick="updateQty('<?= $key ?>',<?= $item['qty']-1 ?>)">−</button>
            <span class="qty-val" id="qty-<?= $key ?>"><?= $item['qty'] ?></span>
            <button class="qty-btn" onclick="updateQty('<?= $key ?>',<?= $item['qty']+1 ?>)">+</button>
          </div>
          <div style="min-width:70px;text-align:right;">
            <div style="font-weight:800;color:var(--gold);">₹<?= number_format($item['price']*$item['qty'],2) ?></div>
          </div>
          <button class="btn-remove-item" onclick="removeItem('<?= $key ?>')" title="Remove">
            <i class="bi bi-trash3"></i>
          </button>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Summary -->
      <div class="col-lg-4">
        <div class="cart-summary">
          <div class="cart-summary-title"><i class="bi bi-receipt me-2"></i>Order Summary</div>
          <?php foreach ($cart as $key => $item): ?>
          <div class="summary-row">
            <span><?= htmlspecialchars($item['name']) ?> (<?= htmlspecialchars($item['size']) ?> / <?= htmlspecialchars($item['color'] ?? 'Default') ?>) × <?= $item['qty'] ?></span>
            <span>₹<?= number_format($item['price']*$item['qty'],2) ?></span>
          </div>
          <?php endforeach; ?>
          <div class="summary-row summary-total">
            <span>Total</span>
            <span id="cartTotal">₹<?= number_format($total,2) ?></span>
          </div>
          <!-- WhatsApp Order Button -->
          <?php
          $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
          $domainName = $_SERVER['HTTP_HOST'];
          $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
          $baseUrl = rtrim($protocol . $domainName . $basePath, '/');

          $waMsg = "Hello! I'd like to order from " . $shopName . ":\n\n";
          foreach ($cart as $item) {
              $colStr = isset($item['color']) ? " / Color: " . $item['color'] : "";
              $waMsg .= "• " . $item['name'] . " (Size: " . $item['size'] . $colStr . ") × " . $item['qty'] . " = ₹" . number_format($item['price']*$item['qty'],2) . "\n";
              if (!empty($item['image'])) {
                  $imgUrl = $baseUrl . '/' . ltrim($item['image'], '/');
                  $waMsg .= "  Image Link: " . $imgUrl . "\n";
              }
          }
          $waMsg .= "\nTotal: ₹" . number_format($total,2) . "\n\nPlease confirm availability and payment details. Thank you!";
          $waLink = "https://wa.me/" . $waNum . "?text=" . rawurlencode($waMsg);
          ?>
          <a href="<?= htmlspecialchars($waLink) ?>" target="_blank" class="btn-wa" style="width:100%;justify-content:center;margin-top:16px;">
            <i class="bi bi-whatsapp"></i> Order via WhatsApp
          </a>
          <a href="categories.php" class="btn-outline-gold" style="width:100%;justify-content:center;margin-top:10px;">
            <i class="bi bi-arrow-left"></i> Continue Shopping
          </a>
          <button onclick="clearCart()" style="width:100%;background:none;border:1px solid #e74c3c33;color:#e74c3c88;padding:8px;border-radius:8px;cursor:pointer;margin-top:8px;font-size:0.8rem;transition:all 0.2s;" onmouseover="this.style.color='#e74c3c';this.style.borderColor='#e74c3c55'" onmouseout="this.style.color='#e74c3c88';this.style.borderColor='#e74c3c33'">
            <i class="bi bi-trash me-1"></i> Clear Cart
          </button>
          <div style="margin-top:16px;padding:12px;background:var(--black-4);border-radius:8px;font-size:0.78rem;color:var(--text-muted);line-height:1.5;">
            <i class="bi bi-info-circle me-1" style="color:var(--gold);"></i>
            Click "Order via WhatsApp" to send your order directly to us. We'll confirm availability and share payment details.
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php require_once 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
<script>
function updateQty(key, qty) {
  if (qty <= 0) { removeItem(key); return; }
  fetch('api/cart.php', { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body: new URLSearchParams({action:'update',key,qty}) })
    .then(r=>r.json()).then(()=>location.reload());
}
function removeItem(key) {
  fetch('api/cart.php', { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body: new URLSearchParams({action:'remove',key}) })
    .then(r=>r.json()).then(()=>location.reload());
}
function clearCart() {
  if (!confirm('Clear all items from cart?')) return;
  fetch('api/cart.php', { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body: new URLSearchParams({action:'clear'}) })
    .then(r=>r.json()).then(()=>location.reload());
}
</script>
</body>
</html>
