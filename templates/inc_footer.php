<div class="container">
    <footer class="py-3 my-4">
        <ul class="nav justify-content-center border-bottom pb-3 mb-3">
            <li class="nav-item"><a href="<?= $baseurl ?>" class="nav-link px-2 text-body-secondary">Home</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">Features</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">Pricing</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">FAQs</a></li>
            <li class="nav-item"><a href="<?= $baseurl ?>/about" class="nav-link px-2 text-body-secondary">About</a>
            </li>
        </ul>
        <p class="text-center text-body-secondary">© 2025 Dynamic Unit FZE LLC.</p>
    </footer>
</div>
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