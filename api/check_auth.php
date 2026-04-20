<?php
// ============================================================
// OffTheField – api/check_auth.php
// Call this at the top of every protected page.
// Returns JSON {loggedIn, user} or redirects based on context.
// ============================================================

session_start();

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

if (!$isLoggedIn) {
    http_response_code(401);
    echo json_encode([
        'loggedIn' => false,
        'message'  => 'Unauthorised. Please log in.',
        'redirect' => '../pages/login.html'
    ]);
    exit;
}

echo json_encode([
    'loggedIn' => true,
    'user' => [
        'id'       => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'email'    => $_SESSION['email'],
        'role'     => $_SESSION['role'],
    ]
]);
