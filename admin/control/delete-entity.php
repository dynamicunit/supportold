<?php
require(__DIR__ . '/../../core/init.php');

$response = ['status' => 'error', 'message' => 'Invalid request.'];

if (input::exists('post')) {

    $id = $_POST['id'];
    $token = $_POST['token'];

    // Validate the CSRF token
    if (!token::check($token)) {
        echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
        exit;
    }

    // Validate the ID
    if (empty($id) || !is_numeric($id)) {
        echo json_encode(['success' => false, 'message' => 'Invalid ID']);
        exit;
    }

    $conditions = array(
        'id' => ['=', $id],
        'website_id' => ['=', $basecode]
    );

    $web = new websettings();

    if ($web->delete('lookup_entities', $conditions)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete Entity']);
    }
}
