<?php include_once(__DIR__ . '/../inc_head.php') ?>
</head>

<body>
    <?php include_once(__DIR__ . '/../inc_header.php') ?>

    <section class="my-5">
        <div class="container">
            <div class="row">
                <div class="col align-self-center">
                    <h1 class="text-center">Forgot Password</h1>
                    <div class="card col-md-6 offset-md-3 my-4 py-2">
                        <div class="card-body">
                            <form id="fget-form" method="post" action="<?= $canonical ?>" novalidate>
                                <?= $view->page_messages() ?>
                                <div class="form-group">
                                    <label for="email" class="form-label">Email address</label> <input type="email"
                                        class="form-control" id="email" name="email" placeholder=""
                                        value="<?= $user_data->email ?? '' ?>" required disabled>
                                </div>

                                <input type="hidden" name="token" value="<?php echo token::generate(); ?>"> <input
                                    type="submit" id="submit" name="submit" value="Retrieve Password"
                                    class="btn btn-primary px-4 mt-3">

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include_once(__DIR__ . '/../inc_footer.php') ?>
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