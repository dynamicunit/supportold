<?php include_once(__DIR__ . '/../inc_head.php') ?>
<style>
    #imagePreview {
        max-width: 100%;
        max-height: 200px;
        margin-top: 10px;
    }

    #blogimg label:hover {
        cursor: pointer;
    }

    .remove-image:hover {
        cursor: pointer;
    }

    .form-group {
        margin-bottom: 13px;
    }

    .error-message {
        color: red;
        display: none;
    }

    .image-preview {
        display: none;
    }

    .preview-img {
        max-width: 100%;
        max-height: 150px;
    }

    <?php if (isset($data->image) && $data->image != ''): ?>
        #blog-img {
            display: none;
        }

        #image-preview {
            display: block;
        }

    <?php else: ?>
        #blog-img {
            display: block;
        }

        #image-preview {
            display: none;
        }

    <?php endif; ?>
</style>
</head>

<body>
    <main class="page-wrapper">
        <?php include_once(__DIR__ . '/../inc_header.php') ?>
        <section class="container my-5">
            <div class="row">

                <h1 class="text-center"><?= $data ? "Update Blog" : "Add Blog" ?></h1>

                <?= $view->page_messages() ?>
                <?= $view->page_messages($validation_errors) ?>

                <div class="col-12 border rounded p-4 bg-white mt-3">

                    <form id="blog-form" class="row needs-validation" method="post" enctype="multipart/form-data"
                        action="<?= $canonical ?>" novalidate>
                        <h3 class="mb-2">Images</h3>
                        <div class="row">
                            <div id="blogimg" class="form-group">
                                <label for="blog-img" class="form-label fs-base">Upload Blog Image</label>
                                <input type="file" id="blog-img" name="blog-img" class="form-control image-field"
                                    accept="image/*">
                                <div id="image-error" class="error-message"></div>
                                <div id="image-preview" class="image-preview">
                                    <img id="preview-img" alt="Image Preview" class="preview-img"
                                        src="<?= $data->image ?? '' ?>">
                                    <span id="remove-image" class="remove-image text-danger">× Remove
                                        Image</span>
                                </div>
                            </div>
                        </div>

                        <h3 class="my-3">Meta Info</h3>

                        <div class="form-group">
                            <label class="form-label fs-base" for="meta-title">Meta Title</label>
                            <input class="form-control" type="text" placeholder="Meta title" id="meta-title"
                                name="meta-title" value="<?= $data->meta_title ?? '' ?>" required minlength="3"
                                maxlength="150">
                        </div>
                        <div class="form-group">
                            <label class="form-label fs-base" for="meta-desc">Meta Description</label>
                            <input class="form-control" type="text" placeholder="Meta title" id="meta-desc"
                                name="meta-desc" value="<?= $data->meta_desc ?? '' ?>" required minlength="3"
                                maxlength="160">
                        </div>

                        <h3 class="my-3">Page</h3>

                        <div class="form-group">
                            <label class="form-label fs-base" for="title">Page Title</label>
                            <input class="form-control " type="text" placeholder="Page title" id="title" name="title"
                                value="<?= $data->blog_title ?? '' ?>" required minlength="3" maxlength="150">
                        </div>

                        <div class="form-group">
                            <label class="form-label fs-base" for="category">Categories</label>
                            <select class="form-select" id="category" name="category" required>
                                <?= $cbo_category ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label fs-base" for="description">Body</label>
                            <textarea id="tiny" name="body"> <?= $data->blog_body ?? '' ?> </textarea>
                        </div>
                        <input type="hidden" name="token" value="<?php echo token::generate(); ?>">
                        <?php if (isset($data->image) && !empty($data->image)): ?>
                            <input type="hidden" name="old-image" value="<?= $data->image ?>">
                        <?php endif; ?>
                        <input type="hidden" name="image-exists" id="image-exists" value="">
                        <input type="hidden" name="blg_id" value="<?= $data->id ?? '' ?>">
                        <div class="">
                            <button type="submit" id="contact-btn" class="btn btn-secondary"
                                type="submit"><?= $data ? "Update" : 'Submit' ?></button>
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
            const form = document.getElementById('blog-form');
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
            // preview image
            document.getElementById("blog-img").addEventListener("change", function () {
                document.getElementById("image-exists").value = "true";
                var maxFileSizeInBytes = 2048 * 1024;
                var fileSize = this.files[0].size;

                if (fileSize > maxFileSizeInBytes) {
                    document.getElementById("image-error").textContent = "Image size cannot exceed more than 2MB";
                    document.getElementById("image-error").style.display = "block";

                    this.value = "";
                    document.getElementById("image-preview").style.display = "none";
                    return;
                }

                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById("preview-img").src = e.target.result;
                        document.getElementById("image-preview").style.display = "block";
                        document.getElementById("image-error").textContent = "";
                        document.getElementById("image-error").style.display = "none";
                        document.getElementById("blog-img").style.display = "none";
                        document.getElementById("main-img").value = e.target.result;
                    };
                    reader.readAsDataURL(this.files[0]);
                } else {
                    document.getElementById("image-preview").style.display = "none";
                }
            });

            document.getElementById("remove-image").addEventListener("click", function () {
                document.getElementById("image-exists").value = "false";
                document.getElementById("preview-img").src = "";
                document.getElementById("image-preview").style.display = "none";
                document.getElementById("blog-img").value = "";
                document.getElementById("blog-img").style.display = "block";
                document.getElementById("main-img").value = "";
            });

            <?php if (empty($data->image)): ?>
                document.getElementById("image-exists").value = "false";
            <?php else: ?>
                document.getElementById("image-exists").value = "true";
            <?php endif; ?>

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