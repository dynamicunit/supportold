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
                            <form id="sla-form" class="row needs-validation" method="post" enctype="multipart/form-data"
                                action="<?= $canonical ?>" novalidate>

                                <h3 class="mb-2">SLA Details</h3>
                                <hr>
                                <div class="form-group">
                                    <label class="form-label fs-base" for="sla_name">SLA Name</label>
                                    <input class="form-control" type="text" id="sla_name" name="sla_name"
                                        placeholder="Enter SLA name" value="<?= $data->sla_name ?? '' ?>" required
                                        minlength="3" maxlength="150">
                                </div>

                                <div class="form-group">
                                    <label class="form-label fs-base" for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                        placeholder="Brief description" required minlength="5"
                                        maxlength="500"><?= $data->description ?? '' ?></textarea>
                                </div>

                                <h3 class="my-3">Service Timelines</h3>
                                <hr>
                                <div class="form-group">
                                    <label class="form-label fs-base" for="response_time_hours">Response Time
                                        (Hours)</label>
                                    <input class="form-control" type="number" id="response_time_hours"
                                        name="response_time_hours" placeholder="e.g. 8"
                                        value="<?= $data->response_time_hours ?? '' ?>" required minlength="1">
                                </div>

                                <div class="form-group">
                                    <label class="form-label fs-base" for="resolution_time_hours">Resolution Time
                                        (Hours)</label>
                                    <input class="form-control" type="number" id="resolution_time_hours"
                                        name="resolution_time_hours" placeholder="e.g. 48"
                                        value="<?= $data->resolution_time_hours ?? '' ?>" required minlength="1">
                                </div>

                                <div class="form-group">
                                    <label class="form-label fs-base" for="priority_level">Priority Level</label>
                                    <select class="form-select" id="priority_level" name="priority_level" required>
                                        <option value="" selected disabled>Select Priority</option>
                                        <option value="Low" <?= isset($data->priority_level) && $data->priority_level == 'Low' ? 'selected' : '' ?>>Low</option>
                                        <option value="Medium" <?= isset($data->priority_level) && $data->priority_level == 'Medium' ? 'selected' : '' ?>>Medium</option>
                                        <option value="High" <?= isset($data->priority_level) && $data->priority_level == 'High' ? 'selected' : '' ?>>High</option>
                                        <option value="Critical" <?= isset($data->priority_level) && $data->priority_level == 'Critical' ? 'selected' : '' ?>>Critical</option>
                                    </select>
                                </div>

                                <h3 class="my-3">Support</h3>
                                <hr>
                                <div class="form-group">
                                    <label class="form-label fs-base" for="support_hours">Support Hours</label>
                                    <textarea class="form-control" id="support_hours" name="support_hours" rows="3"
                                        placeholder='e.g. {"Mon-Fri":"9-18","Sat":"Off","Sun":"Off"}'
                                        required><?= $data->support_hours ?? '' ?></textarea>
                                    <small class="form-text text-muted">Define hours in JSON format (e.g.
                                        {"Mon-Fri":"9-18","Sat":"Off","Sun":"Off"})</small>
                                </div>

                                <div class="form-group">
                                    <label class="form-label fs-base" for="penalty_description">Penalty
                                        Description</label>
                                    <textarea class="form-control" id="penalty_description" name="penalty_description"
                                        rows="2" required minlength="5" maxlength="255"
                                        placeholder="e.g. 5% credit per missed SLA"><?= $data->penalty_description ?? '' ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="form-label fs-base" for="is_active">Active Status</label>
                                    <select class="form-select" id="is_active" name="is_active" required>
                                        <option value="1" <?= isset($data->is_active) && $data->is_active == 1 ? 'selected' : '' ?>>Active</option>
                                        <option value="0" <?= isset($data->is_active) && $data->is_active == 0 ? 'selected' : '' ?>>Inactive</option>
                                    </select>
                                </div>

                                <input type="hidden" name="token" value="<?php echo token::generate(); ?>">
                                <input type="hidden" name="sla_id" value="<?= $data->sla_id ?? '' ?>">

                                <div class="mt-3">
                                    <button type="submit" id="sla-btn" class="btn btn-secondary">
                                        <?= $data ? "Update" : "Submit" ?>
                                    </button>
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