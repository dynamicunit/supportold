<?php
require(__DIR__ . '/../core/init.php');

$user = new users();
$page = new pages('/user/change-password');
$view = new vwcommon($route[0]);

$validation_errors = array();


if (input::exists('post')) {
    if (token::check(input::get('token'))) {

        if (!$user->verify_password(input::get('old_password'))) {
            session::flash('error', "Old password is not correct please try again");
            return false;
        }

        $validate = new validate();
        $validate->check($_POST, array(
            'old_password' => array(
                'required' => true
            ),
            'password' => array(
                'required' => true,
                'min' => 8,
                'max' => 20,
            ),
            'confirm_password' => array(
                'required' => true,
                'min' => 8,
                'max' => 20,
                'match' => 'password'
            )
        ));

        if ($validate->get_passed()) {
            try {
                // generate salt
                $salt = hash::salt(32);
                $ver_hash = hash::salt(32);
                $ver_code = functions::get_randomNumbers(8);

                $set = array(
                    'salt' => $salt,
                    'password' => hash::make(input::get('password'), $salt)
                );

                $conditions = ['id' => ['=', $user->get_loggeduser()->id]];

                $user->update('users', $set, $conditions);

                // set success in session and redirect
                session::flash('success', "Your password has been successfully updated!");

            } catch (exception $e) {
                die($e->getMessage());
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
