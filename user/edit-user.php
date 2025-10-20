<?php
require(__DIR__ . '/../core/init.php');

$user = new users();
$page = new pages('/user/edit-user');
$view = new vwcommon($route[0]);

$validation_errors = array();


if (input::exists('post')) {
    if (token::check(input::get('token'))) {

        $validate = new validate();
        $validate->check($_POST, array(
            'name' => array(
                'required' => true,
                'min' => 5,
                'max' => 20
            )
        ));

        if ($validate->get_passed()) {
            try {
                $user->begin_transaction();

                $s3 = new s3bucket();

                // Step 1: Store image in bucket
                $s3 = new s3bucket();

                $url = input::get('old-image');
                if (!empty($_FILES['user-img']['name'])) {
                    if (input::get('old-image') != '') {
                        $s3->deleteImage(input::get('old-image'));
                    }
                    $url = $s3->addImage($_FILES['user-img']);
                } elseif (input::get('image-exists') == "false") {
                    if (input::get('old-image') != '') {
                        $s3->deleteImage(input::get('old-image'));
                    }
                    $url = '';
                }


                //step 2. update profile 
                $set = array(
                    'profile_image' => $url,
                    'full_name' => input::get('name'),
                    'phone' => input::get('phone'),
                    'bio' => input::get('short_description')
                );

                $conditions = array(
                    'id' => ['=', $user->get_loggeduser()->id]
                );

                $user->update('users', $set, $conditions);

                session::flash('success', "Your profile has successfully been updated.");

                $user->commit();
            } catch (exception $e) {
                $user->roll_back();
                error_log($e->getMessage() . ':/user/edit-user', 3, __DIR__ . '/errors.log');
                session::flash('error', 'An error occurred during account update. Please try again later.');
            }
        } else {
            // Collect validation errors
            $validation_errors = $validate->get_errors();
        }
    }
}

$user->find($user->get_loggeduser()->id);

$data = array(
    'name' => $user->get_loggeduser()->full_name,
    'email' => $user->get_loggeduser()->email,
    'phone' => $user->get_loggeduser()->phone,
    'profile_image' => $user->get_loggeduser()->profile_image,
    'bio' => $user->get_loggeduser()->bio
);

$canonical = $baseurl . $page->get_page_slug();
