<?php include_once(__DIR__ . '/../inc_head.php') ?>
<style>
    .form-group {
        margin-bottom: 13px;
    }
</style>
</head>

<body>
    <main class="page-wrapper">
        <?php include_once(__DIR__ . '/../inc_header.php') ?>

        <section id="data" class="container my-5">

            <div class="row">

                <h1 class="text-center"> <?= $data ? "Update Category" : "Add Category" ?> </h1>

                <?= $view->page_messages() ?>
                <?= $view->page_messages($validation_errors) ?>

                <div class="col-md-6 offset-md-3 border rounded p-4 bg-white mt-3">

                    <form action="<?= $canonical ?>" id="cat-form" method="post" novalidate>
                        <div class="form-group">
                            <div class="form-group">
                                <label for="name" class="form-label">Category Description</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="<?= $data->description ?? '' ?>" required minlength="3" maxlength="50">
                            </div>
                            <div class="form-group">
                                <label for="slug" class="form-label">Category Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control"
                                    value="<?= $data->slug ?? '' ?>" required minlength="3" maxlength="20">
                            </div>

                            <div>
                                <input type="hidden" name="cat_id" value="<?= $data->id ?? '' ?>">
                                <input type="hidden" name="token" value="<?php echo token::generate(); ?>">
                                <button type="submit" class="btn btn-secondary" type="submit">
                                    <?= $data ? "Update" : 'Submit' ?></button>
                            </div>
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
            const form = document.getElementById('cat-form');
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