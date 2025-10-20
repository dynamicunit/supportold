<?php include_once('inc_head.php') ?>
<style>
    .form-group {
        margin-bottom: 15px;
    }
</style>
</head>

<body>
    <?php include_once('inc_header.php') ?>


    <section>
        <div class="container d-flex justify-content-center align-items-center py-5">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card shadow-lg p-4 rounded">
                        <div class="card-body">
                            <h3 class="text-center mb-4">Contact Us</h3>
                            <?= $view->page_messages($validation_errors) ?>
                            <form id="contact-form" name="contact-form" class="row needs-validation" method="post"
                                action="<?= $canonical ?>" novalidate>
                                <div class="row">
                                    <!-- “What’s it about” -->
                                    <div class="mb-3">
                                        <label class="form-label" for="about_what">What's it about? (*)</label>
                                        <select class="form-select" id="about_what" name="about_what" required>
                                            <?= $cbo_reason ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="form-label fs-base" for="name">Name</label>
                                        <input class="form-control " type="text" placeholder="Your name" id="name"
                                            name="name" value="<?= input::get('name') ?>" required minlength="5"
                                            maxlength="20">
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="form-label fs-base" for="email">Email</label>
                                        <input class="form-control" type="email" placeholder="Email address" id="email"
                                            name="email" value="<?= input::get('email') ?>" required minlength="5"
                                            maxlength="50">
                                    </div>
                                    <div class="form-group">
                                        <label for="contact" class="form-label">Contact number *</label>
                                        <input class="form-control" type="text" id="contact" name="contact" required
                                            autocomplete="off" placeholder="phone" value="<?= input::get('contact') ?>">
                                    </div>
                                    <div class="col-sm-12 form-group">
                                        <label class="form-label fs-base" for="message">How can we help?</label>
                                        <textarea class="form-control " rows="5"
                                            placeholder="Enter your message here..." id="message" name="message"
                                            required minlength="10" maxlength="1000"></textarea>
                                    </div>
                                    <div class="d-none">
                                        <label for="subject" class="form-label txtphone">Phone *</label>
                                        <input class="form-control txtphone" type="text" id="phone" name="phone"
                                            autocomplete="off" placeholder="phone" value="<?= input::get('phone') ?>">
                                    </div>
                                    <div class="form-group">
                                        <div class="g-recaptcha "
                                            data-sitekey="<?= $common->get_value('data_sitekey') ?>">
                                        </div>
                                        <!-- <input type="hidden" class="hiddenRecaptcha" name="hiddenRecaptcha" id="hiddenRecaptcha"> -->
                                    </div>
                                    <input type="hidden" name="token" value="<?php echo token::generate(); ?>">
                                    <div class="col-sm-12 mt-3">
                                        <button type="submit" id="submit-button" class="btn btn-primary"
                                            type="submit"><span id="submitSpinner"
                                                class="spinner-border spinner-border-sm d-none"
                                                aria-hidden="true"></span> Send a
                                            request</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include_once('inc_footer.php') ?>
    <script src='https://www.google.com/recaptcha/api.js' async defer></script>
    <script src="<?= $baseurl ?>/templates/js/validate.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('contact-form');
            if (form) {
                form.addEventListener('submit', (event) => {
                    if (!validateForm(form)) {
                        event.preventDefault();
                    } else {
                        const button = document.getElementById('submit-button');
                        const spinner = document.getElementById('submitSpinner');
                        button.disabled = true;
                        spinner.classList.remove("d-none");
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
        window.onload = function () {
            var recaptcha = document.forms["contact-form"]["g-recaptcha-response"];
            recaptcha.required = true;
            recaptcha.oninvalid = function (e) {
                alert("Please complete the captcha");
            }
        }
    </script>

</body>

</html>