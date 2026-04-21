<?php
// ============================================================
// OffTheField – api/logout.php
// Destroys the session and returns JSON (then JS redirects)
// ============================================================

session_start();

// Wipe session data
$_SESSION = [];

// Delete the session cookie
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

session_destroy();

header('Content-Type: application/json');
echo json_encode([
    'success'  => true,
    'message'  => 'You have been logged out successfully.',
    'redirect' => 'login.html'
]);
