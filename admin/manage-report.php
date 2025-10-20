<?php
require(__DIR__ . '/../core/init.php');

$user = new users();
$page = new pages('/admin/manage-report');
$view = new vwcommon($route[0]);
$web = new websettings();
$function = new functions();

$validation_errors = array();


// check if route with the code exists
$reportId = isset($route['2']) ? $route['2'] : null;

$table = 'reports';
$conditions = [
    'id' => ['=', $reportId],
];

$data = $user->fetch($table, $conditions);

if ($reportId && !$data) {
    $view->get_page404();
}


if (input::exists('post')) {
    if (token::check(input::get('token'))) {

        $validate = new validate();
        $validate->check($_POST, array(
            'name' => array(
                'required' => true,
                'min' => 5,
                'max' => 50
            ),
            'description' => array(
                'required' => true,
                'min' => 10,
                'max' => 500
            ),
            'system_name' => array(
                'required' => true
            ),
            'url' => array(
                'required' => true,
                'min' => 5,
                'max' => 100
            ),
            'columns' => array(
                'required' => true,
                'min' => 10,
                'max' => 500
            ),
        ));

        if ($validate->get_passed()) {
            try {
                $user->begin_transaction();

                if (input::get('report_id')) {
                    $sanitizeddata = sanitizer::sanitize([
                        'report_name' => input::get('name'),
                        'description' => input::get('description'),
                        'system_name' => input::get('system_name'),
                        'columns' => input::get('columns'),
                        'report_url' => input::get('url'),
                    ]);
                    $conditions = [
                        'id' => ['=', input::get('report_id')],
                    ];
                    $user->update('reports', $sanitizeddata, $conditions);
                    $user->commit();

                    session::flash('success', 'Reports Updated Successfully.');
                    redirect::to($baseurl . '/admin/reports');
                } else {
                    $sanitizeddata = sanitizer::sanitize([
                        'report_name' => input::get('name'),
                        'description' => input::get('description'),
                        'system_name' => input::get('system_name'),
                        'columns' => input::get('columns'),
                        'report_url' => input::get('url'),
                    ]);
                    $user->add('reports', $sanitizeddata);


                    $user->commit();

                    session::flash('success', 'Reports Added Successfully.');
                    redirect::to($baseurl . '/admin/reports');
                }
            } catch (exception $e) {
                $user->roll_back();
                error_log($e->getMessage() . ': ' . $page->get_page_slug(), 3, __DIR__ . '/errors.log');
                session::flash('error', 'An unexpected error occurred. Please try again later or contact support if the issue persists.' . $e);
                redirect::to($baseurl . $page->get_page_slug() . '/' . $reportId);
            }
        } else {
            $validation_errors = $validate->get_errors();
        }
    }
}


$canonical = $baseurl . $page->get_page_slug() . '/' . $reportId;
