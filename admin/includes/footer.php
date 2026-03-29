    </div><!-- /.content-area -->
</div><!-- /#main-content -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Đóng sidebar khi click bên ngoài (mobile)
document.addEventListener('click', function(e) {
    const sidebar = document.getElementById('sidebar');
    const toggle = document.querySelector('.sidebar-toggle');
    if (window.innerWidth <= 768 && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
        sidebar.classList.remove('open');
    }
});

// Confirm xóa
document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', function(e) {
        if (!confirm(this.dataset.confirm || 'Bạn có chắc chắn muốn xóa?')) e.preventDefault();
    });
});
</script>
<?= isset($extra_js) ? $extra_js : '' ?>
</body>
</html>
