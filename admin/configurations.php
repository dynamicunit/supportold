<?php
require(__DIR__ . '/../core/init.php');

$user = new users();
$page = new pages('/admin/configurations');
$view = new vwcommon($route[0]);
$function = new functions();
$settings = new websettings();


try {
    // Get the data from the query

    $columns = 'id, property, property_value, created_at';
    $table = 'configurations';
    $joins = [];
    $conditions = [
        'website_id' => ['=', config::get('website/website_code')]
    ];

    $groupBy = '';
    $orderBy = '';
    $limit = $common->get_value('per_page');
    $offset = $function->offset($currentpageno);

    if ($settings->fetchbyjson($columns, $table, $joins, $conditions, $groupBy, $orderBy, $limit, $offset)) {
        $dataarray = $function->validateJson($settings->fetchedjson());
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
            $render = $dataarray['data'];

            // Get the pagination
            $render_pagination = $settings->get_pagination(
                $dataarray['totalrecords'],
                $common->get_value('per_page'),
                $currentpageno,
                $page->get_page_slug(),
                $_SERVER['QUERY_STRING']
            );
        } else {
            $render = [];
            $render_pagination = '';
        }
    } else {
        throw new Exception('Query Failed.');
    }

} catch (Exception $e) {
    error_log($e->getMessage() . ': ' . $page->get_page_slug(), 3, __DIR__ . '/errors.log');

    if ($e->getMessage() === 'Configurations not found.') {
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

$canonical = $baseurl . $page->get_page_slug();
