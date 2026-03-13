<?php
$pageTitle = 'Settings';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/db.php';
$db = getDB();
$msg = ''; $msgType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = ['whatsapp_number','shop_name','shop_address','shop_phone','shop_email','shop_hours', 'shop_ticker'];
    foreach ($fields as $key) {
        $val = trim($_POST[$key] ?? '');
        $stmt = $db->prepare("INSERT INTO settings (setting_key,setting_value) VALUES (?,?) ON DUPLICATE KEY UPDATE setting_value=?");
        $stmt->execute([$key, $val, $val]);
    }
    $msg = 'Settings saved successfully!';
}

$settings = [];
$rows = $db->query("SELECT setting_key,setting_value FROM settings")->fetchAll();
foreach ($rows as $r) { $settings[$r['setting_key']] = $r['setting_value']; }

function sv($settings, $key, $default='') {
    return htmlspecialchars($settings[$key] ?? $default);
}

require 'header.php';
?>

<?php if ($msg): ?>
<div class="admin-alert admin-alert-success">
  <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($msg) ?>
</div>
<?php endif; ?>

<div class="row g-4">
  <div class="col-lg-7">
    <div class="admin-card">
      <div class="admin-card-header"><i class="bi bi-gear me-2"></i>Shop Settings</div>
      <form method="POST" class="admin-form">
        <div class="form-group-admin">
          <label><i class="bi bi-whatsapp me-1" style="color:#25D366;"></i>WhatsApp Number <span class="req">*</span></label>
          <input type="text" name="whatsapp_number" class="admin-input" value="<?= sv($settings,'whatsapp_number','919994264756') ?>" placeholder="919994264756" required/>
          <small style="color:#888;">Include country code, no + or spaces. Example: 919994264756</small>
        </div>
        <div class="form-group-admin">
          <label>Shop Name</label>
          <input type="text" name="shop_name" class="admin-input" value="<?= sv($settings,'shop_name','MILIR BANGLES') ?>"/>
        </div>
        <div class="form-group-admin">
          <label>Shop Address</label>
          <textarea name="shop_address" class="admin-input" rows="2"><?= sv($settings,'shop_address') ?></textarea>
        </div>
        <div class="row g-2">
          <div class="col-6 form-group-admin">
            <label>Phone</label>
            <input type="text" name="shop_phone" class="admin-input" value="<?= sv($settings,'shop_phone') ?>"/>
          </div>
          <div class="col-6 form-group-admin">
            <label>Email</label>
            <input type="email" name="shop_email" class="admin-input" value="<?= sv($settings,'shop_email') ?>"/>
          </div>
        </div>
        <div class="form-group-admin">
          <label>Business Hours</label>
          <input type="text" name="shop_hours" class="admin-input" value="<?= sv($settings,'shop_hours','Mon-Sat: 10 AM – 8 PM') ?>"/>
        </div>
        <div class="form-group-admin">
          <label><i class="bi bi-megaphone me-1" style="color:var(--gold);"></i> Scrolling Ticker Update ("Running Train")</label>
          <input type="text" name="shop_ticker" class="admin-input" value="<?= sv($settings,'shop_ticker','New Collection 2026 is now live! Discover the latest sparkling bangles designed to add beauty and shine to your style.') ?>" placeholder="Enter running update text..."/>
          <small style="color:#888;">This text will scroll horizontally at the top of the homepage.</small>
        </div>
        <button type="submit" class="btn-admin-primary"><i class="bi bi-save me-1"></i>Save Settings</button>
      </form>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="admin-card">
      <div class="admin-card-header"><i class="bi bi-whatsapp me-2" style="color:#25D366;"></i>WhatsApp Order Flow</div>
      <div style="padding:16px;color:#ccc;font-size:0.9rem;line-height:1.7;">
        <p>When a customer clicks <strong style="color:#c9a84c;">"Order via WhatsApp"</strong> on the cart page, they will be redirected to WhatsApp with a pre-filled message containing:</p>
        <ul style="margin:8px 0 0 0;padding-left:20px;">
          <li>Order items list</li>
          <li>Sizes selected</li>
          <li>Quantities</li>
          <li>Total price</li>
        </ul>
        <p style="margin-top:12px;">The message is sent to your WhatsApp number above. You then contact the customer to confirm and arrange payment/delivery.</p>
        <div style="background:#0a0a0a;border:1px solid #c9a84c44;border-radius:8px;padding:12px;margin-top:12px;">
          <div style="color:#c9a84c;font-weight:600;margin-bottom:6px;">Current WhatsApp</div>
          <div style="font-size:1.1rem;">+<?= sv($settings,'whatsapp_number','919994264756') ?></div>
        </div>
      </div>
    </div>

    <div class="admin-card mt-3">
      <div class="admin-card-header"><i class="bi bi-shield-lock me-2"></i>Change Admin Password</div>
      <form method="POST" action="change_password.php" class="admin-form">
        <div class="form-group-admin">
          <label>Current Password</label>
          <input type="password" name="current_pass" class="admin-input" placeholder="Current password"/>
        </div>
        <div class="form-group-admin">
          <label>New Password</label>
          <input type="password" name="new_pass" class="admin-input" placeholder="New password (min 6 chars)"/>
        </div>
        <button type="submit" class="btn-admin-primary"><i class="bi bi-key me-1"></i>Change Password</button>
      </form>
    </div>
  </div>
</div>

<?php require 'footer.php'; ?>
