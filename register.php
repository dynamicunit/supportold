<?php
require(__DIR__ . '/core/init.php');

$user = new users();
$page = new pages('/register');
$function = new functions();
$view = new vwcommon($route[0]);
$web = new websettings();

$validation_errors = array();

if (input::exists('post')) {
    if (token::check(input::get('token'))) {

        // messages
        $success_message = '<h4>Thank you for registering!</h4><p><small>A verification code has been sent to 
        <span class="fw-semibold">' . input::get('email') . '</span>. 
        Please check your inbox (or spam folder) to complete your registration. If you don\'t receive it, request a new code.</small></p>';

        $error_message = '<p>We\'re experiencing difficulties with email delivery. 
        Please <a href="' . config::get('website/website_url') . '/contact">contact </a>system administrator for details.</p>';

        $success_message_trap = 'Your registration process is complete. Welcome to our community!';

        $duplicate_email_message = 'This email address is already registered. Try using a different email address.';

        // end messages

        // wrong captcha
        $google = new gcaptcha($_POST["g-recaptcha-response"]);

        if (!$google->get_results()) {
            session::flash('success', $success_message_trap);
            redirect::to($baseurl . '/sign-in');
        }

        // honeypot
        if (input::get('phone') != "") {
            session::flash('success', $success_message_trap);
            redirect::to($baseurl . '/sign-in');
        }

        // invalid country
        if (functions::get_iplocation(functions::get_ip()) == "RU") {
            session::flash('success', $success_message_trap);
            redirect::to($baseurl . '/sign-in');
        }

        $validate = new validate();
        $validate->check($_POST, array(
            'fullname' => array(
                'required' => true,
                'min' => 5,
                'max' => 20
            ),
            'email' => array(
                'required' => true,
                'min' => 5,
                'max' => 50,
                'unique_email' => 'users',
                'format_email' => 'true'
            ),
            'password' => array(
                'required' => true,
                'min' => 8,
                'max' => 20
            ),
            'repassword' => array(
                'required' => true,
                'min' => 8,
                'max' => 20,
                'match' => 'password'
            )
        ));

        if ($validate->get_passed()) {
            try {
                $user->begin_transaction();

                // generate salt
                $salt = hash::salt(32);
                $ver_token_hash = hash::salt(32);
                $unsubscribe_hash = hash::salt(32);
                $ver_code = functions::get_randomNumbers(8);


                $sanitizeddata = sanitizer::sanitize([
                    'website_id' => config::get('website/website_code'),
                    'user_type' => 'user',
                    'role' => 'Client',
                    'full_name' => input::get('fullname'),
                    'slug' => '/' . $function::to_slug(input::get('fullname')),
                    'phone' => input::get('contact-no'),
                    'email' => input::get('email'),
                    'password' => hash::make(input::get('password'), $salt),
                    'salt' => $salt,
                    'registration_ip_address' => functions::get_ip(),
                    'membership' => 1,
                    'is_active' => 0,
                    'is_email_verified' => 0,
                    'is_account_hidden' => 0,
                    'unsubscribe_hash' => $unsubscribe_hash
                ]);

                $user->add(
                    'users',
                    $sanitizeddata
                );

                $user_id = $user->get_last_insert_id();
                $condition = [
                    'id' => ['=', $user_id]
                ];

                $user->update('users', ['slug' => '/' . $function::to_slug(input::get('fullname')) . '-' . $user->lastInsertId()], $condition);

                $user->add(
                    'user_email_confirmation_code',
                    array(
                        'website_id' => config::get('website/website_code'),
                        'user_id' => $user->lastInsertId(),
                        'comfirm_hash' => $ver_token_hash,
                        'confirm_code' => $ver_code
                    )
                );

                // send email to user for email verification

                $response = $user->send_email(
                    'signup_confirm',
                    input::get('email'),
                    array(
                        'fullname' => ucfirst(input::get('fullname')),
                        'email_ver_code' => $ver_code,
                        'website_name' => $common->get_value('site_name_w_ext'),
                        'confirmation_link' => '<a href="' . $baseurl . '/verify-email?auth=' . $ver_token_hash . '&ar=1">Click to verify your email</a>'
                    )
                );

                if ($response) {
                    session::flash('success', $success_message);
                } else {
                    session::flash('error', $error_message);
                }

                $user->commit();
                // set success in session and redirect
                redirect::to($baseurl . '/verify-email?auth=' . $ver_token_hash . '&ar=2');
            } catch (exception $e) {
                $user->roll_back();
                error_log($e->getMessage() . ': ' . $page->get_page_slug(), 3, __DIR__ . '/errors.log');
                session::flash('error', 'An unexpected error occurred. Please try again later or contact support if the issue persists.' . $e);
                redirect::to($baseurl . $page->get_page_slug());
            }
        } else {
            $validation_errors = $validate->get_errors();
        }
    }
}

$canonical = $baseurl . $page->get_page_slug();
