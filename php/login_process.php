<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/db_connect.php'; 


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/Log In.php'); 
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    echo "<script>alert('Please provide username and password.'); window.history.back();</script>";
    exit;
}

$stmt = $conn->prepare("SELECT id, fullname, username, role, password FROM users WHERE username = ? LIMIT 1");
if (!$stmt) {
    error_log("Prepare failed: " . $conn->error);
    echo "<script>alert('Server error. Try again later.'); window.history.back();</script>";
    exit;
}
$stmt->bind_param("s", $username);

if (!$stmt->execute()) {
    error_log("Execute failed: " . $stmt->error);
    echo "<script>alert('Server error. Try again later.'); window.history.back();</script>";
    $stmt->close();
    exit;
}

$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $stmt->close(); 

    // DB is hashed and password verification to hash
    if (password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['username']  = $user['username'];
        $_SESSION['fullname']  = $user['fullname'];
        $_SESSION['role']      = strtolower($user['role']);
        $_SESSION['logged_in'] = true;

        $conn->close();

        // role specific access
        if ($_SESSION['role'] === 'admin') {
            header('Location: ../php/admin_dashboard.php');
            exit;
        } elseif ($_SESSION['role'] === 'doctor') {
            header('Location: ../html/doctor.php');
            exit;
        } elseif ($_SESSION['role'] === 'donor') {
            header('Location: ../html/donor.php');
            exit;
        } else {
            header('Location: ../html/splash.html');
            exit;
        }

    } else {
        // for doesnt mathc password
        $conn->close();
        echo "<script>alert('Invalid username or password.'); window.location.href='../html/Log In.php';</script>";
        exit;
    }
} else {
    $stmt->close();
    $conn->close();
    echo "<script>alert('Invalid username or password.'); window.location.href='../html/Log In.php';</script>";
    exit;
}
?>
