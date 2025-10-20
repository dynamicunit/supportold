<?php
require(__DIR__ . '/core/init.php');

$user = new users();
$page = new pages('/contact');
$function = new functions();
$view = new vwcommon($route[0]);
$web = new websettings();
$validation_errors = array();


$where = [
    'entity_id' => ['=', 43]
];
$cbo_reason = $web->get_combo(
    $web->fetchbyarray('lookups', $where),
    array(
        'type' => 'select',
        'selected' => array(),
        'values' => 'single'
    )
);

if (input::exists('post')) {
    if (token::check(input::get('token'))) {

        // messages
        $success_message = '<h4>Thank you for Contact us!</h4><p><small>Your query has been assigned to one of the staff members. We will contact you shortly.</small></p>';
        $success_message_trap = 'Your query has been assigned to one of the staff members. We will contact you shortly.!';
        $error_message = '<p>We regret to inform you that we\'re experiencing difficulties with email delivery to your address. Our team is actively working to resolve this. Please check your email settings and, if necessary, contact your system administrator through our <a href="' . config::get('website/website_url') . '/contact">contact form</a>. We apologize for any inconvenience and appreciate your understanding.</p>';

        // end messages

        // wrong captcha
        $google = new gcaptcha($_POST["g-recaptcha-response"]);

        if (!$google->get_results()) {
            session::flash('success', $success_message_trap);
            redirect::to($baseurl . '/contact-us');
        }

        // honeypot
        if (input::get('phone') != "") {
            session::flash('success', $success_message_trap);
            redirect::to($baseurl . '/contact-us');
        }

        // invalid country
        if (functions::get_iplocation(functions::get_ip()) == "RU") {
            session::flash('success', $success_message_trap);
            redirect::to($baseurl . '/contact-us');
        }

        $validate = new validate();
        $validate->check($_POST, array(
            'name' => array(
                'required' => true,
                'min' => 5,
                'max' => 20
            ),
            'email' => array(
                'required' => true,
                'min' => 5,
                'max' => 50,
                'format_email' => 'true'
            ),
            'message' => array(
                'required' => true,
                'min' => 10,
                'max' => 1000
            )
        ));

        if ($validate->get_passed()) {
            try {
                $user->begin_transaction();
                $sanitizeddata = sanitizer::sanitize([
                    'website_id' => config::get('website/website_code'),
                    'user_id' => $user->get_loggedin() ? $user->get_loggeduser()->id : '0',
                    'email' => input::get('email'),
                    'about_what' => input::get('about_what'),
                    'phone' => input::get('contact'),
                    'ip_address' => functions::get_ip(),
                    'ip_location' => functions::get_iplocation(functions::get_ip()),
                    'full_name' => input::get('name'),
                    'message_body' => input::get('message'),
                    'reply_from' => $common->get_value('smtp_sender_address'),
                ]);
                $user->add('contactform', $sanitizeddata);
                $last_id = $user->get_last_insert_id();
                $sanitizeddata = sanitizer::sanitize([
                    'slug' => $common->get_value('site_name') . '-' . $last_id
                ]);
                $condition = [
                    'id' => ['=', $last_id]
                ];
                $user->update('contactform', $sanitizeddata, $condition);
                if (
                    $email = new email('contact_form', $common->get_value('con_email'), array(
                        'fullname' => ucfirst(input::get('name')),
                        'email' => ucfirst(input::get('email')),
                        'phone' => ucfirst(input::get('contact')),
                        'message' => ucfirst(input::get('message')),
                        'website_name' => $common->get_value('site_name_w_ext')
                    ))
                ) {
                    session::flash('success', $success_message);
                } else {
                    session::flash('error', $error_message);
                }
                $user->commit();
                // set success in session and redirect
                redirect::to($baseurl . '/contact');
            } catch (exception $e) {
                $user->roll_back();
                error_log($e->getMessage() . ': ' . $page->get_page_slug(), 3, __DIR__ . '/errors.log');
                session::flash('error', 'An unexpected error occurred. Please try again later or contact support if the issue persists.');
                redirect::to($baseurl . $page->get_page_slug());
            }
        } else {
            $validation_errors = null;
            foreach ($validate->get_errors() as $error) {
                $validation_errors[] = $error;
            }
        }
    }
}

$canonical = $baseurl . $page->get_page_slug();
