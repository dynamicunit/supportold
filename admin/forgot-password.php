<?php
require(__DIR__ . '/../core/init.php');

$user = new users();
$page = new pages('/admin/forgot-password');
$view = new vwcommon($route[0]);
$xuser = null;

$user_id = isset($route[2]) ? $route[2] : '';

$condition = [
    'id' => ['=', $user_id]
];
$user_data = $user->fetch('users', $condition);
if ($user_id && !$user_data) {
    $view->get_page404();
}


if (input::exists('post')) {
    if (token::check(input::get('token'))) {
        if ($xuser = $user->fetch('users', array('email' => ['=', input::get('email')]), 1)) {

            try {
                $user->begin_transaction();

                $newpassword = functions::get_randomAlphanumerics('8');
                $salt = hash::salt(32);

                $fields = array(
                    'password' => hash::make($newpassword, $salt),
                    'salt' => $salt
                );

                $conditions = array(
                    'id' => ['=', $xuser->id]
                );

                $user->update('users', $fields, $conditions);

                $response = $user->send_email('reset_pass', input::get('email'), array(
                    'fullname' => ucfirst($xuser->full_name),
                    'website_name' => $common->get_value('site_name_w_ext'),
                    'random_number' => $newpassword
                ));

                $user->commit();
            } catch (exception $e) {
                $user->roll_back();
                die($e->getMessage());
            }
        }
        session::flash('success', 'If your email address matches our records, 
                         we will send you an email with a new password to use for your account.');
    }
}

$canonical = $baseurl . $page->get_page_slug() . '/' . $user_id;
