<?php
require(__DIR__ . '/../core/init.php');

$user = new users();
$page = new pages('/admin/manage-entity');
$view = new vwcommon($route[0]);
$web = new websettings();
$function = new functions();

$validation_errors = array();


// check if route with the code exists
$entityId = isset($route['2']) ? $route['2'] : null;
$conditions = [
    'id' => ['=', $entityId]
];

// check if the designer id is valid
if ($entityId && !$web->fetch('lookup_entities', $conditions)) {
    $view->get_page404();
}
$table = 'lookup_entities';
$conditions = [
    'id' => ['=', $entityId],
];

$data = $web->fetch($table, $conditions);


if (input::exists('post')) {
    if (token::check(input::get('token'))) {

        $validate = new validate();
        $validate->check($_POST, array(
            'name' => array(
                'required' => true,
                'min' => 3,
                'max' => 100
            ),
            'description' => array(
                'required' => true,
                'min' => 3,
                'max' => 100
            ),
        ));

        if ($validate->get_passed()) {
            try {
                $web->begin_transaction();

                if (input::get('entity_id')) {
                    $sanitizeddata = sanitizer::sanitize([
                        'name' => input::get('name'),
                        'description' => input::get('description'),
                    ]);
                    $conditions = [
                        'id' => ['=', input::get('entity_id')],
                    ];
                    $web->update('lookup_entities', $sanitizeddata, $conditions);
                    $web->commit();

                    session::flash('success', 'Entity Updated Successfully.');
                    redirect::to($baseurl . '/admin/entities');
                } else {
                    $sanitizeddata = sanitizer::sanitize([
                        'website_id' => 1,
                        'name' => input::get('name'),
                        'description' => input::get('description'),
                    ]);
                    $web->add('lookup_entities', $sanitizeddata);


                    $web->commit();

                    session::flash('success', 'Entity Updated Successfully.');
                    redirect::to($baseurl . '/admin/entities');
                }
            } catch (exception $e) {
                $web->roll_back();
                error_log($e->getMessage() . ': ' . $page->get_page_slug(), 3, __DIR__ . '/errors.log');
                session::flash('error', 'An unexpected error occurred. Please try again later or contact support if the issue persists.' . $e);
                redirect::to($baseurl . $page->get_page_slug() . '/' . $entityId);
            }
        } else {
            $validation_errors = $validate->get_errors();
        }
    }
}


$canonical = $baseurl . $page->get_page_slug() . '/' . $entityId;
