<?php
// php/verify_reset_token.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Secure session cookie settings
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => '',
    'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
require_once __DIR__ . '/db_connect.php';

// Ensure proper token handling 
$token = trim($_GET['token'] ?? '');
if ($token === '') {
    echo "<script>alert('Invalid or missing reset link.'); window.location.href='../html/forgot.php';</script>";
    exit;
}

// Verify token in Datbase 
$stmt = $conn->prepare("SELECT id, email, reset_expiry FROM users WHERE reset_token = ? LIMIT 1");
if (!$stmt) {
    error_log('verify_reset_token: DB prepare failed: ' . $conn->error);
    echo "<script>alert('Server error. Please try again later.'); window.location.href='../html/forgot.php';</script>";
    exit;
}

$stmt->bind_param('s', $token);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();

if (!$res || $res->num_rows === 0) {
    echo "<script>alert('Invalid or expired reset link.'); window.location.href='../html/forgot.php';</script>";
    exit;
}

$user = $res->fetch_assoc();

//Check expiry
if (empty($user['reset_expiry']) || strtotime($user['reset_expiry']) < time()) {
    echo "<script>alert('This reset link has expired. Please request a new one.'); window.location.href='../html/forgot.php';</script>";
    exit;
}

// Save token/email in session 
$_SESSION['reset_token'] = $token;
$_SESSION['reset_email'] = $user['email'] ?? null;

// Redirect correctly to reset form 
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$basePath = dirname(dirname($_SERVER['PHP_SELF'])); // goes up from /php to project root
$resetPage = $protocol . '://' . $host . $basePath . '/html/reset_password.php';

// Redirect to reset password page
header('Location: ' . $resetPage);
exit;
