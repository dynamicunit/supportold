<?php
require __DIR__ . '/../../classes/session.class.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Generate and store the CSRF token in session
$token = md5(uniqid());
session::put('token', $token);

// Return the token as a JSON response
echo json_encode(['token' => $token]);
