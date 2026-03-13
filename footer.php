<?php
// Shared footer — included by all frontend pages
// $waNum and $shopName must be set before including this
$waNum    = $waNum    ?? '919994264756';
$shopName = $shopName ?? 'MILIR BANGLES';
?>
<footer class="site-footer">
  <div class="container">
    <div class="row gy-5">
      <!-- Brand -->
      <div class="col-lg-4">
        <div class="d-flex align-items-center gap-3 mb-3">
          <img src="<?= str_repeat('../', $depth??0) ?>assets/images/logo.jpeg" alt="Logo" class="footer-logo-img"/>
          <div>
            <div class="footer-brand-name"><?= htmlspecialchars($shopName) ?></div>
            <div class="footer-brand-sub">மிளிர்</div>
          </div>
        </div>
        <p class="footer-desc">Every woman deserves to shine. Let your hands glitter with Milir Bangles.</p>
        <div class="footer-social">
          <a href="https://wa.me/<?= $waNum ?>" target="_blank" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
          <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
          <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
          <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
        </div>
      </div>
      <!-- Quick Links -->
      <div class="col-sm-6 col-lg-2">
        <div class="footer-heading">Quick Links</div>
        <ul class="footer-links">
          <li><a href="<?= str_repeat('../', $depth??0) ?>index.php"><i class="bi bi-chevron-right"></i> Home</a></li>
          <li><a href="<?= str_repeat('../', $depth??0) ?>categories.php"><i class="bi bi-chevron-right"></i> Categories</a></li>
          <li><a href="<?= str_repeat('../', $depth??0) ?>cart.php"><i class="bi bi-chevron-right"></i> Cart</a></li>
          <li><a href="<?= str_repeat('../', $depth??0) ?>about.php"><i class="bi bi-chevron-right"></i> About Us</a></li>
          <li><a href="<?= str_repeat('../', $depth??0) ?>contact.php"><i class="bi bi-chevron-right"></i> Contact</a></li>
        </ul>
      </div>
      <!-- Contact -->
      <div class="col-lg-3">
        <div class="footer-heading">Contact Us</div>
        <ul class="footer-links">
          <li><a href="tel:+91<?= $waNum ?>"><i class="bi bi-telephone"></i> +91 <?= $waNum ?></a></li>
          <li><a href="mailto:milirbangles@gmail.com"><i class="bi bi-envelope"></i> milirbangles@gmail.com</a></li>
          <li><a href="contact.php"><i class="bi bi-geo-alt"></i> 123, Fashion Street, Chennai, TN</a></li>
          <li style="color:var(--text-muted);font-size:0.82rem;padding:4px 0;"><i class="bi bi-clock me-1"></i> Mon–Sat: 10 AM – 8 PM</li>
        </ul>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container">
      <p class="mb-0">&copy; 2026 <span><?= htmlspecialchars($shopName) ?></span>. All rights reserved. Made with <span>♥</span> for every man.</p>
    </div>
  </div>
</footer>

<!-- Floating WhatsApp -->
<a href="https://wa.me/<?= $waNum ?>" class="whatsapp-float" target="_blank" aria-label="Chat on WhatsApp" rel="noopener">
  <i class="bi bi-whatsapp"></i>
</a>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>
