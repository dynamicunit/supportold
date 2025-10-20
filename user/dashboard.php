<?php
require(__DIR__ . '/../core/init.php');

$user = new users();
$page = new pages('/user/dashboard');
$view = new vwcommon($route[0]);
$listing = new listings();
$function = new functions();

// get total tickets of current user
$condition = [
    'created_user_id' => ['=', $user->get_loggeduser()->id],
];

$total_tickets = $listing->fetchbyarray('tickets', $condition)->count() ?? 0;

// open ticket count
$condition = [
    'created_user_id' => ['=', $user->get_loggeduser()->id],
    'ticket_status' => ['=', '2'],
];
$open_tickets = $listing->fetchbyarray('tickets', $condition)->count() ?? 0;

// closed ticket count
$condition = [
    'created_user_id' => ['=', $user->get_loggeduser()->id],
    'ticket_status' => ['=', '7'],
];
$closed_tickets = $listing->fetchbyarray('tickets', $condition)->count() ?? 0;


// latest tickets

try {
    // Get the data from the query

    $columns = 'a.ticket_id, a.issue_title,a.issue_description,a.assigned_user_id,c.status_name, a.created_at';
    $table = 'tickets as a';
    $joins = [
        'JOIN ticket_statuses as c ON a.ticket_status = c.status_id',
    ];
    $conditions = [
        'a.created_user_id' => ['=', $user->get_loggeduser()->id]
    ];

    $groupBy = '';
    $orderBy = 'created_at desc';
    $limit = $common->get_value('per_page');
    $offset = $function->offset($currentpageno);

    if ($listing->fetchbyjson($columns, $table, $joins, $conditions, $groupBy, $orderBy, $limit, $offset)) {
        $dataarray = $function->validateJson($listing->fetchedjson());
        if (!empty($dataarray['data'])) {

            // Validation in case of wrong page number
            $totalPages = ceil($listing->get_total_count() / $common->get_value('per_page'));

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
            $render_pagination = $listing->get_pagination(
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

    if ($e->getMessage() === 'Tickets not found.') {
        http_response_code(404);
        include(__DIR__ . '/../404.php');
        exit;
    } elseif ($e->getMessage() === 'Query failed.') {
        http_response_code(500);
        include(__DIR__ . '/../500.php');
        exit;
    } else {
        http_response_code(404);
        include(__DIR__ . '/../404.php');
        exit;
    }
}


$canonical = $page->get_page_slug();
