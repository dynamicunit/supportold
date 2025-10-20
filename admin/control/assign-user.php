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
        $validate = new validate();
        $validate->check(
            $_POST,
            array(
                'assignuser' => array(
                    'required' => true
                )
            )
        );

        if ($validate->get_passed()) {
            try {
                $user->begin_transaction();

                $sanitizeddata = sanitizer::sanitize([
                    'assigned_user_id' => input::get('assignuser'),
                ]);
                $condition = [
                    'ticket_id' => ['=', input::get('ticket')]
                ];
                $user->update('tickets', $sanitizeddata, $condition);


                $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                  Ticket assigned to user successfully.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                $user->commit();
                send_response(true, $message);

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
