<?php
require(__DIR__ . '/../core/init.php');

$user = new users();
$page = new pages('/admin/manage-configuration');
$view = new vwcommon($route[0]);
$web = new websettings();
$function = new functions();

$validation_errors = array();


// check if route with the code exists
$configid = isset($route['2']) ? $route['2'] : null;
$conditions = [
    'id' => ['=', $configid]
];

// check if the designer id is valid
if (!$web->fetch('configurations', $conditions)) {
    $view->get_page404();
}
$table = 'configurations';
$conditions = [
    'id' => ['=', $configid],
];

$data = $web->fetch($table, $conditions);


if (input::exists('post')) {
    if (token::check(input::get('token'))) {

        $validate = new validate();
        $validate->check($_POST, array(

            'value' => array(
                'min' => 3,
                'max' => 100
            )
        ));

        if ($validate->get_passed()) {
            try {
                $web->begin_transaction();

                if (input::get('config_id')) {
                    $sanitizeddata = sanitizer::sanitize([
                        'property_value' => input::get('value'),
                    ]);
                    $conditions = [
                        'id' => ['=', input::get('config_id')],
                    ];
                    $web->update('configurations', $sanitizeddata, $conditions);


                    $web->commit();

                    session::flash('success', 'Configuration Updated Successfully.');
                    redirect::to($baseurl . '/admin/configurations');
                }
            } catch (exception $e) {
                $web->roll_back();
                error_log($e->getMessage() . ': ' . $page->get_page_slug(), 3, __DIR__ . '/errors.log');
                session::flash('error', 'An unexpected error occurred. Please try again later or contact support if the issue persists.' . $e);
                redirect::to($baseurl . $page->get_page_slug() . '/' . $configid);
            }
        } else {
            $validation_errors = $validate->get_errors();
        }
    }
}


$canonical = $baseurl . $page->get_page_slug() . '/' . $configid;
