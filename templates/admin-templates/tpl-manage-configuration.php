<?php include_once(__DIR__ . '/../inc_head.php') ?>
<script src="https://cdn.tiny.cloud/1/ypj9hucususi58xehyjmhna37doyobwgp1yphc858neivdb0/tinymce/6/tinymce.min.js"
    referrerpolicy="origin"></script>

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

                <h1 class="text-center">Update Configuration</h1>

                <?= $view->page_messages() ?>
                <?= $view->page_messages($validation_errors) ?>

                <div class="col-md-6 offset-md-3 border rounded p-4 bg-white mt-3">

                    <form action="<?= $canonical ?>" id="conf_form" method="post" novalidate>
                        <div class="row">
                            <div class="form-group">
                                <label for="type" class="form-label">Group</label>
                                <input type="text" name="type" id="type" class="form-control"
                                    value="<?= $data->config_group ?? '' ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="property" class="form-label">Property</label>
                                <input type="text" name="property" id="property" value="<?= $data->property ?? '' ?>"
                                    class="form-control" disabled>
                            </div>

                            <div class="form-group">
                                <label for="value" class="form-label">Value</label>
                                <input type="text" name="value" id="value" value="<?= $data->property_value ?? '' ?>"
                                    class="form-control" required minlength="3" maxlength="100">
                            </div>
                        </div>
                        <div>
                            <input type="hidden" name="token" value="<?php echo token::generate(); ?>">
                            <input type="hidden" name="config_id" value="<?= $data->id ?? '' ?>">
                            <button type="submit" class="btn btn-secondary"><?= $data ? 'Update' : 'Submit' ?></button>
                        </div>
                    </form>

                </div>
            </div>
        </section>
    </main>
    <?php include_once(__DIR__ . '/../inc_footer.php') ?>
    <script src="<?= $baseurl ?>/templates/js/jquery.min.js"></script>
    <script src="<?= $baseurl ?>/templates/js/validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tinymce/tinymce-jquery@2/dist/tinymce-jquery.min.js"></script>
    <script src="<?= $baseurl ?>/templates/js/validate.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('conf_form');
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
            plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons accordion',
            editimage_cors_hosts: ['picsum.photos'],
            menubar: 'file edit view insert format tools table help',
            toolbar: "undo redo | accordion accordionremove | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent| forecolor backcolor removeformat | charmap emoticons | code fullscreen preview | save print | pagebreak anchor codesample | ltr rtl",
            autosave_ask_before_unload: true,
            autosave_interval: '30s',
            autosave_prefix: '{path}{query}-{id}-',
            autosave_restore_when_empty: false,
            autosave_retention: '2m',
            image_advtab: true,

            importcss_append: true,

            height: 600,
            image_caption: true,
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            noneditable_class: 'mceNonEditable',
            toolbar_mode: 'sliding',
            contextmenu: 'link image table',
            skin: useDarkMode ? 'oxide-dark' : 'oxide',
            content_css: useDarkMode ? 'dark' : 'default',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
        });
    </script>



</body>

</html>