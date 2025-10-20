<?php
require(__DIR__ . '/../core/init.php');

$user = new users();
$page = new pages('/admin/manage-sla-policies');
$view = new vwcommon($route[0]);
$web = new websettings();
$function = new functions();

$validation_errors = array();


// check if route with the code exists
$sla_id = isset($route['2']) ? $route['2'] : null;
$conditions = [
    'sla_id' => ['=', $sla_id]
];

// check if the designer id is valid
if ($sla_id && !$web->fetch('sla_policies', $conditions)) {
    $view->get_page404();
}
$table = 'sla_policies';
$conditions = [
    'sla_id' => ['=', $sla_id],
];

$data = $web->fetch($table, $conditions);


if (Input::exists('post')) {
    if (Token::check(Input::get('token'))) {

        $validate = new Validate();
        $validate->check($_POST, array(
            'sla_name' => array(
                'required' => true,
                'min' => 3,
                'max' => 100
            ),
            'description' => array(
                'required' => true,
                'min' => 5,
                'max' => 500
            ),
            'response_time_hours' => array(
                'required' => true,
                'numeric' => true
            ),
            'resolution_time_hours' => array(
                'required' => true,
                'numeric' => true
            ),
            'priority_level' => array(
                'required' => true
            ),
            'support_hours' => array(
                'required' => true
            ),
            'penalty_description' => array(
                'required' => true,
                'min' => 5,
                'max' => 255
            )
        ));

        if ($validate->get_passed()) {
            try {
                $web->begin_transaction();
                $sanitizeddata = Sanitizer::sanitize([
                    'sla_name' => Input::get('sla_name'),
                    'description' => Input::get('description'),
                    'response_time_hours' => Input::get('response_time_hours'),
                    'resolution_time_hours' => Input::get('resolution_time_hours'),
                    'priority_level' => Input::get('priority_level'),
                    'support_hours' => Input::get('support_hours'),
                    'penalty_description' => Input::get('penalty_description'),
                    'is_active' => Input::get('is_active') ?? '0',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                if (Input::get('sla_id')) {
                    // Update SLA
                    $sanitizeddata['updated_at'] = date('Y-m-d H:i:s');

                    $conditions = [
                        'sla_id' => ['=', Input::get('sla_id')],
                    ];

                    $web->update('sla_policies', $sanitizeddata, $conditions);

                    $web->commit();
                    Session::flash('success', 'SLA Updated Successfully.');
                    Redirect::to($baseurl . '/admin/sla-policies');
                } else {
                    // Add SLA
                    $web->add('sla_policies', $sanitizeddata);

                    $last_id = $web->lastInsertId();
                    $web->commit();

                    Session::flash('success', 'SLA Added Successfully.');
                    Redirect::to($baseurl . '/admin/sla-policies');
                }
            } catch (Exception $e) {
                $web->roll_back();
                error_log($e->getMessage() . ': ' . $page->get_page_slug(), 3, __DIR__ . '/errors.log');
                Session::flash('error', 'An unexpected error occurred. Please try again later. ');
                Redirect::to($baseurl . $page->get_page_slug() . '/' . Input::get('sla_id'));
            }
        } else {
            $validation_errors = $validate->get_errors();
        }
    }
}



$canonical = $baseurl . $page->get_page_slug() . '/' . $sla_id;
