<?php
// ============================================================
// OffTheField – api/login.php
// Handles AJAX login via POST (fetch + FormData)
// Returns JSON response
// ============================================================

session_start();

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

require_once __DIR__ . '/config.php';

// ── Only accept POST ─────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

// ── Already logged in? ───────────────────────────────────────
if (isset($_SESSION['user_id'])) {
    echo json_encode(['success' => true, 'message' => 'Already logged in.', 'redirect' => '../pages/dashboard.html']);
    exit;
}

// ── Collect & sanitise inputs ────────────────────────────────
$email    = trim($_POST['email']    ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($email) || empty($password)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

// ── Query DB ─────────────────────────────────────────────────
$conn = getDBConnection();

$stmt = $conn->prepare(
    "SELECT id, username, email, password, role FROM users WHERE email = ? LIMIT 1"
);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Generic message – don't leak whether the email exists
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Invalid email or password. Please try again.']);
    $stmt->close();
    $conn->close();
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

// ── Verify password ──────────────────────────────────────────
if (!password_verify($password, $user['password'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Invalid email or password. Please try again.']);
    exit;
}

// ── Start session ────────────────────────────────────────────
session_regenerate_id(true);          // prevent session fixation

$_SESSION['user_id']   = $user['id'];
$_SESSION['username']  = $user['username'];
$_SESSION['email']     = $user['email'];
$_SESSION['role']      = $user['role'];
$_SESSION['logged_in'] = true;

echo json_encode([
    'success'  => true,
    'message'  => 'Login successful! Redirecting…',
    'user'     => [
        'id'       => $user['id'],
        'username' => $user['username'],
        'role'     => $user['role'],
    ],
    'redirect' => '../pages/dashboard.html'
]);
