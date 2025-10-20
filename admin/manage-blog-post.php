<?php
require(__DIR__ . '/../core/init.php');

$user = new users();
$page = new pages('/admin/manage-blog-post');
$view = new vwcommon($route[0]);
$web = new websettings();
$blog = new blog();
$function = new functions();

$validation_errors = array();


// check if route with the code exists
$blogid = isset($route['2']) ? $route['2'] : null;
$conditions = [
    'id' => ['=', $blogid]
];

// check if the designer id is valid
if ($blogid && !$blog->fetch('blogs', $conditions)) {
    $view->get_page404();
}
$table = 'blogs';
$conditions = [
    'id' => ['=', $blogid],
];

$data = $blog->fetch($table, $conditions);

$where = [
    'website_id' => ['=', config::get('website/website_code')]
];
$cbo_category = $web->get_combo(
    $web->fetchbyarray('blog_categories', $where),
    array(
        'type' => 'select',
        'selected' => $data && $data->category_id ? explode(',', $data->category_id) : array(),
        'values' => 'multiple'
    )
);

if (input::exists('post')) {
    if (token::check(input::get('token'))) {

        $validate = new validate();
        $validate->check($_POST, array(
            'title' => array(
                'required' => true,
                'min' => 3,
                'max' => 150
            ),
            'meta-title' => array(
                'required' => true,
                'min' => 3,
                'max' => 100
            ),

            'meta-desc' => array(
                'required' => true,
                'min' => 3,
                'max' => 160
            ),
            'body' => array(
                'required' => true,
            ),
        ));

        if ($validate->get_passed()) {
            $s3Bucket = new s3bucket();
            try {
                $blog->begin_transaction();
                $result = input::get('old-image');
                if (!empty($_FILES['blog-img']['name'])) {
                    if (input::get('old-image') != '') {
                        $s3Bucket->deleteImage(input::get('old-image'));
                    }
                    $result = $s3Bucket->addImage($_FILES['blog-img']);
                } elseif (input::get('image-exists') == "false") {
                    if (input::get('old-image') != '') {
                        $s3Bucket->deleteImage(input::get('old-image'));
                    }
                    $result = '';
                }
                if (input::get('blg_id')) {
                    $sanitizeddata = sanitizer::sanitize([
                        'image' => $result,
                        'meta_title' => input::get('meta-title'),
                        'slug' => $function->to_slug(input::get('meta-title')) . '-' . input::get('blg_id'),
                        'meta_desc' => input::get('meta-desc'),
                        'blog_title' => input::get('title'),
                        'blog_body' => input::get('body'),
                        'category_id' => input::get('category'),
                    ]);
                    $conditions = [
                        'id' => ['=', input::get('blg_id')],
                    ];
                    $blog->update('blogs', $sanitizeddata, $conditions);


                    $blog->commit();

                    session::flash('success', 'Blog Updated Successfully.');
                    redirect::to($baseurl . '/admin/blog-posts');
                } else {
                    $sanitizeddata = sanitizer::sanitize([
                        'website_id' => config::get('website/website_code'),
                        'user_id' => $user->get_loggeduser()->id,
                        'image' => $result,
                        'meta_title' => input::get('meta-title'),
                        'slug' => $function->to_slug(input::get('meta-title')),
                        'meta_desc' => input::get('meta-desc'),
                        'blog_title' => input::get('title'),
                        'blog_body' => input::get('body'),
                        'category_id' => input::get('category'),
                    ]);

                    $blog->add('blogs', $sanitizeddata);

                    $last_id = $blog->lastInsertId();

                    $sanitizeddata = sanitizer::sanitize([
                        'slug' => $function->to_slug(input::get('meta-title')) . '-' . $last_id
                    ]);
                    $conditions = [
                        'id' => ['=', $last_id],
                    ];

                    $blog->update('blogs', $sanitizeddata, $conditions);

                    $blog->commit();
                    session::flash('success', 'Blog Added Successfully.');
                    redirect::to($baseurl . '/admin/blog-posts');
                }
            } catch (exception $e) {
                $blog->roll_back();
                error_log($e->getMessage() . ': ' . $page->get_page_slug(), 3, __DIR__ . '/errors.log');
                session::flash('error', 'An unexpected error occurred. Please try again later or contact support if the issue persists.' . $e);
                redirect::to($baseurl . $page->get_page_slug() . '/' . $blogid);
            }
        } else {
            $validation_errors = $validate->get_errors();
        }
    }
}


$canonical = $baseurl . $page->get_page_slug() . '/' . $blogid;
