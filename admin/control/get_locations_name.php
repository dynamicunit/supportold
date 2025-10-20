<?php
require(__DIR__ . '/../../core/init.php');

$user = new users();
$web = new websettings();
$response = [
    'status' => false,
    'message' => null
];

try {
    $id = input::get('id');
    $table = input::get('table_name');

    if ($id && $table) {
        $condition = [
            'id' => ['=', $id]
        ];
        $limit = '1';
        $response['status'] = true;
        $response['message'] = $web->fetch($table, $condition, '', $limit)->description;
    }
} catch (Exception $e) {
    $response['message'] = 'An error occurred: ' . $e->getMessage();
    // Optionally log the error
    error_log($e->getMessage());
}

header('Content-Type: application/json');
echo json_encode($response);
exit();
