<?php
require(__DIR__ . '/../core/init.php');

$user = new users();
$page = new pages('/admin/manage-page');
$view = new vwcommon($route[0]);
$web = new websettings();
$function = new functions();

$validation_errors = array();


// check if route with the code exists
$pageid = isset($route['2']) ? $route['2'] : null;
$conditions = [
    'id' => ['=', $pageid]
];

// check if the designer id is valid
if ($pageid && !$web->fetch('pages', $conditions)) {
    $view->get_page404();
}
$table = 'pages';
$conditions = [
    'id' => ['=', $pageid],
];

$data = $web->fetch($table, $conditions);


if (input::exists('post')) {
    if (token::check(input::get('token'))) {

        $validate = new validate();
        $validate->check($_POST, array(
            'slug' => array(
                'required' => true,
                'min' => 3,
                'max' => 50
            ),
            'meta_title' => array(
                'required' => true,
                'min' => 3,
                'max' => 80
            ),

            'meta_desc' => array(
                'min' => 3,
                'max' => 150
            ),
            'meta_keywords' => array(
                'min' => 3,
                'max' => 150
            ),
            'page_title' => array(
                'required' => true,
                'min' => 3,
                'max' => 50
            ),

            'page_title_desc' => array(
                'min' => 3,
                'max' => 100
            ),
            'page_contents' => array(
                'min' => 3,
                'max' => 500
            ),
        ));

        if ($validate->get_passed()) {
            try {
                $web->begin_transaction();

                if (input::get('page_id')) {
                    $sanitizeddata = sanitizer::sanitize([
                        'slug' => input::get('slug'),
                        'meta_title' => input::get('meta_title'),
                        'meta_description' => input::get('meta_desc'),
                        'meta_keywords' => input::get('meta_keywords'),
                        'page_title' => input::get('page_title'),
                        'page_title_description' => input::get('page_title_desc'),
                        'page_body' => input::get('page_contents'),
                        'is_active' => input::get('approval_status'),
                    ]);
                    $conditions = [
                        'id' => ['=', input::get('page_id')],
                    ];
                    $web->update('pages', $sanitizeddata, $conditions);


                    $web->commit();

                    session::flash('success', 'Page Updated Successfully.');
                    redirect::to($baseurl . '/admin/pages');
                } else {
                    $sanitizeddata = sanitizer::sanitize([
                        'website_id' => config::get('website/website_code'),
                        'slug' => input::get('slug'),
                        'meta_title' => input::get('meta_title'),
                        'meta_description' => input::get('meta_desc'),
                        'meta_keywords' => input::get('meta_keywords'),
                        'page_title' => input::get('page_title'),
                        'page_title_description' => input::get('page_title_desc'),
                        'page_body' => input::get('page_contents'),
                        'is_active' => input::get('approval_status'),
                    ]);

                    $web->add('pages', $sanitizeddata);

                    $last_id = $web->lastInsertId();
                    $web->commit();
                    session::flash('success', 'Page Added Successfully.');
                    redirect::to($baseurl . '/admin/pages');
                }
            } catch (exception $e) {
                $web->roll_back();
                error_log($e->getMessage() . ': ' . $page->get_page_slug(), 3, __DIR__ . '/errors.log');
                session::flash('error', 'An unexpected error occurred. Please try again later or contact support if the issue persists.' . $e);
                redirect::to($baseurl . $page->get_page_slug() . '/' . $pageid);
            }
        } else {
            $validation_errors = $validate->get_errors();
        }
    }
}


$canonical = $baseurl . $page->get_page_slug() . '/' . $pageid;
