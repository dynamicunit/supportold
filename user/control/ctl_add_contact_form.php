<?php
require(__DIR__ . '/../../core/init.php');
$user = new users();
$function = new functions();

$success = false; // success variable
$message = null; // for passing the error or success message

function send_response($success, $message)
{
    $response = [
        'status' => $success,
        'message' => $message
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

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
            $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                             ' . $success_message_trap . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
            send_response(true, $message);
        }

        // honeypot
        if (input::get('phone') != "") {
            $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                             ' . $success_message_trap . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
            send_response(true, $message);
        }

        // invalid country
        if (functions::get_iplocation(functions::get_ip()) == "RU") {
            $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                             ' . $success_message_trap . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
            send_response(true, $message);
        }

        $validate = new validate();
        $validate->check(
            $_POST,
            array(
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
            )
        );

        if ($validate->get_passed()) {
            try {
                $user->begin_transaction();

                $sanitizeddata = sanitizer::sanitize([
                    'website_id' => config::get('website/website_code'),
                    'user_id' => $user->get_loggedin() ? $user->get_loggeduser()->id : '0',
                    'email' => input::get('email'),
                    'phone' => input::get('contact'),
                    'ip_address' => functions::get_ip(),
                    'full_name' => input::get('name'),
                    'message_body' => input::get('message')
                ]);
                $user->add('contact', $sanitizeddata);

                if (
                    $email = new email(
                        'contact_form',
                        $common->get_value('con_email'),
                        array(
                            'fullname' => ucfirst(input::get('name')),
                            'email' => ucfirst(input::get('email')),
                            'phone' => ucfirst(input::get('contact')),
                            'message' => ucfirst(input::get('message')),
                            'website_name' => $common->get_value('site_name_w_ext')
                        )
                    )
                ) {
                    $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                     ' . $success_message . '
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                    $user->commit();
                    send_response(true, $message);
                } else {
                    $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                     ' . $error_message . '
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                    $user->commit();
                    send_response(false, $message);
                }
            } catch (exception $e) {
                $user->roll_back();
                error_log($e->getMessage() . ': control contact form', 3, __DIR__ . '/errors.log');
                $message = 'Error occurred while processing your request. Please try again later.';
                send_response(false, $message);
            }
        } else {
            $message = '<div class="alert alert-danger" role="alert">';
            foreach ($validate->get_errors() as $error) {
                $message .= $error . '</br>';
            }
            $message .= '</div>';
            send_response(false, $message);
        }
    }
}
