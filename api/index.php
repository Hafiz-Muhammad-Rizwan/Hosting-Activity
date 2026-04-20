<?php
// OffTheField – root entry point
// Redirects unauthenticated users to login, authenticated to dashboard
session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: pages/dashboard.html');
} else {
    header('Location: pages/login.html');
}
exit;
