<?php include_once('inc_head.php') ?>
</head>

<body>
    <?php include_once('inc_header.php') ?>

    <section class="my-5">
        <div class="container d-flex justify-content-center align-items-center">
            <div class="card shadow-lg p-4 rounded" style="max-width: 400px; width: 100%;">
                <div class="card-body">
                    <!-- Page Title -->
                    <h1 class="text-center mb-4">Forgot Password</h1>

                    <?= $view->page_messages() ?>

                    <!-- Forgot Password Form -->
                    <form id="fget-form" method="post" action="<?= $canonical ?>" novalidate>
                        <!-- Email Input -->
                        <div class="form-group position-relative">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Enter your email" required>
                            </div>
                        </div>

                        <!-- Hidden Token -->
                        <input type="hidden" name="token" value="<?php echo token::generate(); ?>">

                        <!-- Retrieve Password Button -->
                        <div class="d-grid mt-4">
                            <button type="submit" id="submit" name="submit" class="btn btn-primary">
                                Retrieve Password
                            </button>
                        </div>
                    </form>

                    <!-- Divider -->
                    <hr class="my-4">

                    <!-- Sign-in Link -->
                    <div class="text-center">
                        <a href="<?= $baseurl ?>/sign-in" class="text-muted">Back to Sign-in</a>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <?php include_once('inc_footer.php') ?>
    <script src="<?= $baseurl ?>/templates/js/validate.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('fget-form');
            if (form) {
                form.addEventListener('submit', (event) => {
                    if (!validateForm(form)) {
                        event.preventDefault();
                    } else {
                        form.submit();
                    }
                });

                const inputs = form.querySelectorAll('input, textarea, select, file');
                inputs.forEach(input => {
                    input.addEventListener('blur', () => {
                        validateInput(input);
                    });
                });
            }
        });
    </script>

</body>

</html>