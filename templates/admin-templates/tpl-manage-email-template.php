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

        <section id="data">
            <div class="container my-5">
                <div class="row">

                    <h1 class="text-center"><?= $data ? 'Update Email Template' : 'Add Email Template' ?> </h1>

                    <?= $view->page_messages() ?>
                    <?= $view->page_messages($validation_errors) ?>

                    <div class="col-12 border rounded p-4 bg-white mt-3">

                        <form action="<?= $canonical ?>" id="email-form" method="post" novalidate>
                            <div class="row">
                                <div class="form-group">
                                    <label for="type" class="form-label">Type</label>
                                    <input type="text" name="type" id="type" class="form-control"
                                        value="<?= $data->text_id ?? '' ?>" required minlength="3" maxlength="50">
                                </div>
                                <div class="form-group">
                                    <label for="desc" class="form-label">Description</label>
                                    <input type="text" name="desc" id="desc"
                                        value="<?= $data->field_description ?? '' ?>" class="form-control" required
                                        minlength="3" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="subject" class="form-label">Subject</label>
                                    <textarea name="subject" id="subject" class="form-control" required minlength="3"
                                        maxlength="100"><?= $data->email_subject ?? '' ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="body" class="form-label">Body</label>
                                    <textarea id="tiny" name="body"> <?= $data->email_body ?? '' ?> </textarea>
                                </div>
                            </div>
                            <div>
                                <input type="hidden" name="token" value="<?php echo token::generate(); ?>">
                                <input type="hidden" name="mail_id" value="<?= $data->id ?? '' ?>">
                                <button type="submit"
                                    class="btn btn-secondary"><?= $data ? 'Update' : 'Submit' ?></button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php include_once(__DIR__ . '/../inc_footer.php') ?>
    <script src="<?= $baseurl ?>/templates/apps/tinymce/tinymce.min.js"></script>
    <script src="<?= $baseurl ?>/templates/js/validate.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('email-form');
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
    <script>
        const useDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const isSmallScreen = window.matchMedia('(max-width: 1023.5px)').matches;

        tinymce.init({
            selector: 'textarea#tiny',
            height: 600,
            theme: 'silver',
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools'
            ],
            toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            toolbar2: 'print preview media | forecolor backcolor emoticons',
            image_advtab: true,
            templates: [{
                title: 'Test template 1',
                content: 'Test 1'
            },
            {
                title: 'Test template 2',
                content: 'Test 2'
            }
            ],
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tinymce.com/css/codepen.min.css'
            ]
        });
    </script>


</body>

</html>