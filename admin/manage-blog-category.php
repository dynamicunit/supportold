<?php
require(__DIR__ . '/../core/init.php');

$user = new users();
$page = new pages('/admin/manage-blog-category');
$view = new vwcommon($route[0]);
$web = new websettings();
$blog = new blog();
$function = new functions();

$validation_errors = array();


// check if route with the code exists
$blogcatid = isset($route['2']) ? $route['2'] : null;
$conditions = [
    'id' => ['=', $blogcatid]
];

// check if the designer id is valid
if ($blogcatid && !$blog->fetch('blog_categories', $conditions)) {
    $view->get_page404();
}
$table = 'blog_categories';
$conditions = [
    'id' => ['=', $blogcatid],
];

$data = $blog->fetch($table, $conditions);

if (input::exists('post')) {
    if (token::check(input::get('token'))) {

        $validate = new validate();
        $validate->check($_POST, array(
            'name' => array(
                'required' => true,
                'min' => 3,
                'max' => 50
            ),
            'slug' => array(
                'required' => true,
                'min' => 3,
                'max' => 20
            ),
        ));

        if ($validate->get_passed()) {
            try {
                $blog->begin_transaction();

                if (input::get('cat_id')) {
                    $sanitizeddata = sanitizer::sanitize([
                        'description' => input::get('name'),
                        'slug' => input::get('slug')
                    ]);
                    $conditions = [
                        'id' => ['=', input::get('cat_id')],
                    ];
                    $blog->update('blog_categories', $sanitizeddata, $conditions);


                    $blog->commit();

                    session::flash('success', 'Blog category Updated Successfully.');
                    redirect::to($baseurl . '/admin/blog-categories');
                } else {
                    $sanitizeddata = sanitizer::sanitize([
                        'website_id' => config::get('website/website_code'),
                        'description' => input::get('name'),
                        'slug' => input::get('slug')
                    ]);

                    $blog->add('blog_categories', $sanitizeddata);

                    $last_id = $blog->lastInsertId();
                    $blog->commit();
                    session::flash('success', 'Blog category Added Successfully.');
                    redirect::to($baseurl . '/admin/blog-categories');
                }
            } catch (exception $e) {
                $blog->roll_back();
                error_log($e->getMessage() . ': ' . $page->get_page_slug(), 3, __DIR__ . '/errors.log');
                session::flash('error', 'An unexpected error occurred. Please try again later or contact support if the issue persists.' . $e);
                redirect::to($baseurl . $page->get_page_slug() . '/' . $blogcatid);
            }
        } else {
            $validation_errors = $validate->get_errors();
        }
    }
}


$canonical = $baseurl . $page->get_page_slug() . '/' . $blogcatid;
