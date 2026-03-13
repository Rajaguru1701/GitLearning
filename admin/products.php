<?php
$pageTitle = 'Products';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/db.php';
$db  = getDB();
$msg = ''; $msgType = 'success';

// --- Handle Add / Edit / Delete ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_product' || $action === 'edit_product') {
        $id       = (int)($_POST['id'] ?? 0);
        $name     = trim($_POST['name']     ?? '');
        $desc     = trim($_POST['desc']     ?? '');
        $price    = (float)($_POST['price'] ?? 0);
        $cat_id   = (int)($_POST['cat_id']  ?? 0);
        $sub_id   = (int)($_POST['sub_id']  ?? 0) ?: null;
        $stock    = (int)($_POST['stock']   ?? 1);
        $featured = isset($_POST['featured']) ? 1 : 0;
        $is_new   = isset($_POST['is_new']) ? 1 : 0;
        $sizes    = implode(',', $_POST['sizes'] ?? ['S','2.4','2.6','2.8']);
        $colors   = trim($_POST['colors'] ?? 'Default');
        $offer    = (int)($_POST['offer_percent'] ?? 0);
        $image    = trim($_POST['old_image'] ?? '');

        if (!empty($_FILES['image']['name'])) {
            $ext   = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $fname = 'prod_' . time() . '_' . rand(100,999) . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../uploads/' . $fname);
            $image = $fname;
        }

        if (!$name || !$price || !$cat_id) {
            $msg = 'Name, price and category are required.'; $msgType = 'error';
        } else {
            if ($action === 'add_product') {
                $stmt = $db->prepare("INSERT INTO products (name,description,price,offer_percent,category_id,subcategory_id,sizes,colors,image,stock,is_featured,is_new) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                $stmt->execute([$name,$desc,$price,$offer,$cat_id,$sub_id,$sizes,$colors,$image,$stock,$featured,$is_new]);
                $msg = 'Product added!';
            } else {
                $stmt = $db->prepare("UPDATE products SET name=?,description=?,price=?,offer_percent=?,category_id=?,subcategory_id=?,sizes=?,colors=?,image=?,stock=?,is_featured=?,is_new=? WHERE id=?");
                $stmt->execute([$name,$desc,$price,$offer,$cat_id,$sub_id,$sizes,$colors,$image,$stock,$featured,$is_new,$id]);
                $msg = 'Product updated!';
            }
        }
    } elseif ($_POST['action'] === 'delete_product') {
        $id = (int)$_POST['id'];
        $stmt = $db->prepare("SELECT image FROM products WHERE id=?");
        $stmt->execute([$id]);
        $prod = $stmt->fetch();
        if ($prod && $prod['image']) {
            $imgPath = __DIR__ . '/../uploads/' . $prod['image'];
            if (file_exists($imgPath)) @unlink($imgPath);
        }
        $db->prepare("DELETE FROM products WHERE id=?")->execute([$id]);
        $msg = 'Product deleted.';
    }
}

// Edit mode
$editProduct = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM products WHERE id=?");
    $stmt->execute([(int)$_GET['edit']]);
    $editProduct = $stmt->fetch();
}

$categories = $db->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$allSubs    = $db->query("SELECT * FROM subcategories ORDER BY name")->fetchAll();
$products   = $db->query("SELECT p.*,c.name as cat_name,s.name as sub_name FROM products p LEFT JOIN categories c ON p.category_id=c.id LEFT JOIN subcategories s ON p.subcategory_id=s.id ORDER BY p.created_at DESC")->fetchAll();
$sizeOptions= ['S','2.4','2.6','2.8','Free Size'];

require 'header.php';
?>

<?php if ($msg): ?>
<div class="admin-alert <?= $msgType==='error'?'admin-alert-error':'admin-alert-success' ?>">
  <i class="bi bi-<?= $msgType==='error'?'x-circle':'check-circle' ?>-fill me-2"></i><?= htmlspecialchars($msg) ?>
</div>
<?php endif; ?>

