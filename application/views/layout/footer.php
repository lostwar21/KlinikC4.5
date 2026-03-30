    </div> <!-- end main-content -->

    <!-- JQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <?php if (isset($extra_js)) echo $extra_js; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Sidebar on Mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if (sidebarToggle && sidebar && overlay) {
            function toggleMenu() {
                sidebar.classList.toggle('show');
                if (sidebar.classList.contains('show')) {
                    overlay.style.display = 'block';
                    setTimeout(() => overlay.classList.add('show'), 10);
                } else {
                    overlay.classList.remove('show');
                    setTimeout(() => overlay.style.display = 'none', 300);
                }
            }

            sidebarToggle.addEventListener('click', toggleMenu);
            overlay.addEventListener('click', toggleMenu);
        }
    });
    </script>
</body>
</html>
