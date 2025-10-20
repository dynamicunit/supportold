<?php include_once(__DIR__ . '/../inc_head.php') ?>
<style>
    #imagePreview {
        max-width: 100%;
        max-height: 200px;
        margin-top: 10px;
    }

    #companyImg label:hover {
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

    <?php if (isset($data->logo_url) && $data->logo_url != ''): ?>
        #company-img {
            display: none;
        }

        #image-preview {
            display: block;
        }

    <?php else: ?>
        #company-img {
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

                <h1 class="text-center"><?= $data ? "Update Company" : "Add Company" ?></h1>

                <?= $view->page_messages() ?>
                <?= $view->page_messages($validation_errors) ?>

                <div class="col-12 border rounded p-4 bg-white mt-3">

                    <form id="company-form" class="row needs-validation" method="post" enctype="multipart/form-data"
                        action="<?= $canonical ?>" novalidate>

                        <h3 class="mb-2">Company Logo</h3>
                        <hr>
                        <div class="row">
                            <div id="companyImg" class="form-group col-md-4">
                                <label for="company-img" class="form-label fs-base">Upload Company Logo</label>
                                <input type="file" id="company-img" name="company-img" class="form-control image-field"
                                    accept="image/*">
                                <div id="image-error" class="error-message"></div>
                                <div id="image-preview" class="image-preview">
                                    <img id="preview-img" alt="Image Preview" class="preview-img"
                                        src="<?= $data->logo_url ?? '' ?>">
                                    <span id="remove-image" class="remove-image text-danger">× Remove
                                        Logo</span>
                                </div>
                            </div>
                        </div>

                        <h3 class="my-3">Company Info</h3>
                        <hr>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label fs-base" for="company_name">Company Name</label>
                                <input class="form-control" type="text" id="company_name" name="company_name"
                                    placeholder="Enter Company Name" value="<?= $data->company_name ?? '' ?>" required
                                    minlength="3" maxlength="200">
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label fs-base" for="company_code">Company Code</label>
                                <input class="form-control" type="text" id="company_code" name="company_code"
                                    placeholder="Short code (e.g. ABCUTIL)" value="<?= $data->company_code ?? '' ?>"
                                    required minlength="2" maxlength="50">
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label fs-base" for="industry">Industry</label>
                                <input class="form-control" type="text" id="industry" name="industry"
                                    placeholder="Industry type" maxlength="100" value="<?= $data->industry ?? '' ?>">
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label fs-base" for="company_size">Company Size</label>
                                <input class="form-control" type="text" id="company_size" name="company_size"
                                    placeholder="e.g. 200-500" maxlength="50" value="<?= $data->company_size ?? '' ?>">
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label fs-base" for="timezone">Timezone</label>
                                <input class="form-control" type="text" id="timezone" name="timezone"
                                    placeholder="e.g. Asia/Dubai" maxlength="100" value="<?= $data->timezone ?? '' ?>">
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label fs-base" for="default_language">Default Language</label>
                                <input class="form-control" type="text" id="default_language" name="default_language"
                                    placeholder="e.g. en" maxlength="20" value="<?= $data->default_language ?? '' ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label fs-base" for="portal_domain">Portal Domain</label>
                                <input class="form-control" type="text" id="portal_domain" name="portal_domain"
                                    placeholder="support.example.com" maxlength="200"
                                    value="<?= $data->portal_domain ?? '' ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label fs-base" for="theme_color">Theme Color</label>
                                <input class="form-control" type="color" id="theme_color" name="theme_color"
                                    value="<?= $data->theme_color ?? '#000000' ?>">
                            </div>
                        </div>
                        <h3 class="my-3">Contract & Billing</h3>
                        <hr>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="form-label fs-base" for="sla_id">SLA</label>
                                <select class="form-select" id="sla_id" name="sla_id" required>
                                    <?= $cbo_sla ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label fs-base" for="contract_start">Contract Start</label>
                                <input class="form-control" type="date" id="contract_start" name="contract_start"
                                    value="<?= $data->contract_start ?? '' ?>">
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label fs-base" for="contract_end">Contract End</label>
                                <input class="form-control" type="date" id="contract_end" name="contract_end"
                                    value="<?= $data->contract_end ?? '' ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="form-label fs-base" for="billing_type">Billing Type</label>
                                <select class="form-select" id="billing_type" name="billing_type">
                                    <option value="retainer" <?= isset($data->billing_type) && $data->billing_type == 'retainer' ? 'selected' : '' ?>>Retainer</option>
                                    <option value="per_ticket" <?= isset($data->billing_type) && $data->billing_type == 'per_ticket' ? 'selected' : '' ?>>Per Ticket</option>
                                    <option value="hybrid" <?= isset($data->billing_type) && $data->billing_type == 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
                                </select>
                            </div>
                        </div>
                        <h3 class="my-3">Contact Details</h3>
                        <hr>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label fs-base" for="contact_person">Contact Person</label>
                                <input class="form-control" type="text" id="contact_person" name="contact_person"
                                    placeholder="Full name" maxlength="150" value="<?= $data->contact_person ?? '' ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label fs-base" for="contact_email">Contact Email</label>
                                <input class="form-control" type="email" id="contact_email" name="contact_email"
                                    placeholder="example@email.com" maxlength="150"
                                    value="<?= $data->contact_email ?? '' ?>">
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label fs-base" for="contact_phone">Contact Phone</label>
                                <input class="form-control" type="text" id="contact_phone" name="contact_phone"
                                    placeholder="+971..." maxlength="50" value="<?= $data->contact_phone ?? '' ?>">
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label fs-base" for="billing_email">Billing Email</label>
                                <input class="form-control" type="email" id="billing_email" name="billing_email"
                                    placeholder="billing@example.com" maxlength="150"
                                    value="<?= $data->billing_email ?? '' ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label fs-base" for="is_active">Active Status</label>
                            <select class="form-select" id="is_active" name="is_active">
                                <option value="1" <?= isset($data->is_active) && $data->is_active == 1 ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= isset($data->is_active) && $data->is_active == 0 ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                        <input type="hidden" name="token" value="<?php echo token::generate(); ?>">
                        <input type="hidden" name="company_id" value="<?= $data->company_id ?? '' ?>">
                        <?php if (isset($data->logo_url) && !empty($data->logo_url)): ?>
                            <input type="hidden" name="old-logo" value="<?= $data->logo_url ?>">
                        <?php endif; ?>
                        <input type="hidden" name="image-exists" id="image-exists" value="">

                        <div class="mt-3">
                            <button type="submit" id="company-btn" class="btn btn-secondary">
                                <?= $data ? "Update" : "Submit" ?>
                            </button>
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
            const form = document.getElementById('company-form');
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
            // preview image
            document.getElementById("company-img").addEventListener("change", function () {
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
                        document.getElementById("company-img").style.display = "none";
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
                document.getElementById("company-img").value = "";
                document.getElementById("company-img").style.display = "block";
                document.getElementById("main-img").value = "";
            });

            <?php if (empty($data->logo_url)): ?>
                document.getElementById("image-exists").value = "false";
            <?php else: ?>
                document.getElementById("image-exists").value = "true";
            <?php endif; ?>

        });

    </script>


</body>

</html>