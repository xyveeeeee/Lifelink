<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/db_connect.php';


$DEBUG = false;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/login.php');
    exit;
}


$token = $_SESSION['reset_token'] ?? '';
if (empty($token) && !empty($_POST['reset_token'])) {
    $token = trim($_POST['reset_token']);
}

// Get posted passwords
$password = trim($_POST['password'] ?? '');
$confirm  = trim($_POST['confirm'] ?? '');

// Debugging helper 
if ($DEBUG) {
    echo "<pre>DEBUG SESSION token set: " . (!empty($_SESSION['reset_token']) ? 'yes' : 'no') . PHP_EOL;
    echo "DEBUG POST reset_token present: " . (!empty($_POST['reset_token']) ? 'yes' : 'no') . PHP_EOL;
    echo "DEBUG POST have password: " . (!empty($password) ? 'yes' : 'no') . PHP_EOL;
    echo "</pre>";
}

//validation
if ($token === '') {
    error_log('reset_password_process: no token provided (session and POST empty)');
    echo "<script>alert('Invalid or missing reset token. Please request a new reset link.'); window.location.href='../html/forgot.php';</script>";
    exit;
}
if ($password === '' || $confirm === '') {
    echo "<script>alert('Please provide and confirm your new password.'); window.history.back();</script>";
    exit;
}
if ($password !== $confirm) {
    echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
    exit;
}
if (strlen($password) < 6) {
    echo "<script>alert('Password must be at least 6 characters long.'); window.history.back();</script>";
    exit;
}

// Verify token in DataB and that it's not expired
$stmt = $conn->prepare("SELECT id, reset_expiry FROM users WHERE reset_token = ? LIMIT 1");
if (!$stmt) {
    error_log('reset_password_process: DB prepare failed: ' . $conn->error);
    echo "<script>alert('Server error. Please try again later.'); window.history.back();</script>";
    exit;
}
$stmt->bind_param('s', $token);
if (!$stmt->execute()) {
    error_log('reset_password_process: DB execute failed: ' . $stmt->error);
    echo "<script>alert('Server error. Please try again later.'); window.history.back();</script>";
    $stmt->close();
    exit;
}
$res = $stmt->get_result();
if (!$res || $res->num_rows === 0) {
    // token not found
    error_log('reset_password_process: token not found or already used');
    $stmt->close();
    // Clear stale session token if present
    unset($_SESSION['reset_token'], $_SESSION['reset_email']);
    echo "<script>alert('Invalid or expired token. Please request a new link.'); window.location.href='../html/forgot.php';</script>";
    exit;
}
$user = $res->fetch_assoc();
$stmt->close();

// Check expiry 
if (empty($user['reset_expiry']) || strtotime($user['reset_expiry']) < time()) {
    error_log('reset_password_process: token expired for user id ' . intval($user['id']));
    unset($_SESSION['reset_token'], $_SESSION['reset_email']);
    echo "<script>alert('This reset link has expired. Please request a new one.'); window.location.href='../html/forgot.php';</script>";
    exit;
}

// update password (hash) and clear token/expiry
$hash = password_hash($password, PASSWORD_DEFAULT);
$upd = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE id = ?");
if (!$upd) {
    error_log('reset_password_process: DB prepare failed (update): ' . $conn->error);
    echo "<script>alert('Server error. Please try again later.'); window.history.back();</script>";
    exit;
}
$upd->bind_param('si', $hash, $user['id']);
if (!$upd->execute()) {
    error_log('reset_password_process: update execute failed: ' . $upd->error);
    $upd->close();
    echo "<script>alert('Server error. Please try again later.'); window.history.back();</script>";
    exit;
}
$upd->close();

// Clear session token/email for safety
unset($_SESSION['reset_token'], $_SESSION['reset_email']);

// Recret session id and confirm success
session_regenerate_id(true);
echo "<script>alert('Password successfully reset. You can now log in.'); window.location.href='../html/Log In.php';</script>";
exit;