<div class="row g-4">
  <!-- Form -->
  <div class="col-lg-4">
    <div class="admin-card">
      <div class="admin-card-header">
        <i class="bi bi-<?= $editProduct?'pencil':'plus-circle' ?> me-2"></i>
        <?= $editProduct ? 'Edit Product' : 'Add New Product' ?>
        <?php if ($editProduct): ?><a href="products.php" class="btn-admin-sm ms-auto">Cancel</a><?php endif; ?>
      </div>
      <form method="POST" enctype="multipart/form-data" class="admin-form" id="productForm">
        <input type="hidden" name="action" value="<?= $editProduct ? 'edit_product' : 'add_product' ?>"/>
        <?php if ($editProduct): ?><input type="hidden" name="id" value="<?= $editProduct['id'] ?>"/><?php endif; ?>
        <input type="hidden" name="old_image" value="<?= htmlspecialchars($editProduct['image']??'') ?>"/>

        <div class="form-group-admin">
          <label>Product Name <span class="req">*</span></label>
          <input type="text" name="name" class="admin-input" value="<?= htmlspecialchars($editProduct['name']??'') ?>" placeholder="e.g. Premium Bangles" required/>
        </div>
        <div class="form-group-admin">
          <label>Description</label>
          <textarea name="desc" class="admin-input" rows="3" placeholder="Product description..."><?= htmlspecialchars($editProduct['description']??'') ?></textarea>
        </div>
        <div class="row g-2">
          <div class="col-5 form-group-admin">
            <label>Current Selling Price (₹) <span class="req">*</span></label>
            <input type="number" name="price" class="admin-input" step="0.01" value="<?= $editProduct['price']??'' ?>" placeholder="0.00" required/>
          </div>
          <div class="col-4 form-group-admin">
            <label>Offer %</label>
            <input type="number" name="offer_percent" class="admin-input" value="<?= $editProduct['offer_percent']??0 ?>" min="0" max="100"/>
          </div>
          <div class="col-3 form-group-admin">
            <label>Stock</label>
            <input type="number" name="stock" class="admin-input" value="<?= $editProduct['stock']??1 ?>" min="0"/>
          </div>
        </div>
        <div class="form-group-admin">
          <label>Category <span class="req">*</span></label>
          <select name="cat_id" class="admin-input" id="catSelect" required onchange="loadSubs(this.value)">
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($editProduct['category_id']??'')==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group-admin">
          <label>Subcategory</label>
          <select name="sub_id" class="admin-input" id="subSelect">
            <option value="">-- None --</option>
            <?php foreach ($allSubs as $s): ?>
            <option value="<?= $s['id'] ?>" data-cat="<?= $s['category_id'] ?>" <?= ($editProduct['subcategory_id']??'')==$s['id']?'selected':'' ?>><?= htmlspecialchars($s['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group-admin">
          <label>Available Sizes</label>
          <div class="sizes-grid">
            <?php foreach ($sizeOptions as $sz): ?>
            <?php $checked = $editProduct ? in_array($sz, explode(',', $editProduct['sizes']??'')) : true; ?>
            <label class="size-check">
              <input type="checkbox" name="sizes[]" value="<?= $sz ?>" <?= $checked?'checked':'' ?>/> <?= $sz ?>
            </label>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="form-group-admin">
          <label>Available Colors</label>
          <input type="text" name="colors" class="admin-input" value="<?= htmlspecialchars($editProduct['colors'] ?? 'Default') ?>" placeholder="e.g. Red, Blue, Black"/>
          <small style="color:var(--text-muted);font-size:0.75rem;">Comma separated list of colors.</small>
        </div>
        <div class="form-group-admin">
          <label>Product Image</label>
          <?php if ($editProduct && $editProduct['image']): ?>
          <div class="current-img-preview">
            <img src="../uploads/<?= htmlspecialchars($editProduct['image']) ?>" alt="Current"/>
            <small>Current image</small>
          </div>
          <?php endif; ?>
          <input type="file" name="image" class="admin-input" accept="image/*" onchange="previewImg(this)"/>
          <img id="imgPreview" src="" alt="" style="display:none;max-width:100%;border-radius:8px;margin-top:8px;"/>
        </div>
        <div class="form-group-admin" style="display:flex; gap:15px;">
          <label class="size-check">
            <input type="checkbox" name="featured" <?= ($editProduct['is_featured']??0)?'checked':'' ?>/> Featured
          </label>
          <label class="size-check">
            <input type="checkbox" name="is_new" <?= ($editProduct['is_new']??0)?'checked':'' ?>/> New Collection
          </label>
        </div>
        <button type="submit" class="btn-admin-primary">
          <i class="bi bi-<?= $editProduct?'check2-circle':'plus' ?> me-1"></i>
          <?= $editProduct ? 'Update Product' : 'Add Product' ?>
        </button>
      </form>
    </div>
  </div>

  <!-- Products List -->
  <div class="col-lg-8">
    <div class="admin-card">
      <div class="admin-card-header">
        <i class="bi bi-box-seam me-2"></i>All Products
        <span class="ms-auto badge-count"><?= count($products) ?> total</span>
      </div>
      <div class="admin-table-responsive">
        <table class="admin-table">
          <thead><tr><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Variations</th><th>Stock</th><th>Actions</th></tr></thead>
          <tbody>
            <?php if (empty($products)): ?>
            <tr><td colspan="7" style="text-align:center;color:#888;padding:24px;">No products yet. Add your first product!</td></tr>
            <?php else: ?>
            <?php foreach ($products as $p): ?>
            <tr>
              <td>
                <?php if ($p['image']): ?>
                <img src="../uploads/<?= htmlspecialchars($p['image']) ?>" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:6px;border:1px solid #c9a84c;"/>
                <?php else: ?>
                <div style="width:48px;height:48px;background:#1a1a1a;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#c9a84c;"><i class="bi bi-image"></i></div>
                <?php endif; ?>
              </td>
              <td>
                <strong><?= htmlspecialchars($p['name']) ?></strong>
                <?php if ($p['is_featured']): ?><span class="featured-badge">★ Featured</span><?php endif; ?>
              </td>
              <td>
                <div><span class="badge-cat"><?= htmlspecialchars($p['cat_name']) ?></span></div>
                <?php if ($p['sub_name']): ?><small style="color:#888;"><?= htmlspecialchars($p['sub_name']) ?></small><?php endif; ?>
              </td>
              <td>
                <strong style="color:#c9a84c;">₹<?= number_format($p['price'],2) ?></strong>
                <?php if($p['offer_percent'] > 0): ?>
                  <br/><small style="color:#dc3545;"><?= $p['offer_percent'] ?>% OFF</small>
                <?php endif; ?>
              </td>
              <td style="font-size:0.75rem;color:#aaa;">
                Sizes: <?= htmlspecialchars($p['sizes']) ?><br/>
                Colors: <?= htmlspecialchars($p['colors']) ?>
              </td>
              <td><span class="<?= $p['stock']>0?'stock-in':'stock-out' ?>"><?= $p['stock']>0?'In Stock':'Out' ?></span></td>
              <td>
                <a href="?edit=<?= $p['id'] ?>" class="btn-admin-sm">Edit</a>
                <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this product?')">
                  <input type="hidden" name="action" value="delete_product"/>
                  <input type="hidden" name="id" value="<?= $p['id'] ?>"/>
                  <button type="submit" class="btn-admin-danger-sm"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
const allSubs = <?= json_encode($allSubs) ?>;
function loadSubs(catId) {
  const sel = document.getElementById('subSelect');
  sel.innerHTML = '<option value="">-- None --</option>';
  allSubs.filter(s => s.category_id == catId).forEach(s => {
    sel.insertAdjacentHTML('beforeend', `<option value="${s.id}">${s.name}</option>`);
  });
}
function previewImg(input) {
  const prev = document.getElementById('imgPreview');
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => { prev.src = e.target.result; prev.style.display = 'block'; };
    reader.readAsDataURL(input.files[0]);
  }
}
// Init subcategory filter for edit mode
<?php if ($editProduct && $editProduct['category_id']): ?>
loadSubs(<?= $editProduct['category_id'] ?>);
document.querySelector('#subSelect option[value="<?= $editProduct['subcategory_id'] ?>"]')?.setAttribute('selected','selected');
<?php endif; ?>
</script>

<?php require 'footer.php'; ?>
