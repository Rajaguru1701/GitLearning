// Admin Panel JavaScript
document.addEventListener('DOMContentLoaded', function () {
    // Auto-hide alerts after 4 seconds
    const alerts = document.querySelectorAll('.admin-alert');
    alerts.forEach(a => setTimeout(() => { a.style.opacity = '0'; a.style.transition = 'opacity 0.5s'; setTimeout(() => a.remove(), 500); }, 4000));

    // File input image preview labels
    const fileInputs = document.querySelectorAll('input[type=file]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function () {
            const label = this.previousElementSibling;
            if (label && label.tagName === 'LABEL' && this.files[0]) {
                // already handled inline
            }
        });
    });
});
