<?php
session_start();

// ✅ If user is not logged in, redirect
if (
    empty($_SESSION['logged_in']) ||
    empty($_SESSION['username']) ||
    empty($_SESSION['user_id'])
) {
    header('Location: ../html/Log In.php');
    exit;
}

// ✅ If page requires a specific role (e.g. $required_role = 'donor';), check it
if (isset($required_role) && isset($_SESSION['role']) && $_SESSION['role'] !== $required_role) {
    // Optional: show a message before redirect
    echo "<script>alert('Access denied: This page is for $required_role only.'); window.location.href='../html/Log In.php';</script>";
    exit;
}

// ✅ Optional: include database connection here if needed globally
require_once 'db_connect.php';
?>
