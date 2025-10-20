<?php
require(__DIR__ . '/core/init.php');

$user = new users();
$page = new pages('/blog-post');
$view = new vwcommon($route[0]);
$blog = new blog();
$function = new functions();

if (!($route[1] ?? null)) {
    $view->get_page404();
}
// get blog details
$row = $blog->get_blogpost($route[1]);

if ($blog->get_total_count() <> 1) {
    http_response_code(404);
    include(__DIR__ . '/404.php');
    exit;
} else {
    $metatitle = $row->meta_title;
    $metadesc = $row->meta_desc;
}

$blogs_list_html = '';
// Get the data from the query
try {

    $columns = 'website_id, id, image, blog_title, meta_title, meta_desc, slug, created_at';
    $table = 'blogs';
    $joins = [];
    $conditions = [
        'website_id' => ['=', 1]
    ];
    $groupBy = '';
    $orderBy = 'RAND()';
    $limit = '3';
    $offset = '';

    if ($blog->fetchbyjson($columns, $table, $joins, $conditions, $groupBy, $orderBy, $limit, $offset)) {
        $dataarray = $function->validateJson($blog->fetchedjson());
        if (!empty($dataarray['data'])) {
            $blogs_list_html = $blog->get_blogslist($dataarray['data']);
        } else {
            $blogs_list_html = '<div class="col-lg-10 offset-lg-1 text-center">No blog post has been added yet.</div>';
        }
    } else {
        throw new Exception('Query failed.');
    }
} catch (Exception $e) {
    error_log(date('Y-m-d H:i:s') . '-' . $e->getMessage() . ': ' . $page->get_page_slug(), 3, __DIR__ . '/errors.log');

    if ($e->getMessage() === 'Product not found.') {
        http_response_code(404);
        include(__DIR__ . '/404.php');
        exit;
    } elseif ($e->getMessage() === 'Query failed.') {
        http_response_code(500);
        include(__DIR__ . '/500.php');
        exit;
    } else {
        http_response_code(404);
        include(__DIR__ . '/404.php');
        exit;
    }
}


$canonical = $baseurl . $page->get_page_slug() . '/' . $route[1];
