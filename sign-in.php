<?php
require(__DIR__ . '/core/init.php');

$user = new users();
$page = new pages('/sign-in');
$view = new vwcommon($route[0]);

if (input::exists('post')) {
    if (token::check(input::get('token'))) {
        switch ($user->login(input::get('email'), input::get('password'))) {
            case 0:
                session::flash('error', 'Invalid Email/Password. Please try again.');
                break;
            case 1:
                session::flash('error', 'Email verification pending for this email.');
                break;
            case 2:
                if ($user->get_loggeduser()->user_type === 'admin') {
                    redirect::to($baseurl . '/admin/dashboard');
                } else if ($user->get_loggeduser()->user_type == 'user') {
                    redirect::to($baseurl . '/user/dashboard');
                } else if ($user->get_loggeduser()->user_type == 'agency') {
                    redirect::to($baseurl . '/agency/dashboard');
                }
                setcookie('email', input::get('email'), time() + 30 * 24 * 60 * 60);
                setcookie('password', input::get('password'), time() + 30 * 24 * 60 * 60);
                break;
        }
    }
}

/* canonical */
$canonical = $baseurl . $page->get_page_slug();
