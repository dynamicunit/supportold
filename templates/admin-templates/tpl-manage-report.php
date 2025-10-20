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
                <h1 class="text-center"><?= $data ? 'Update Report' : 'Add Report' ?> </h1>
                <div class="col-md-6 offset-md-3 p-4">
                    <div class="card">
                        <div class="card-body">
                            <?= $view->page_messages($validation_errors) ?>
                            <form action="<?= $canonical ?>" id="workshop-form" method="post" novalidate>
                                <div class="form-group">
                                    <label for="name" class="form-label">Report Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="<?= $data->report_name ?? '' ?>" required minlength="5" maxlength="50">
                                </div>
                                <div class=<div class="form-group">
                                    <label for="system_name" class="form-label">System Name</label>
                                    <select name="system_name" id="system_name" class="form-select" required>
                                        <option disabled selected>--Select--</option>
                                        <option value="Infor" <?= $data && $data->system_name === 'Infor' ? 'selected' : '' ?>>Infor
                                        </option>
                                        <option value="Navision" <?= $data && $data->system_name === 'Navision' ? 'selected' : '' ?>>
                                            Navision</option>
                                        <option value="Business Central" <?= $data && $data->system_name === 'Business Central' ? 'selected' : '' ?>>Business Central</option>
                                        <option value="SAP" <?= $data && $data->system_name === 'SAP' ? 'selected' : '' ?>>
                                            SAP
                                        </option>
                                        <option value="Oracle" <?= $data && $data->system_name === 'Oracle' ? 'selected' : '' ?>>
                                            Oracle
                                        </option>
                                        <option value="Others" <?= $data && $data->system_name === 'Others' ? 'selected' : '' ?>>
                                            Others
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" id="description" class="form-control" required
                                        minlength="10" maxlength="500" rows="3"><?= $data->description ?? '' ?></textarea>
                                </div>
                                <div class=<div class="form-group">
                                    <label for="url" class="form-label">Report URL</label>
                                    <input type="text" name="url" id="url" class="form-control" required minlength="5"
                                        maxlength="100" value="<?= $data->report_url ?? '' ?>">
                                </div>
                                <div class="form-group">
                                    <label for="columns" class="form-label">Columns</label>
                                    <textarea name="columns" id="columns" class="form-control" required minlength="10"
                                        maxlength="500" rows="3"><?= $data->columns ?? '' ?></textarea>
                                </div>

                                <div>
                                    <input type="hidden" name="token" value="<?= token::generate() ?>">
                                    <input type="hidden" name="report_id" value="<?= $data->id ?? '' ?>">
                                    <button type="submit" class="btn btn-secondary" id="submit-btn"><span
                                            class="spinner-border spinner-border-sm d-none"
                                            aria-hidden="true"></span><?= $data ? 'Update' : 'Submit' ?></button>
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
            const form = document.getElementById('workshop-form');
            if (form) {
                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                    if (!validateForm(form)) {
                        return;
                    }

                    const submitButton = form.querySelector('#submit-btn');
                    const spinner = submitButton.querySelector('.spinner-border');

                    submitButton.disabled = true;
                    spinner.classList.remove('d-none');
                    form.submit();

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