<?php
$pageTitle = 'Manage Posters';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/db.php';
$db  = getDB();
$msg = ''; $msgType = 'success';

// --- Handle Add / Edit / Delete ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_poster' || $action === 'edit_poster') {
        $id          = (int)($_POST['id'] ?? 0);
        $title       = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $link        = trim($_POST['link'] ?? '');
        $is_active   = isset($_POST['is_active']) ? 1 : 0;
        $is_popup    = isset($_POST['is_popup']) ? 1 : 0;
        $image       = trim($_POST['old_image'] ?? '');

        if ($is_popup) {
            // Unset other popups first
            $db->exec("UPDATE posters SET is_popup = 0");
        }

        if (!empty($_FILES['image']['name'])) {
            $ext   = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $fname = 'poster_' . time() . '_' . rand(100,999) . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../uploads/' . $fname)) {
                // Delete old image if exists
                if ($image && file_exists(__DIR__ . '/../uploads/' . $image)) {
                    @unlink(__DIR__ . '/../uploads/' . $image);
                }
                $image = $fname;
            }
        }

        if (!$title || (!$image && $action === 'add_poster')) {
            $msg = 'Title and Image are required.'; $msgType = 'error';
        } else {
            if ($action === 'add_poster') {
                $stmt = $db->prepare("INSERT INTO posters (title, description, image, link, is_active, is_popup) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $description, $image, $link, $is_active, $is_popup]);
                $msg = 'Poster added!';
            } else {
                $stmt = $db->prepare("UPDATE posters SET title=?, description=?, image=?, link=?, is_active=?, is_popup=? WHERE id=?");
                $stmt->execute([$title, $description, $image, $link, $is_active, $is_popup, $id]);
                $msg = 'Poster updated!';
            }
        }
    } elseif ($action === 'delete_poster') {
        $id = (int)$_POST['id'];
        $stmt = $db->prepare("SELECT image FROM posters WHERE id=?");
        $stmt->execute([$id]);
        $poster = $stmt->fetch();
        if ($poster && $poster['image']) {
            $imgPath = __DIR__ . '/../uploads/' . $poster['image'];
            if (file_exists($imgPath)) @unlink($imgPath);
        }
        $db->prepare("DELETE FROM posters WHERE id=?")->execute([$id]);
        $msg = 'Poster deleted.';
    }
}

// Edit mode
$editPoster = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM posters WHERE id=?");
    $stmt->execute([(int)$_GET['edit']]);
    $editPoster = $stmt->fetch();
}

$posters = $db->query("SELECT * FROM posters ORDER BY created_at DESC")->fetchAll();

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
        <i class="bi bi-<?= $editPoster?'pencil':'plus-circle' ?> me-2"></i>
        <?= $editPoster ? 'Edit Poster' : 'Add New Poster' ?>
        <?php if ($editPoster): ?><a href="posters.php" class="btn-admin-sm ms-auto">Cancel</a><?php endif; ?>
      </div>
      <form method="POST" enctype="multipart/form-data" class="admin-form">
        <input type="hidden" name="action" value="<?= $editPoster ? 'edit_poster' : 'add_poster' ?>"/>
        <?php if ($editPoster): ?><input type="hidden" name="id" value="<?= $editPoster['id'] ?>"/><?php endif; ?>
        <input type="hidden" name="old_image" value="<?= htmlspecialchars($editPoster['image']??'') ?>"/>

        <div class="form-group-admin">
          <label>Poster Title <span class="req">*</span></label>
          <input type="text" name="title" class="admin-input" value="<?= htmlspecialchars($editPoster['title']??'') ?>" placeholder="e.g. Summer Sale 2026" required/>
        </div>
        <div class="form-group-admin">
          <label>Description</label>
          <textarea name="description" class="admin-input" rows="3" placeholder="Brief tagline or details..."><?= htmlspecialchars($editPoster['description']??'') ?></textarea>
        </div>
        <div class="form-group-admin">
          <label>Link URL</label>
          <input type="text" name="link" class="admin-input" value="<?= htmlspecialchars($editPoster['link']??'') ?>" placeholder="e.g. products.php?category_id=1"/>
          <small class="text-muted" style="font-size: 0.75rem;">Where should clicking the poster lead? (Optional)</small>
        </div>
        <div class="form-group-admin">
          <label>Poster Image <span class="req">*</span></label>
          <?php if ($editPoster && $editPoster['image']): ?>
          <div class="current-img-preview">
            <img src="../uploads/<?= htmlspecialchars($editPoster['image']) ?>" alt="Current" style="max-width: 100px; border-radius: 8px; border: 1px solid var(--gold); margin-bottom: 8px;"/>
            <small class="d-block">Current image</small>
          </div>
          <?php endif; ?>
          <input type="file" name="image" class="admin-input" accept="image/*" <?= $editPoster?'':'required' ?>/>
        </div>
        <div class="form-group-admin">
          <label class="size-check" style="margin-bottom:8px;">
            <input type="checkbox" name="is_active" <?= ($editPoster['is_active']??1)?'checked':'' ?>/> Active / Show in Carousel
          </label>
          <label class="size-check">
            <input type="checkbox" name="is_popup" <?= ($editPoster['is_popup']??0)?'checked':'' ?>/> <strong>Show as Homepage Popup</strong>
          </label>
        </div>
        <button type="submit" class="btn-admin-primary">
          <i class="bi bi-<?= $editPoster?'check2-circle':'plus' ?> me-1"></i>
          <?= $editPoster ? 'Update Poster' : 'Add Poster' ?>
        </button>
      </form>
    </div>
  </div>

  <!-- Posters List -->
  <div class="col-lg-8">
    <div class="admin-card">
      <div class="admin-card-header">
        <i class="bi bi-megaphone me-2"></i>Active Posters
        <span class="ms-auto badge-count"><?= count($posters) ?> total</span>
      </div>
      <div class="admin-table-responsive">
        <table class="admin-table">
          <thead><tr><th>Preview</th><th>Title</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead>
          <tbody>
            <?php if (empty($posters)): ?>
            <tr><td colspan="5" style="text-align:center;color:#888;padding:24px;">No posters yet. Create an advertisement!</td></tr>
            <?php else: ?>
            <?php foreach ($posters as $p): ?>
            <tr>
              <td>
                <img src="../uploads/<?= htmlspecialchars($p['image']) ?>" alt="" style="width:80px;height:45px;object-fit:cover;border-radius:6px;border:1px solid #c9a84c22;"/>
              </td>
              <td>
                <div style="font-weight:700;"><?= htmlspecialchars($p['title']) ?></div>
                <div style="font-size:0.75rem;color:#888;"><?= htmlspecialchars(substr($p['description'], 0, 40)) ?>...</div>
              </td>
              <td>
                <span class="badge-cat" style="background:<?= $p['is_active']?'#2ecc7122':'#e74c3c22' ?>;color:<?= $p['is_active']?'#2ecc71':'#e74c3c' ?>;">
                  <?= $p['is_active']?'Active':'Inactive' ?>
                </span>
                <?php if ($p['is_popup']): ?>
                <span class="badge-cat" style="background:#f1c40f22;color:#f1c40f;margin-left:5px;">
                  Popup
                </span>
                <?php endif; ?>
              </td>
              <td style="font-size:0.75rem;color:#999;"><?= date('M d, Y', strtotime($p['created_at'])) ?></td>
              <td>
                <a href="?edit=<?= $p['id'] ?>" class="btn-admin-sm">Edit</a>
                <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this poster?')">
                  <input type="hidden" name="action" value="delete_poster"/>
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

<?php require 'footer.php'; ?>
