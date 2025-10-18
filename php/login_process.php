<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/Log In.php'); 
    exit;
}

$usernameOrEmail = trim($_POST['username'] ?? ''); // <--- dito sa part na ito tatangapin nya both username and email
$password = $_POST['password'] ?? '';

if ($usernameOrEmail === '' || $password === '') {
    echo "<script>alert('Please provide username/email and password.'); window.history.back();</script>";
    exit;
}

// it wil allow user to log i n using username or email
$stmt = $conn->prepare("SELECT id, fullname, username, email, role, password FROM users WHERE username = ? OR email = ? LIMIT 1");
if (!$stmt) {
    error_log("Prepare failed: " . $conn->error);
    echo "<script>alert('Server error. Try again later.'); window.history.back();</script>";
    exit;
}

$stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);

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

    if (password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['username']  = $user['username'];
        $_SESSION['fullname']  = $user['fullname'];
        $_SESSION['email']     = $user['email'];
        $_SESSION['role']      = strtolower($user['role']);
        $_SESSION['logged_in'] = true;

        $conn->close();

        // spisipik access por the role
        if ($_SESSION['role'] === 'admin') {
            header('Location: ../html/admin.php');
            exit;
        } elseif ($_SESSION['role'] === 'doctor') {
            header('Location: ../html/doctor_panel/landing_page.php');
            exit;
        } elseif ($_SESSION['role'] === 'donor') {
            header('Location: ../html/donation.php');
            exit;
        } else {
            header('Location: ../html/splash.php');
            exit;
        }

    } else {
        $conn->close();
        echo "<script>alert('Invalid username/email or password.'); window.location.href='../html/Log In.php';</script>";
        exit;
    }
} else {
    $stmt->close();
    $conn->close();
    echo "<script>alert('Invalid username/email or password.'); window.location.href='../html/Log In.php';</script>";
    exit;
}
?>
