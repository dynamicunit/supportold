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

                <h1 class="text-center"><?= $data ? 'Update Website Page' : 'Add Website Page' ?> </h1>

                <?= $view->page_messages() ?>
                <?= $view->page_messages($validation_errors) ?>

                <div class="col-12 border rounded p-4 bg-white mt-3">

                    <form action="<?= $canonical ?>" id="page-form" method="post" novalidate.>
                        <div class="row">
                            <div class="form-group">
                                <label for="slug" class="form-label">slug</label>
                                <input type="text" name="slug" id="slug" class="form-control"
                                    value="<?= $data->slug ?? '' ?>" required minlength="3" maxlength="50">
                            </div>
                            <div class="form-group">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" name="meta_title" id="meta_title"
                                    value="<?= $data->meta_title ?? '' ?>" class="form-control" required minlength="3"
                                    maxlength="80">
                            </div>
                            <div class="form-group">
                                <label for="meta_desc" class="form-label">Meta desc</label>
                                <textarea name="meta_desc" id="meta_desc" class="form-control" minlength="3"
                                    maxlength="150"><?= $data->meta_description ?? '' ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="meta_keywords" class="form-label">Meta keywords</label>
                                <input type="text" name="meta_keywords" id="meta_keywords"
                                    value="<?= $data->meta_keywords ?? '' ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="page_title" class="form-label">Page Title</label>
                                <input type="text" name="page_title" id="page_title"
                                    value="<?= $data->page_title ?? '' ?>" class="form-control" required minlength="3"
                                    maxlength="50">
                            </div>
                            <div class="form-group">
                                <label for="page_title_desc" class="form-label">Page Title Desc</label>
                                <input type="text" name="page_title_desc" id="page_title_desc"
                                    value="<?= $data->page_title_description ?? '' ?>" class="form-control"
                                    minlength="3" maxlength="100">
                            </div>
                            <div class="form-group">
                                <label for="page_contents" class="form-label">Page Contents</label>
                                <textarea name="page_contents" id="tiny"
                                    class="form-control"><?= $data->page_body ?? '' ?></textarea>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="approval_status" class="form-label">Page Status</label>
                                <select name="approval_status" id="approval_status" class="form-select">
                                    <option disabled selected>--Select Status--</option>
                                    <option value="1" <?= $data && $data->is_active == '1' ? 'selected' : '' ?>>
                                        Active</option>
                                    <option value="0" <?= $data && $data->is_active == '0' ? 'selected' : '' ?>>
                                        disabled</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <input type="hidden" name="token" value="<?php echo token::generate(); ?>">
                            <input type="hidden" name="page_id" value="<?= $data->id ?? '' ?>">
                            <button type="submit" class="btn btn-secondary"><?= $data ? 'Update' : 'Submit' ?></button>
                        </div>
                    </form>

                </div>
            </div>
        </section>
    </main>
    <?php include_once(__DIR__ . '/../inc_footer.php') ?>

    <script src="<?= $baseurl ?>/templates/apps/tinymce/tinymce.min.js"></script>
    <script src="<?= $baseurl ?>/templates/js/validate.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('page-form');
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