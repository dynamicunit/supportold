    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script>
        feather.replace();

        const sidebar = document.getElementById("sidebar");
        const toggle = document.getElementById("sidebarToggle");
        const overlay = document.getElementById("sidebarOverlay");

        // Only activate toggle for mobile
        if (toggle) {
            toggle.addEventListener("click", () => {
                const isOpen = sidebar.classList.toggle("show");
                overlay.classList.toggle("show", isOpen);
            });

            overlay.addEventListener("click", () => {
                sidebar.classList.remove("show");
                overlay.classList.remove("show");
            });
        }
    </script>