<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/db.php';
$db = getDB();

$catCount  = $db->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$subCount  = $db->query("SELECT COUNT(*) FROM subcategories")->fetchColumn();
$prodCount = $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
$recentProducts = $db->query("SELECT p.*, c.name AS cat_name FROM products p LEFT JOIN categories c ON p.category_id=c.id ORDER BY p.created_at DESC LIMIT 5")->fetchAll();
require 'header.php';
?>

<div class="dash-stats">
  <div class="stat-card">
    <div class="stat-icon"><i class="bi bi-grid-3x3-gap"></i></div>
    <div class="stat-info">
      <div class="stat-num"><?= $catCount ?></div>
      <div class="stat-label">Categories</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon"><i class="bi bi-diagram-3"></i></div>
    <div class="stat-info">
      <div class="stat-num"><?= $subCount ?></div>
      <div class="stat-label">Subcategories</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
    <div class="stat-info">
      <div class="stat-num"><?= $prodCount ?></div>
      <div class="stat-label">Products</div>
    </div>
  </div>
  <div class="stat-card stat-wa">
    <div class="stat-icon"><i class="bi bi-whatsapp"></i></div>
    <div class="stat-info">
      <div class="stat-num">WhatsApp</div>
      <div class="stat-label">Orders via DM</div>
    </div>
  </div>
</div>

<div class="admin-card mt-4">
  <div class="admin-card-header">
    <span><i class="bi bi-clock-history me-2"></i>Recent Products</span>
    <a href="products.php" class="btn-admin-sm">View All</a>
  </div>
  <div class="admin-table-responsive">
    <table class="admin-table">
      <thead><tr><th>#</th><th>Name</th><th>Category</th><th>Price</th><th>Sizes</th><th>Action</th></tr></thead>
      <tbody>
        <?php if (empty($recentProducts)): ?>
        <tr><td colspan="6" class="text-center" style="color:#888;padding:20px;">No products yet. <a href="products.php">Add one!</a></td></tr>
        <?php else: ?>
        <?php foreach ($recentProducts as $p): ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td><?= htmlspecialchars($p['name']) ?></td>
          <td><span class="badge-cat"><?= htmlspecialchars($p['cat_name']) ?></span></td>
          <td>₹<?= number_format($p['price'], 2) ?></td>
          <td style="font-size:0.8rem;"><?= htmlspecialchars($p['sizes']) ?></td>
          <td><a href="products.php?edit=<?= $p['id'] ?>" class="btn-admin-sm">Edit</a></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="row g-3 mt-2">
  <div class="col-md-4">
    <a href="categories.php" class="quick-action-card">
      <i class="bi bi-plus-circle"></i>
      <span>Add Category</span>
    </a>
  </div>
  <div class="col-md-4">
    <a href="products.php" class="quick-action-card">
      <i class="bi bi-box-seam"></i>
      <span>Add Product</span>
    </a>
  </div>
  <div class="col-md-4">
    <a href="settings.php" class="quick-action-card">
      <i class="bi bi-whatsapp"></i>
      <span>WhatsApp Settings</span>
    </a>
  </div>
</div>

<?php require 'footer.php'; ?>
