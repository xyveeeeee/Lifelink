<?php

require_once __DIR__ . '/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/register.php');
    exit;
}

$fullname = trim($_POST['fullname'] ?? '');
$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$role     = strtolower(trim($_POST['role'] ?? ''));
$password = $_POST['password'] ?? '';
$cpass    = $_POST['cpassword'] ?? '';

if ($fullname === '' || $username === '' || $email === '' || $role === '' || $password === '' || $cpass === '') {
    echo "<script>alert('Please fill in all fields.'); window.history.back();</script>";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Please enter a valid email address.'); window.history.back();</script>";
    exit;
}

if (!in_array($role, ['donor','doctor','admin'], true)) {
    echo "<script>alert('Invalid role selected.'); window.history.back();</script>";
    exit;
}

if ($password !== $cpass) {
    echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
    exit;
}

// it will go chik if already exis the username or email
$check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$check->bind_param("ss", $username, $email);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    echo "<script>alert('Username or email already taken.'); window.history.back();</script>";
    $check->close();
    $conn->close();
    exit;
}
$check->close();

// pass wash and inset
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (fullname, username, email, role, password) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $fullname, $username, $email, $role, $hash);

if ($stmt->execute()) {
    echo "<script>alert('Registration successful! Please sign in.'); window.location.href='../html/Log In.php';</script>";
} else {
    echo "<script>alert('Database error. Try again later.'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
