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
        'company_id' => ['=', $id],
    );

    $company = new listings();
    $s3 = new s3bucket();
    // delete image if exists from s3
    $image = $company->fetch('companies', $conditions)->logo_url;
    if ($image) {
        $s3->deleteImage($image);
    }

    if ($company->delete('companies', $conditions)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete company']);
    }
}
