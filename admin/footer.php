  </div><!-- /.admin-content -->
</div><!-- /.admin-main -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/admin.js"></script>
<script>
function toggleSidebar() {
  const sidebar = document.getElementById('adminSidebar');
  const main = document.getElementById('adminMain');
  const backdrop = document.getElementById('sidebarBackdrop');
  
  if (window.innerWidth <= 768) {
    sidebar.classList.toggle('mobile-open');
    backdrop.classList.toggle('active');
    // Prevent scrolling when sidebar is open on mobile
    document.body.style.overflow = sidebar.classList.contains('mobile-open') ? 'hidden' : '';
  } else {
    sidebar.classList.toggle('collapsed');
    main.classList.toggle('expanded');
  }
}
</script>
</body>
</html>
