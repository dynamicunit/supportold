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

    <?php if (isset($render['profile_image']) && $render['profile_image'] != ''): ?>
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
    <main class="page-wrapper">
        <?php include_once(__DIR__ . '/../inc_header.php') ?>

        <section id="data" class="container my-5">
            <div class="row">

                <h1 class="text-center"><?= $render ? 'Edit' : 'Add' ?> User</h1>

                <?= $view->page_messages() ?>
                <?= $view->page_messages($validation_errors) ?>

                <div class="col-md-6 offset-md-3 border rounded p-4 bg-white mt-3">

                    <form action="<?= $canonical ?>" id="user-form" method="post" enctype="multipart/form-data"
                        novalidate>
                        <div id="userimg" class="form-group">
                            <label for="user-img" class="form-label fs-base">Upload User Image</label>
                            <input type="file" id="user-img" name="user-img" class="form-control image-field"
                                accept="image/*">
                            <div id="image-error" class="error-message"></div>
                            <div id="image-preview" class="image-preview">
                                <img id="preview-img" alt="Image Preview" class="preview-img"
                                    src="<?= $render['profile_image'] ?? '' ?>">
                                <span id="remove-image" class="remove-image text-danger">× Remove
                                    Image</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="user-type" class="form-label">User Type</label>
                            <select name="user-type" id="user-type" class="form-select">
                                <option value="admin" <?= isset($render['user_type']) && $render['user_type'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="user" <?= isset($render['user_type']) && $render['user_type'] == 'user' ? 'selected' : '' ?>>User</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" id="role" class="form-select">
                                <option value="admin" <?= isset($render['role']) && $render['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="user" <?= isset($render['role']) && $render['role'] == 'user' ? 'selected' : '' ?>>User</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name" class="form-label">User Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="<?= $render['full_name'] ?? '' ?>" required minlength="3" maxlength="30">
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" name="email" id="email" class="form-control" <?= $render ? 'disabled' : '' ?> value="<?= $render['email'] ?? '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control"
                                value="<?= $render['phone'] ?? '' ?>" required minlength="8" maxlength="15">
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" <?= $render ? '' : 'required' ?> minlength="8" maxlength="13">
                        </div>
                        <?php
                        if (!$render) { ?>
                                <div class="form-group">
                                    <label for="confirm_password" class="form-label">Confirm Password *</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                        placeholder="" value="">
                                </div>
                        <?php }
                        ?>
                        <div class="form-group">
                            <label for="status" class="form-label">Account Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="1" <?= isset($render['account_status']) && $render['account_status'] == 1 ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= isset($render['account_status']) && $render['account_status'] == 0 ? 'selected' : '' ?>>Disabled</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="short_description" class="form-label">Short Description</label>
                            <textarea name="short_description" id="short_description" rows="7" maxlength="300"
                                class="form-control"><?= $render['bio'] ?? '' ?></textarea>
                        </div>
                        <div class="">
                            <?php if (isset($render['profile_image']) && !empty($render['profile_image'])): ?>
                                    <input type="hidden" name="old-image" value="<?= $render['profile_image'] ?>">
                            <?php endif; ?>
                            <input type="hidden" name="image-exists" id="image-exists" value="">
                            <input type="hidden" name="user-id" value="<?= $render['id'] ?? '' ?>">
                            <input type="hidden" name="token" value="<?php echo token::generate(); ?>">
                            <button type="submit" class="btn btn-secondary"><?= $render ? 'Update' : 'Add' ?></button>
                        </div>
                    </form>

                </div>
            </div>
        </section>
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

            <?php if (empty($render['profile_image'])): ?>
                    document.getElementById("image-exists").value = "false";
            <?php else: ?>
                    document.getElementById("image-exists").value = "true";
            <?php endif; ?>

        });

    </script>
</body>

</html>