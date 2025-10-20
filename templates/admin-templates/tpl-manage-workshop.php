<?php include_once(__DIR__ . '/../inc_head.php') ?>
<link rel="stylesheet" href="<?= $baseurl ?>/templates/css/choices.min.css">
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
                <h1 class="text-center"><?= $data ? 'Update Workshop' : 'Add Workshop' ?> </h1>
                <div class="col-md-6 offset-md-3 p-4">
                    <div class="card">
                        <div class="card-body">
                            <?= $view->page_messages($validation_errors) ?>
                            <form action="<?= $canonical ?>" id="workshop-form" method="post" novalidate>
                                <div class="form-group">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" name="title" id="title" class="form-control"
                                        value="<?= $data->title ?? '' ?>" required minlength="5" maxlength="50">
                                </div>
                                <div class="form-group">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" id="description" class="form-control" required
                                        minlength="10" maxlength="500" rows="3"><?= $data->description ?? '' ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" name="date" id="date" class="form-control" required
                                        value="<?= $data->date ?? '' ?>">
                                </div>
                                <div class="form-group">
                                    <label for="venue" class="form-label">Venue</label>
                                    <input type="text" name="venue" id="venue" class="form-control" required
                                        minlength="5" maxlength="100" value="<?= $data->venue ?? '' ?>">
                                </div>
                                <div class="form-group">
                                    <label for="status" class="form-label">Status</label>
                                    <div class="form-check d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="radio" name="status" id="status1"
                                            value="Upcoming" <?= $data && $data->status === 'Upcoming' ? 'checked' : 'checked' ?>>
                                        <label class="form-check-label" for="status1">
                                            Upcoming
                                        </label>
                                    </div>
                                    <div class="form-check d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="radio" name="status" id="status2"
                                            value="Completed" <?= $data && $data->status === 'Completed' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="status2">
                                            Completed
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="users" class="form-label">Add Users</label>
                                    <select name="users[]" id="users" class="form-select" multiple>
                                        <?php
                                        foreach ($cbo_users as $key => $user) {
                                            $isSelected = in_array($user->id, $selected_user_ids) ? 'selected' : '';
                                            ?>
                                                <option value="<?= $user->id ?>" <?= $isSelected ?>><?= $user->full_name ?>
                                                </option>
                                        <?php }
                                        ?>
                                    </select>
                                </div>
                                <div>
                                    <input type="hidden" name="token" value="<?= token::generate() ?>">
                                    <input type="hidden" name="workshop_id" value="<?= $data->id ?? '' ?>">
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
    <script src="<?= $baseurl ?>/templates/js/choices.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            new Choices("#users", {
                removeItemButton: true,
                placeholder: true,
                placeholderValue: "--select--",
                searchEnabled: true,
            });

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