<?php
require(__DIR__ . '/core/init.php');

$user = new users();
$page = new pages('/verify-email');
$view = new vwcommon($route[0]);

$show_form = true;
$confirmed_error = false;

$success_message = "Welcome! Your email has been successfully verified. You can now access all account features.";
$error_message = "The code you entered is invalid. Please try again with the code sent to your email.";
$wrong_message = "Your email address has already been verified.";

if (!input::get('auth') || !input::get('ar')) {
    $view->get_page404();
}

if (input::get('auth') and input::get('ar') == 1) {
    $condition = array('comfirm_hash' => ['=', input::get('auth')]);
    $data = $user->fetch('user_email_confirmation_code', $condition);

    try {
        if ($data) {
            $fields = array('is_email_verified' => '1');
            $conditions = array('id' => ['=', $data->user_id]);

            $user->update('users', $fields, $conditions);

            $conditions = array('user_id' => ['=', $data->user_id]);
            $user->delete('user_email_confirmation_code', $condition);

            $show_form = false;
            session::flash('success', $success_message);
            redirect::to($baseurl . '/sign-in');
        } else {
            throw new exception($wrong_message);
        }
    } catch (exception $e) {
        $confirmed_error = ($e->getMessage());
    }
}

if (input::exists('post')) {
    if (token::check(input::get('token'))) {
        if (input::get('ar') == 2) {
            $condition = array('confirm_code' => ['=', input::get('code')]);
            $data = $user->fetch('user_email_confirmation_code', $condition);

            try {
                if ($data) {
                    $fields = array('is_email_verified' => '1');
                    $conditions = array('id' => ['=', $data->user_id]);

                    $user->update('users', $fields, $conditions);

                    $conditions = array('user_id' => ['=', $data->user_id]);
                    $user->delete('user_email_confirmation_code', $condition);

                    session::flash('success', $success_message);
                    redirect::to($baseurl . '/sign-in');
                } else {
                    throw new exception($wrong_message);
                }
            } catch (exception $e) {
                $confirmed_error = ($e->getMessage());
            }
        }
    }
}



$canonical = $baseurl . '/' . $route[0];
