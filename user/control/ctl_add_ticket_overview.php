<?php
require(__DIR__ . '/../../core/init.php');
$user = new users();
$function = new functions();

$success = false; // success variable
$message = null; // for passing the error or success message

function send_response($success, $message, $id = '')
{
    $response = [
        'status' => $success,
        'message' => $message,
        'ticket_id' => $id
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
                'ticketTitle' => array(
                    'required' => true,
                    'min' => 5,
                    'max' => 200
                ),
                'appName' => array(
                    'required' => true,
                ),
                'issueType' => array(
                    'required' => true,
                ),
                'priority' => array(
                    'required' => true,
                ),
                'issueDescription' => array(
                    'required' => true,
                    'min' => 5,
                    'max' => 1000
                )
            )
        );

        if ($validate->get_passed()) {
            try {
                $user->begin_transaction();
                $sanitizeddata = sanitizer::sanitize([
                    'issue_title' => input::get('ticketTitle'),
                    'app_id' => input::get('appName'),
                    'issue_type' => input::get('issueType'),
                    'priority' => input::get('priority'),
                    'issue_description' => input::get('issueDescription'),
                    'ticket_status' => input::get('status'),
                    'department_id' => input::get('department')
                ]);
                if (input::get('ticket_id')) {

                    $condition = [
                        'ticket_id' => ['=', input::get('ticket_id')]
                    ];
                    $user->update('tickets', $sanitizeddata, $condition);
                    $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                       Ticket information updated successfully.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                    $user->commit();
                    send_response(true, $message);
                } else {
                    $sanitizeddata['created_user_id'] = $user->get_loggedin() ? $user->get_loggeduser()->id : '0';

                    $user->add('tickets', $sanitizeddata);
                    $last_id = $user->get_last_insert_id();
                    $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                       Ticket information added successfully.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                    $user->commit();
                    send_response(true, $message, $last_id);
                }
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
