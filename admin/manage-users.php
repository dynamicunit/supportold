<?php
require(__DIR__ . '/../core/init.php');


$user = new users();
$page = new pages('/admin/manage-users');
$view = new vwcommon($route[0]);
$function = new functions();

$user_id = isset($route[2]) ? $route[2] : '';

$condition = [
    'id' => ['=', $user_id]
];
//route not set
if ($user_id && !$user->fetch('users', $condition)) {
    $view->get_page404();
}
$render = [];
if ($user_id) {
    // get the data 
    $json = '';


    //get the data from the query
    if (
        $user->fetchbyjson(
            'id, full_name, phone,user_type, role, email, password, registration_ip_address, profile_image, membership, is_active,bio, is_email_verified, created_at',
            //table
            'users',
            //fields
            array(),
            //where
            [
                'id' => ['=', $user_id],
                'website_id' => ['=', $basecode]
            ],
            //order by
            '',
            //group by
            '',
            //limit
            $common->get_value('per_page'),
            //offset
            $function->offset($currentpageno)
        )
    ) {
        $json = $user->fetchedjson();
    }

    $response = json_decode($json, true);

    //exist in case of zero records
    if (isset($response['pagetotal']) && $response['pagetotal'] == 0) {
        $view->get_page404();
    }

    // Check if decoding was successful
    if ($response === null && json_last_error() !== JSON_ERROR_NONE) {
        echo 'Error decoding JSON: ' . json_last_error_msg();
    }


    // Access the data array
    $data = $response['data'] ?? [];


    if (!empty($data)) {
        $render = [
            'id' => $data[0]['id'],
            'full_name' => $data[0]['full_name'],
            'user_type' => $data[0]['user_type'],
            'role' => $data[0]['role'],
            'phone' => $data[0]['phone'],
            'email' => $data[0]['email'],
            'bio' => $data[0]['bio'],
            'password' => $data[0]['password'],
            'ip_address' => $data[0]['registration_ip_address'],
            'profile_image' => $data[0]['profile_image'],
            'membership' => $data[0]['membership'],
            'account_status' => $data[0]['is_active'],
            'email_status' => $data[0]['is_email_verified'],
            'created_date' => $data[0]['created_at']
        ];
    }
}
// Update/ Add data
$validation_errors = array();

if (input::exists('post')) {
    if (token::check(input::get('token'))) {


        $validate = new validate();
        $validate->check(
            $_POST,
            array(
                'name' => array(
                    'required' => true,
                    'min' => 5,
                    'max' => 20
                ),
                'phone' => array(
                    'required' => true,
                    'min' => 10,
                    'max' => 15
                ),
                'password' => array(
                    'min' => 8
                ),
                'email' => array(
                    'min' => 5,
                    'max' => 50,
                    'unique_email' => 'users',
                    'format_email' => 'true'
                ),
            )
        );

        if ($validate->get_passed()) {
            try {
                $user->begin_transaction();

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
                if (input::get('user-id')) {
                    // Step 1: Store image in bucket

                    $salt = hash::salt(32);

                    // Step 2: Update profile 
                    $set = array(
                        'profile_image' => $url,
                        'user_type' => input::get('user-type'),
                        'role' => input::get('role'),
                        'full_name' => input::get('name'),
                        'phone' => input::get('phone'),
                        'bio' => input::get('short_description'),
                        'is_active' => input::get('status')
                    );

                    if (!empty(input::get('password'))) {
                        $salt = hash::salt(32);
                        $set['salt'] = $salt;
                        $set['password'] = hash::make(input::get('password'), $salt);
                    }

                    $conditions = array(
                        'id' => ['=', input::get('user-id')]
                    );

                    $user->update('users', $set, $conditions);

                    session::flash('success', "User profile has successfully been updated.");

                    $user->commit();
                    redirect::to($baseurl . '/admin/users');
                } else {
                    $salt = hash::salt(32);

                    // Add profile 
                    $set = array(
                        'website_id' => config::get('website/website_code'),
                        'profile_image' => $url,
                        'user_type' => input::get('user-type'),
                        'role' => input::get('role'),
                        'full_name' => input::get('name'),
                        'email' => input::get('email'),
                        'slug' => $function->to_slug(input::get('name')),
                        'phone' => input::get('phone'),
                        'bio' => input::get('short_description'),
                        'is_active' => input::get('status')
                    );

                    if (!empty(input::get('password'))) {
                        $salt = hash::salt(32);
                        $set['salt'] = $salt;
                        $set['password'] = hash::make(input::get('password'), $salt);
                    }

                    $user->add('users', $set);

                    session::flash('success', "User profile has successfully Added.");

                    $user->commit();
                    redirect::to($baseurl . '/admin/users');
                }
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

$canonical = $baseurl . $page->get_page_slug() . '/' . $user_id;
