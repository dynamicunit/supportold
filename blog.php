<?php
require(__DIR__ . '/core/init.php');

$user = new users();
$page = new pages('/blog');
$view = new vwcommon($route[0]);
$blog = new blog();
$function = new functions();
$settings = new websettings();
$posts = null;

try {

    $columns = 'website_id, id, image, blog_title, meta_title, meta_desc, slug, created_at';
    $table = 'blogs';
    $joins = [];
    $conditions = [
        'website_id' => ['=', 1]
    ];
    $groupBy = '';
    $orderBy = 'RAND()';
    $limit = $common->get_value('per_page');
    $offset = $function->offset($currentpageno);

    if ($blog->fetchbyjson($columns, $table, $joins, $conditions, $groupBy, $orderBy, $limit, $offset)) {
        $dataarray = $function->validateJson($blog->fetchedjson());

        if (!empty($dataarray['data'])) {

            // Validation in case of wrong page number
            $totalPages = ceil($settings->get_total_count() / $common->get_value('per_page'));

            if ($currentpageno < 1 || $currentpageno > $totalPages || !is_numeric($currentpageno)) {
                header("Location: " . $baseurl . $page->get_page_slug() . "?page=" . $totalPages);
                exit;
            }

            // Check if decoding was successful
            if ($dataarray === null && json_last_error() !== JSON_ERROR_NONE) {
                echo 'Error decoding JSON: ' . json_last_error_msg();
            }

            // get product data
            $posts = $blog->get_blogslist($dataarray['data']);

            // Get the pagination
            $render_pagination = $settings->get_pagination(
                $dataarray['totalrecords'],
                $common->get_value('per_page'),
                $currentpageno,
                $page->get_page_slug(),
                $_SERVER['QUERY_STRING']
            );

        } else {
            $posts = '<div class="col-12 text-center">No blog post has been added yet.</div>';
            $render_pagination = '';
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

$canonical = $baseurl . '/' . $route[0];
