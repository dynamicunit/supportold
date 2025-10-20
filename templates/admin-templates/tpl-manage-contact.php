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

                <h1 class="text-center">Update Contact Message</h1>

                <?= $view->page_messages() ?>
                <?= $view->page_messages($validation_errors) ?>

                <div class="col-md-6 offset-md-3 border rounded p-4 bg-white mt-3">

                    <form action="<?= $canonical ?>" id="contact-form" method="post" novalidate>
                        <div class="row">
                            <div class="form-group">
                                <label for="full-name" class="form-label">Full Name</label>
                                <input type="text" name="full-name" id="full-name" class="form-control"
                                    value="<?= $data->full_name ?? '' ?>" required minlength="5" maxlength="20">
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" value="<?= $data->email ?? '' ?>"
                                    class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" name="phone" id="phone" value="<?= $data->phone ?? '' ?>"
                                    class="form-control" maxlength="13">
                            </div>
                            <div class="form-group">
                                <label for="message-body" class="form-label">Message Body</label>
                                <textarea name="message-body" id="message-body" class="form-control" required
                                    minlength="10" maxlength="1000" rows="7"><?= $data->message_body ?? '' ?></textarea>
                            </div>
                        </div>
                        <div>
                            <input type="hidden" name="token" value="<?php echo token::generate(); ?>">
                            <input type="hidden" name="contact_id" value="<?= $data->id ?? '' ?>">
                            <button type="submit" class="btn btn-secondary">Update</button>
                        </div>
                    </form>

                </div>
            </div>
        </section>
    </main>
    <?php include_once(__DIR__ . '/../inc_footer.php') ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('contact-form');
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