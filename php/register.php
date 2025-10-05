<?php

require_once __DIR__ . '/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/register.html');
    exit;
}

$fullname = trim($_POST['fullname'] ?? '');
$username = trim($_POST['username'] ?? '');
$role     = strtolower(trim($_POST['role'] ?? ''));
$password = $_POST['password'] ?? '';
$cpass    = $_POST['cpassword'] ?? '';

if ($fullname === '' || $username === '' || $role === '' || $password === '' || $cpass === '') {
    echo "<script>alert('Please fill in all fields.'); window.history.back();</script>";
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

$check = $conn->prepare("SELECT id FROM users WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    echo "<script>alert('Username already taken.'); window.history.back();</script>";
    $check->close();
    $conn->close();
    exit;
}
$check->close();

// pass Hash and insertion dita
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (fullname, username, role, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $fullname, $username, $role, $hash);

if ($stmt->execute()) {
    echo "<script>alert('Registration successful! Please sign in.'); window.location.href='../html/Log In.php';</script>";
} else {
    echo "<script>alert('Database error. Try again later.'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
