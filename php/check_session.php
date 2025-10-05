<?php
session_start();

if (empty($_SESSION['logged_in']) || empty($_SESSION['username'])) {
    header('Location: ../html/Log In.php');
    exit;
}

if (isset($required_role) && $_SESSION['role'] !== $required_role) {
    header('Location: ../html/Log In.php');
    exit;
}
?>
