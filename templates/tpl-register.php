<?php include_once('inc_head.php') ?>
<style>
    .custom-form-group {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .custom-form-group label {
        min-width: 160px;
        margin-bottom: 0;
        white-space: nowrap;
        text-align: right;
    }

    .custom-form-group select,
    .custom-form-group input {
        flex: 1;
        width: auto;
    }

    /* Responsive behavior for small screens */
    @media (max-width: 768px) {
        .custom-form-group {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }

        .custom-form-group label {
            min-width: auto;
            text-align: left;
        }

        .custom-form-group select,
        .custom-form-group input {
            width: 100%;
        }
    }

    h2 {
        font-size: 1.4rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-bottom: 3px solid lightgray;
        padding-bottom: 5px;
        margin-bottom: 30px;
        margin-top: 20px;
        display: inline-block;
    }
</style>

</head>

<body>
    <?php include_once('inc_header.php') ?>

    <!-- Second Section: Form -->
    <section class="my-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <!-- Wrap the form in a Bootstrap card for a cleaner look -->
                    <div class="card shadow">
                        <div class="card-body mx-md-5">

                            <h1 class="text-center mb-4">Register</h1>

                            <form action="<?= $canonical ?>" name="frmReg" id="frmReg" method="post" novalidate>

                                <?= $view->page_messages($validation_errors) ?>

                                <div class="form-group custom-form-group">
                                    <label for="fullname" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" id="fullname" name="fullname"
                                        placeholder="Enter your full legal name" required
                                        value="<?= input::get('fullname') ?>">
                                </div>

                                <div class="form-group custom-form-group">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Enter your email" required value="<?= input::get('email') ?>">
                                </div>

                                <div class="form-group custom-form-group">
                                    <label for="contact-no" class="form-label">Phone *</label>
                                    <input type="phone" class="form-control" id="contact-no" name="contact-no"
                                        placeholder="Enter your phone" required value="<?= input::get('contact-no') ?>">
                                </div>

                                <div class="form-group custom-form-group">
                                    <label for="password" class="form-label">Password *</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Choose a password" required value="">
                                </div>

                                <div class="form-group custom-form-group">
                                    <label for="repassword" class="form-label">Confirm Password *</label>
                                    <input type="password" class="form-control" id="repassword" name="repassword"
                                        placeholder="Re-enter your password" required value="">
                                </div>

                                <!-- DUPLICATED PHONE FIELD - Just rename the label to avoid confusion -->
                                <div class="form-group custom-form-group">
                                    <label for="phone" class="form-label txtphone">Phone (Again) *</label>
                                    <input class="form-control txtphone" type="text" id="phone" name="phone"
                                        autocomplete="off" placeholder="Re-enter your phone"
                                        value="<?= input::get('phone') ?>">
                                </div>

                                <div class="form-group custom-form-group">
                                    <div class="g-recaptcha mb-4"
                                        data-sitekey="<?= $common->get_value('data_sitekey') ?>">
                                    </div>
                                </div>

                                <p>
                                    <small>
                                        By clicking Register, you agree to our
                                        <a href="<?= $baseurl ?>/terms-of-service">Terms and conditions</a>
                                    </small>
                                </p>
                                <input type="hidden" name="token" value="<?php echo token::generate(); ?>">
                                <input type="submit" value="Register" class="btn btn-primary px-4">

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container" aria-label="Important Registration Disclaimer">
        <div class="col-lg-8 offset-lg-2 p-4" role="alert">
            <h3 class="alert-heading text-danger">
                <i class="fas fa-exclamation-triangle"></i> Important Notice
            </h3>
            <hr>
            <ul class="mt-3 ps-3">
                <li class="mb-2">
                    <i class="fas fa-user-check text-success me-2"></i>
                    <strong>Unverified accounts</strong> will be **automatically removed** from our system **seven
                    days** after registration.
                </li>
                <li class="mb-2">
                    <i class="fas fa-shield-alt text-danger me-2"></i>
                    **Fraudulent profiles** created to deceive or engage in unethical activities are strictly
                    prohibited.
                    Such actions may lead to **legal consequences**, including **IP tracking** and potential reporting
                    to **law enforcement authorities**.
                </li>
                <li>
                    <i class="fas fa-ban text-warning me-2"></i>
                    Accounts registered using **proxies, VPNs, or anonymizing software** will **not be activated**.
                </li>
            </ul>
        </div>
    </section>




    <?php include_once('inc_footer.php') ?>
    <script src='https://www.google.com/recaptcha/api.js' async defer></script>
    <script src="<?= $baseurl ?>/templates/js/validate.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const form = document.getElementById('frmReg');
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

    <script>
        window.onload = function () {
            var recaptcha = document.forms["frmReg"]["g-recaptcha-response"];
            recaptcha.required = true;
            recaptcha.oninvalid = function (e) {
                alert("Please complete the captcha");
            }
        }
    </script>

</body>

</html>