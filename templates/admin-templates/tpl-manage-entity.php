<?php include_once(__DIR__ . '/../inc_head.php') ?>

<style>
    .form-group {
        margin-bottom: 13px;
    }
</style>

</head>

<body>
    <?php include_once(__DIR__ . '/../inc_header.php') ?>

    <section id="data" class="">
        <div class="container my-5">

            <div class="row ">
                <h1 class="text-center"><?= $data ? 'Update Entity' : 'Add Entity' ?> </h1>
                <div class="col-md-6 offset-md-3 p-4">
                    <div class="card">
                        <div class="card-body">
                            <?= $view->page_messages($validation_errors) ?>
                            <form action="<?= $canonical ?>" id="lookup-form" method="post" novalidate>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Entity Name</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            value="<?= $data->name ?? '' ?>" required minlength="3" maxlength="100">
                                    </div>
                                    <div class="form-group">
                                        <label for="description" class="form-label">Description</label>
                                        <input type="text" name="description" id="description"
                                            value="<?= $data->description ?? '' ?>" class="form-control" required
                                            minlength="3" maxlength="100">
                                    </div>

                                </div>
                                <div>
                                    <input type="hidden" name="token" value="<?php echo token::generate(); ?>">
                                    <input type="hidden" name="entity_id" value="<?= $data->id ?? '' ?>">
                                    <button type="submit"
                                        class="btn btn-secondary"><?= $data ? 'Update' : 'Submit' ?></button>
                                </div>
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
            const form = document.getElementById('lookup-form');
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