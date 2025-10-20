<?php include_once(__DIR__ . '/../inc_head.php') ?>
<style>
    .form-group {
        margin-bottom: 10px;
    }
</style>
</head>

<body>
    <main class="page-wrapper">
        <?php include_once(__DIR__ . '/../inc_header.php') ?>
        <section class="container my-5">
            <div class="row">
                <h1 class="text-center">Change Password</h1>

                <?= $view->page_messages() ?>
                <?= $view->page_messages($validation_errors) ?>

                <div class="col-md-6 offset-md-3 border rounded p-4 bg-white mt-3">

                    <form action="<?= $canonical ?>" id="change-password" method="post" enctype="multipart/form-data"
                        novalidate>
                        <div class="form-group">
                            <label for="old_password" class="form-label">Old Password</label>
                            <input type="password" name="old_password" id="old_password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" name="password" id="password" class="form-control" required
                                minlength="8" maxlength="20">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control">
                        </div>
                        <div>
                            <input type="hidden" name="token" value="<?php echo token::generate(); ?>">
                            <button type="submit" class="btn btn-secondary">Change Password</button>
                        </div>
                    </form>

                </div>
            </div>
        </section>
    </main>
    <?php include_once(__DIR__ . '/../inc_footer.php') ?>
    <script src="<?= $baseurl ?>/templates/js/validate.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('change-password');
            if (form) {
                form.addEventListener('submit', (event) => {
                    if (!validateForm(form)) {
                        event.preventDefault();
                    } else {
                        // event.preventDefault();
                        // submitFormViaAjax(form);
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