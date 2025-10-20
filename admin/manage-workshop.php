<?php
require(__DIR__ . '/../core/init.php');

$user = new users();
$page = new pages('/admin/manage-workshop');
$view = new vwcommon($route[0]);
$web = new websettings();
$function = new functions();

$validation_errors = array();


// check if route with the code exists
$workshopId = isset($route['2']) ? $route['2'] : null;

$table = 'workshops';
$conditions = [
    'id' => ['=', $workshopId],
];

$data = $user->fetch($table, $conditions);

if ($workshopId && !$data) {
    $view->get_page404();
}


$where = [
    'user_type' => ['=', 'user']
];
$cbo_users = $user->fetchbyarray('users', $where);
$selected_user_ids = [];
if ($data) {
    $where = [
        'workshop_id' => ['=', $data->id]
    ];
    $selected_users = $user->fetchbyarray('workshop_participants', $where);
    $selected_user_ids = $selected_users ? array_column($selected_users, 'user_id') : [];
}

if (input::exists('post')) {
    if (token::check(input::get('token'))) {

        $validate = new validate();
        $validate->check($_POST, array(
            'title' => array(
                'required' => true,
                'min' => 5,
                'max' => 50
            ),
            'description' => array(
                'required' => true,
                'min' => 10,
                'max' => 500
            ),
            'date' => array(
                'required' => true
            ),
            'venue' => array(
                'required' => true,
                'min' => 5,
                'max' => 100
            ),
            'status' => array(
                'required' => true
            )
        ));

        if ($validate->get_passed()) {
            try {
                $user->begin_transaction();

                if (input::get('workshop_id')) {
                    $sanitizeddata = sanitizer::sanitize([
                        'title' => input::get('title'),
                        'description' => input::get('description'),
                        'date' => input::get('date'),
                        'venue' => input::get('venue'),
                        'status' => input::get('status'),
                    ]);
                    $conditions = [
                        'id' => ['=', input::get('workshop_id')],
                    ];
                    $user->update('workshops', $sanitizeddata, $conditions);

                    $conditions = [
                        'workshop_id' => ['=', input::get('workshop_id')],
                    ];

                    $user->delete('workshop_participants', $conditions);
                    if (input::get('users')) {
                        foreach (input::get('users') as $userId) {
                            $sanitizeddata = sanitizer::sanitize([
                                'workshop_id' => input::get('workshop_id'),
                                'user_id' => $userId
                            ]);
                            $user->add('workshop_participants', $sanitizeddata);
                        }
                    }
                    $user->commit();

                    session::flash('success', 'Workshop Updated Successfully.');
                    redirect::to($baseurl . '/admin/workshops');
                } else {
                    $sanitizeddata = sanitizer::sanitize([
                        'title' => input::get('title'),
                        'description' => input::get('description'),
                        'date' => input::get('date'),
                        'venue' => input::get('venue'),
                        'status' => input::get('status'),
                    ]);
                    $user->add('workshops', $sanitizeddata);
                    $workshop_id = $user->get_last_insert_id();
                    if (input::get('users')) {
                        foreach (input::get('users') as $userId) {
                            $sanitizeddata = sanitizer::sanitize([
                                'workshop_id' => $workshop_id,
                                'user_id' => $userId
                            ]);
                            $user->add('workshop_participants', $sanitizeddata);
                        }
                    }
                    $user->commit();

                    session::flash('success', 'Workshop Added Successfully.');
                    redirect::to($baseurl . '/admin/workshops');
                }
            } catch (exception $e) {
                $user->roll_back();
                error_log($e->getMessage() . ': ' . $page->get_page_slug(), 3, __DIR__ . '/errors.log');
                session::flash('error', 'An unexpected error occurred. Please try again later or contact support if the issue persists.' . $e);
                redirect::to($baseurl . $page->get_page_slug() . '/' . $workshopId);
            }
        } else {
            $validation_errors = $validate->get_errors();
        }
    }
}


$canonical = $baseurl . $page->get_page_slug() . '/' . $workshopId;
