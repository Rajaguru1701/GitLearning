<?php
$pageTitle = 'Categories';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/db.php';
$db = getDB();

$msg = '';
$msgType = 'success';

// --- Handle POST actions ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Add Category
    if ($action === 'add_category') {
        $name = trim($_POST['name'] ?? '');
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));
        $image = null;
        if (!empty($_FILES['image']['name'])) {
            $ext   = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $fname = 'cat_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../uploads/' . $fname);
            $image = $fname;
        }
        if ($name) {
            try {
                $stmt = $db->prepare("INSERT INTO categories (name,slug,image) VALUES (?,?,?)");
                $stmt->execute([$name, $slug, $image]);
                $msg = 'Category added successfully!';
            } catch (PDOException $e) {
                $msg = 'Failed to add category. Name may already exist.';
                $msgType = 'error';
            }
        } else { $msg = 'Name is required.'; $msgType = 'error'; }
    }

    // Delete Category
    elseif ($action === 'delete_category') {
        $id = (int)$_POST['id'];
        try {
            $stmt = $db->prepare("SELECT image FROM categories WHERE id=?");
            $stmt->execute([$id]);
            $cat = $stmt->fetch();
            if ($cat && $cat['image']) {
                $imgPath = __DIR__ . '/../uploads/' . $cat['image'];
                if (file_exists($imgPath)) @unlink($imgPath);
            }
            $db->prepare("DELETE FROM products WHERE category_id=?")->execute([$id]);
            $db->prepare("DELETE FROM categories WHERE id=?")->execute([$id]);
            $msg = 'Category deleted.';
        } catch (PDOException $e) {
            $msg = 'Failed to delete category.';
            $msgType = 'error';
        }
    }

    // Add Subcategory
    elseif ($action === 'add_sub') {
        $cat_id = (int)$_POST['category_id'];
        $name   = trim($_POST['name'] ?? '');
        $slug   = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));
        if ($name && $cat_id) {
            try {
                $stmt = $db->prepare("INSERT INTO subcategories (category_id,name,slug) VALUES (?,?,?)");
                $stmt->execute([$cat_id, $name, $slug]);
                $msg = 'Subcategory added!';
            } catch (PDOException $e) {
                $msg = 'Failed to add subcategory. Name may already exist.';
                $msgType = 'error';
            }
        } else { $msg = 'All fields required.'; $msgType = 'error'; }
    }

    // Delete Subcategory
    elseif ($action === 'delete_sub') {
        $id = (int)$_POST['id'];
        try {
            $db->prepare("DELETE FROM products WHERE subcategory_id=?")->execute([$id]);
            $db->prepare("DELETE FROM subcategories WHERE id=?")->execute([$id]);
            $msg = 'Subcategory deleted.';
        } catch (PDOException $e) {
            $msg = 'Failed to delete subcategory.';
            $msgType = 'error';
        }
    }
}

$categories = $db->query("SELECT * FROM categories ORDER BY sort_order, name")->fetchAll();
foreach ($categories as &$cat) {
    $stmt = $db->prepare("SELECT * FROM subcategories WHERE category_id=? ORDER BY name");
    $stmt->execute([$cat['id']]);
    $cat['subs'] = $stmt->fetchAll();
}

require 'header.php';
?>

<?php if ($msg): ?>
<div class="admin-alert <?= $msgType === 'error' ? 'admin-alert-error' : 'admin-alert-success' ?>">
  <i class="bi bi-<?= $msgType === 'error' ? 'x-circle' : 'check-circle' ?>-fill me-2"></i><?= htmlspecialchars($msg) ?>
</div>
<?php endif; ?>

<div class="row g-4">
  <!-- Add Category -->
  <div class="col-lg-5">
    <div class="admin-card">
      <div class="admin-card-header"><i class="bi bi-plus-circle me-2"></i>Add New Category</div>
      <form method="POST" enctype="multipart/form-data" class="admin-form">
        <input type="hidden" name="action" value="add_category"/>
        <div class="form-group-admin">
          <label>Category Name <span class="req">*</span></label>
          <input type="text" name="name" class="admin-input" placeholder="e.g. Glass Bangles" required/>
        </div>
        <div class="form-group-admin">
          <label>Category Image (optional)</label>
          <input type="file" name="image" class="admin-input" accept="image/*"/>
        </div>
        <button type="submit" class="btn-admin-primary"><i class="bi bi-plus me-1"></i>Add Category</button>
      </form>
    </div>

    <div class="admin-card mt-4">
      <div class="admin-card-header"><i class="bi bi-diagram-3 me-2"></i>Add Subcategory</div>
      <form method="POST" class="admin-form">
        <input type="hidden" name="action" value="add_sub"/>
        <div class="form-group-admin">
          <label>Parent Category <span class="req">*</span></label>
          <select name="category_id" class="admin-input" required>
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group-admin">
          <label>Subcategory Name <span class="req">*</span></label>
          <input type="text" name="name" class="admin-input" placeholder="e.g. Stone Bangles" required/>
        </div>
        <button type="submit" class="btn-admin-primary"><i class="bi bi-plus me-1"></i>Add Subcategory</button>
      </form>
    </div>
  </div>

  <!-- Categories List -->
  <div class="col-lg-7">
    <div class="admin-card">
      <div class="admin-card-header"><i class="bi bi-list-ul me-2"></i>All Categories</div>
      <?php if (empty($categories)): ?>
      <p style="color:#888;padding:20px;text-align:center;">No categories yet. Add one!</p>
      <?php else: ?>
      <div class="admin-table-responsive">
      <div class="cat-list">
        <?php foreach ($categories as $cat): ?>
        <div class="cat-item">
          <div class="cat-item-header">
            <div class="cat-item-name">
              <?php if ($cat['image']): ?>
              <img src="../uploads/<?= htmlspecialchars($cat['image']) ?>" alt="" class="cat-thumb"/>
              <?php endif; ?>
              <strong><?= htmlspecialchars($cat['name']) ?></strong>
              <span class="badge-count"><?= count($cat['subs']) ?> subs</span>
            </div>
            <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this category and all its subcategories?')">
              <input type="hidden" name="action" value="delete_category"/>
              <input type="hidden" name="id" value="<?= $cat['id'] ?>"/>
              <button type="submit" class="btn-admin-danger-sm"><i class="bi bi-trash"></i></button>
            </form>
          </div>
          <?php if ($cat['subs']): ?>
          <div class="sub-list">
            <?php foreach ($cat['subs'] as $sub): ?>
            <div class="sub-item">
              <span><i class="bi bi-chevron-right me-1"></i><?= htmlspecialchars($sub['name']) ?></span>
              <form method="POST" style="display:inline;" onsubmit="return confirm('Delete subcategory?')">
                <input type="hidden" name="action" value="delete_sub"/>
                <input type="hidden" name="id" value="<?= $sub['id'] ?>"/>
                <button type="submit" class="btn-admin-danger-sm"><i class="bi bi-trash"></i></button>
              </form>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require 'footer.php'; ?>
