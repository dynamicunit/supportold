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
                <h1 class="text-center"><?= $data ? 'Update Lookup Code' : 'Add Lookup Code' ?> </h1>
                <div class="col-md-6 offset-md-3 p-4">
                    <div class="card">
                        <div class="card-body">
                            <?= $view->page_messages($validation_errors) ?>
                            <form action="<?= $canonical ?>" id="lookup-form" method="post" novalidate>
                                <div class="row">
                                    <div class="form-group">
                                        <label for="entity" class="form-label">Entity</label>
                                        <select name="entity" id="entity" class="form-control" required>
                                            <?= $cbo_entity ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="description" class="form-label">Description</label>
                                        <input type="text" name="description" id="description"
                                            value="<?= $data->description ?? '' ?>" class="form-control" required
                                            minlength="3" maxlength="20">
                                    </div>

                                    <div class="form-group">
                                        <label for="short-description" class="form-label">Short Description</label>
                                        <input type="text" name="short-description" id="short-description"
                                            value="<?= $data->short_desc ?? '' ?>" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="slug" class="form-label">slug</label>
                                        <input type="text" name="slug" id="slug" value="<?= $data->slug ?? '' ?>"
                                            class="form-control">
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="approve">Approval Status</label>
                                        <select name="approve" id="approve" class="form-select">
                                            <option selected disabled>--select--</option>
                                            <option value="0" <?= $data && $data->is_active == 0 ? 'selected' : '' ?>>
                                                Reject</option>
                                            <option value="1" <?= $data && $data->is_active == 1 ? 'selected' : '' ?>>
                                                Accept</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <input type="hidden" name="token" value="<?php echo token::generate(); ?>">
                                    <input type="hidden" name="lookup_id" value="<?= $data->id ?? '' ?>">
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