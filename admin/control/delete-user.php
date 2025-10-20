<?php
require(__DIR__ . '/../../core/init.php');

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
    $img = $user->fetch('users', ['id' => ['=', $id]])->profile_image;
    if ($img) {
        $s3->deleteImage($img);
    }
    if ($user->delete('users', ['id' => ['=', $id]])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete author']);
    }
}
