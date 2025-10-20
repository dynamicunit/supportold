<?php
require(__DIR__ . '/../core/init.php');

$user = new users();
$page = new pages('/admin/manage-lookup-code');
$view = new vwcommon($route[0]);
$web = new websettings();
$function = new functions();

$validation_errors = array();


// check if route with the code exists
$lookupid = isset($route['2']) ? $route['2'] : null;
$conditions = [
    'id' => ['=', $lookupid]
];

// check if the designer id is valid
if ($lookupid && !$web->fetch('lookups', $conditions)) {
    $view->get_page404();
}
$table = 'lookups';
$conditions = [
    'id' => ['=', $lookupid],
];

$data = $web->fetch($table, $conditions);

$where = [
    'website_id' => ['=', 1]
];

$cbo_entity = $web->get_combo(
    $web->fetchbyarray('lookup_entities', $where, 'description'),
    array(
        'type' => 'select',
        'selected' => $data && $data->entity_id ? array($data->entity_id) : array(),
        'values' => 'single'
    )
);

if (input::exists('post')) {
    if (token::check(input::get('token'))) {

        $validate = new validate();
        $validate->check($_POST, array(
            'entity' => array(
                'required' => true,
            ),
            'description' => array(
                'required' => true,
                'min' => 3,
                'max' => 20
            )
        ));

        if ($validate->get_passed()) {
            try {
                $web->begin_transaction();

                if (input::get('lookup_id')) {
                    $sanitizeddata = sanitizer::sanitize([
                        'entity_id' => input::get('entity'),
                        'description' => input::get('description'),
                        'slug' => input::get('slug'),
                        'short_desc' => input::get('short-description'),
                        'is_active' => input::get('approve'),
                    ]);
                    $conditions = [
                        'id' => ['=', input::get('lookup_id')],
                    ];
                    $web->update('lookups', $sanitizeddata, $conditions);


                    $web->commit();

                    session::flash('success', 'Lookup Updated Successfully.');
                    redirect::to($baseurl . '/admin/lookup-codes');
                } else {
                    $sanitizeddata = sanitizer::sanitize([
                        'website_id' => config::get('website/website_code'),
                        'entity_id' => input::get('entity'),
                        'description' => input::get('description'),
                        'slug' => input::get('slug'),
                        'short_desc' => input::get('short-description'),
                        'is_active' => input::get('approve'),
                    ]);

                    $web->add('lookups', $sanitizeddata);

                    $last_id = $web->lastInsertId();
                    $web->commit();
                    session::flash('success', 'Lookup Added Successfully.');
                    redirect::to($baseurl . '/admin/lookup-codes');
                }
            } catch (exception $e) {
                $web->roll_back();
                error_log($e->getMessage() . ': ' . $page->get_page_slug(), 3, __DIR__ . '/errors.log');
                session::flash('error', 'An unexpected error occurred. Please try again later or contact support if the issue persists.' . $e);
                redirect::to($baseurl . $page->get_page_slug() . '/' . $lookupid);
            }
        } else {
            $validation_errors = $validate->get_errors();
        }
    }
}


$canonical = $baseurl . $page->get_page_slug() . '/' . $lookupid;
