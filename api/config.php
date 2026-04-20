<?php
// ============================================================
// OffTheField – Database Configuration
// ============================================================

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'offthefield_db');
define('DB_PORT', (int) (getenv('DB_PORT') ?: 3306));
define('DB_SSL_MODE', getenv('DB_SSL_MODE') ?: '');
define('DB_SSL_CA', getenv('DB_SSL_CA') ?: '');

// Base URL used for redirects (no trailing slash)
define('BASE_URL', getenv('BASE_URL') ?: 'http://localhost');

/**
 * Returns a MySQLi connection.  Exits with a JSON error on failure.
 */
function getDBConnection(): mysqli
{
    $conn = mysqli_init();
    if ($conn === false) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Database connection failed. Please try again later.'
        ]);
        exit;
    }

    $flags = 0;
    if (DB_SSL_MODE === 'REQUIRED') {
        $conn->ssl_set(null, null, DB_SSL_CA ?: null, null, null);
        $flags = MYSQLI_CLIENT_SSL;
    }

    $conn->real_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT, null, $flags);

    if ($conn->connect_error) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Database connection failed. Please try again later.'
        ]);
        exit;
    }

    $conn->set_charset('utf8mb4');
    return $conn;
}
