<?php
require(__DIR__ . '/../../core/init.php');
$user = new users();
$function = new functions();

$success = false; // success variable
$message = null; // for passing the error or success message

function send_response($success, $message, $comment = '', $username = '')
{
    $response = [
        'status' => $success,
        'message' => $message,
        'lastmessage' => $comment,
        'username' => $username
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}


if (input::exists('post')) {
    if (token::check(input::get('token'))) {
        $validate = new validate();
        $validate->check(
            $_POST,
            array(
                'newMessage' => array(
                    'required' => true,
                    'min' => 5,
                    'max' => 30
                ),
                'ticket_id' => array(
                    'required' => true
                ),
            )
        );

        if ($validate->get_passed()) {
            try {
                $user->begin_transaction();

                $sanitizeddata = sanitizer::sanitize([
                    'website_id' => 1,
                    'ticket_id' => input::get('ticket_id'),
                    'message_body' => input::get('newMessage'),
                    'sender_id' => $user->get_loggedin() ? $user->get_loggeduser()->id : '0',
                ]);
                $user->add('ticket_messages', $sanitizeddata);


                $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                   Comment added added successfully.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                $user->commit();
                send_response(true, $message, input::get('newMessage'), $user->get_loggeduser()->full_name);

            } catch (exception $e) {
                $user->roll_back();
                error_log($e->getMessage() . ': control contact form', 3, __DIR__ . '/errors.log');

                $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
               Error occurred while processing your request. Please try again later.
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
           </div>';
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
