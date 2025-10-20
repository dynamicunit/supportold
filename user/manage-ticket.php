<?php
require(__DIR__ . '/../core/init.php');

$user = new users();
$page = new pages('/user/manage-ticket');
$view = new vwcommon($route[0]);
$web = new websettings();
$function = new functions();
$listing = new listings();

$ticket_id = $route['2'] ?? '';
$table = 'tickets';
$condition = [
    'ticket_id' => ['=', $ticket_id]
];
$limit = 1;

$ticket_data = $user->fetch($table, $condition, '', $limit);

$priority = $ticket_data && $ticket_data->priority ? $web->fetch('lookups', ['id' => ['=', $ticket_data->priority]])->description : '';
$assigned_to = $ticket_data && $ticket_data->assigned_user_id ? $web->fetch('users', ['id' => ['=', $ticket_data->assigned_user_id]])->full_name : '';


if ($ticket_id && !$ticket_data) {
    $view->get_page404();
}

// get ticket messages
try {
    $columns = 'a.id, a.message_body,a.ticket_id, b.full_name, a.created_at';
    $table = 'ticket_messages as a';
    $joins = [
        'JOIN users as b ON a.sender_id = b.id'
    ];
    $conditions = [
        'a.ticket_id' => ['=', $ticket_id]
    ];

    $groupBy = '';
    $orderBy = 'created_at desc';
    $limit = $common->get_value('per_page');
    $offset = $function->offset($currentpageno);

    if ($user->fetchbyjson($columns, $table, $joins, $conditions, $groupBy, $orderBy, $limit, $offset)) {
        $dataarray = $function->validateJson($user->fetchedjson());
        if (!empty($dataarray['data'])) {
            // get comments data
            $comments = $dataarray['data'];

        } else {
            $comments = [];
        }
    } else {
        throw new Exception('Query Failed.');
    }

} catch (Exception $e) {
    error_log($e->getMessage() . ': ' . $page->get_page_slug(), 3, __DIR__ . '/errors.log');

    if ($e->getMessage() === 'Blog posts not found.') {
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

$pictures = $user->fetchbyarray('ticket_pictures', $condition);


$statuses = $listing->get_statuslist_by_group($user->get_loggeduser()->user_group, $ticket_data->ticket_status ?? 0);
$current_status = $listing->get_currentstatus($ticket_data->ticket_status ?? '8');

$where = [
    'entity_id' => ['=', 10]
];
$cbo_app = $web->get_combo(
    $web->fetchbyarray('lookups', $where),
    array(
        'type' => 'select',
        'selected' => $ticket_data && $ticket_data->app_id ? array($ticket_data->app_id) : array(),
        'values' => 'single'
    )
);

$where = [
    'entity_id' => ['=', 13]
];
$cbo_issue_type = $web->get_combo(
    $web->fetchbyarray('lookups', $where),
    array(
        'type' => 'select',
        'selected' => $ticket_data && $ticket_data->issue_type ? array($ticket_data->issue_type) : array(),
        'values' => 'single'
    )
);


$where = [
    'entity_id' => ['=', 14]
];
$cbo_priority = $web->get_combo(
    $web->fetchbyarray('lookups', $where),
    array(
        'type' => 'select',
        'selected' => $ticket_data && $ticket_data->priority ? array($ticket_data->priority) : array('339'),
        'values' => 'single'
    )
);

$where = [
    '1' => ['=', 1]
];
$cbo_dep = $web->get_combo(
    $web->fetchbyarray('department', $where),
    array(
        'type' => 'select',
        'selected' => $ticket_data && $ticket_data->department_id ? array($ticket_data->department_id) : array('1'),
        'values' => 'single'
    ),
    'name',
    'dep_id'
);


$canonical = $page->get_page_slug();
