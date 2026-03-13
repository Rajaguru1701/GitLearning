// =====================================================
// Sakthi Clothes — Main JavaScript
// =====================================================

document.addEventListener('DOMContentLoaded', function () {
  // Load cart count on all pages
  loadCartCount();

  // Scroll animations
  initScrollAnimations();
});

// =====================================================
// CART UTILITIES
// =====================================================
function loadCartCount() {
  fetch('api/cart.php?action=get')
    .then(r => r.json())
    .then(res => {
      if (res.success) updateCartBadge(res.cart_count);
    })
    .catch(() => { }); // gracefully fail if not on PHP server
}

function updateCartBadge(count) {
  const badge = document.getElementById('navCartCount');
  if (!badge) return;
  if (count > 0) {
    badge.textContent = count;
    badge.style.display = 'flex';
  } else {
    badge.style.display = 'none';
  }
}

// =====================================================
// TOAST
// =====================================================
function showToast(message, duration = 2500) {
  const container = document.getElementById('toastContainer');
  if (!container) return;
  const toast = document.createElement('div');
  toast.className = 'toast-msg';
  toast.innerHTML = message;
  container.appendChild(toast);
  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transition = 'opacity 0.4s';
    setTimeout(() => toast.remove(), 400);
  }, duration);
}

// =====================================================
// SCROLL ANIMATIONS
// =====================================================
function initScrollAnimations() {
  const els = document.querySelectorAll('.fade-up, .reveal, .reveal-3d, [data-reveal]');
  if (!els.length) return;

  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -80px 0px'
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        // Add a slight stagger if needed
        const delay = entry.target.dataset.delay || 0;
        setTimeout(() => {
          entry.target.classList.add('revealed');
          entry.target.classList.add('visible'); // keep compatibility if needed
        }, delay);
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  els.forEach(el => observer.observe(el));
}

// =====================================================
// NAVBAR SCROLL EFFECT
// =====================================================
window.addEventListener('scroll', () => {
  const nav = document.getElementById('mainNavbar');
  if (!nav) return;
  if (window.scrollY > 50) {
    nav.style.boxShadow = '0 4px 30px rgba(0,0,0,0.5)';
  } else {
    nav.style.boxShadow = 'none';
  }
});
