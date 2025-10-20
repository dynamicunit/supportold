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

    $user = new users();
    $s3 = new s3bucket();

    $conditions = array(
        'ticket_id' => ['=', $id],
    );
    $pictures = $user->fetchbyarray('ticket_pictures', $conditions);
    try {
        $user->begin_transaction();
        if ($pictures) {
            foreach ($pictures as $picture) {
                $s3->deleteImage($picture->url);
            }
        }

        $user->delete('tickets', $conditions);
        $user->delete('ticket_pictures', $conditions);
        $user->delete('ticket_messages', $conditions);
        echo json_encode(['success' => true]);
        $user->commit();
    } catch (\Throwable $th) {
        error_log($th->getMessage() . ': ' . '/user/tickets', 3, __DIR__ . '/errors.log');
        echo json_encode(['success' => false, 'message' => 'Failed to delete ticket']);
    }

}
