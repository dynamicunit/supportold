<?php include_once(__DIR__ . '/../inc_head.php') ?>
<style>
    #imagePreview {
        max-width: 100%;
        max-height: 200px;
        margin-top: 10px;
    }

    #userimg label:hover {
        cursor: pointer;
    }

    .remove-image:hover {
        cursor: pointer;
    }

    .form-group {
        margin-bottom: 10px;
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

    <?php if (isset($data['profile_image']) && $data['profile_image'] != ''): ?>
        #user-img {
            display: none;
        }

        #image-preview {
            display: block;
        }

    <?php else: ?>
        #user-img {
            display: block;
        }

        #image-preview {
            display: none;
        }

    <?php endif; ?>
</style>
</head>

<body>
    <?php include_once(__DIR__ . '/../inc_sidebar.php') ?>



    <!-- CONTENT -->
    <main class="content">
        <!-- Header Row -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <h1 class="mb-0">Dashboard</h1>

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= $baseurl ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit User</li>
                </ol>
            </nav>
        </div>

        <div class="row">

            <h1 class="text-center">Account Details</h1>

            <?= $view->page_messages() ?>
            <?= $view->page_messages($validation_errors) ?>

            <div class="col-md-6 offset-md-3 border rounded p-4 bg-white mt-3">

                <form action="<?= $canonical ?>" id="user-form" method="post" enctype="multipart/form-data" novalidate>
                    <div id="userimg" class="form-group">
                        <label for="user-img" class="form-label fs-base">Profile Image</label>
                        <input type="file" id="user-img" name="user-img" class="form-control image-field"
                            accept="image/*">
                        <div id="image-error" class="error-message"></div>
                        <div id="image-preview" class="image-preview">
                            <img id="preview-img" alt="Image Preview" class="preview-img"
                                src="<?= $data['profile_image'] ?? '' ?>">
                            <span id="remove-image" class="remove-image text-danger">× Remove
                                Image</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="<?= $data['name'] ?? '' ?>"
                            required minlength="5" maxlength="20">
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" name="email" id="email" class="form-control" disabled
                            value="<?= $data['email'] ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control"
                            value="<?= $data['phone'] ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="short_description" class="form-label">Bio / About Me</label>
                        <textarea name="short_description" id="short_description" rows="5"
                            class="form-control"><?= $data['bio'] ?? '' ?></textarea>
                    </div>
                    <div>
                        <?php if (isset($data['profile_image']) && !empty($data['profile_image'])): ?>
                            <input type="hidden" name="old-image" value="<?= $data['profile_image'] ?>">
                        <?php endif; ?>
                        <input type="hidden" name="image-exists" id="image-exists" value="">
                        <input type="hidden" name="token" value="<?php echo token::generate(); ?>">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>

            </div>
        </div>

    </main>
    <?php include_once(__DIR__ . '/../inc_footer.php') ?>
    <script src="<?= $baseurl ?>/templates/js/validate.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('user-form');
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
            document.getElementById("user-img").addEventListener("change", function () {
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
                        document.getElementById("user-img").style.display = "none";
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
                document.getElementById("user-img").value = "";
                document.getElementById("user-img").style.display = "block";
                document.getElementById("main-img").value = "";
            });

            <?php if (empty($data['profile_image'])): ?>
                document.getElementById("image-exists").value = "false";
            <?php else: ?>
                document.getElementById("image-exists").value = "true";
            <?php endif; ?>
        });
    </script>


</body>

</html>