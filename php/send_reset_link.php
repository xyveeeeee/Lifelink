<?php
// php/send_reset_link.php
session_start();
require_once __DIR__ . '/db_connect.php';
require __DIR__ . '/../vendor/autoload.php'; // PHPMailer 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* CONFIGIRATION */
$smtpHost = getenv(name: 'SMTP_HOST') ?: 'smtp.gmail.com';
$smtpUser = getenv('SMTP_USER') ?: 'lifelinkprofessional@gmail.com';
$smtpPass = getenv('SMTP_PASS') ?: 'jrxj vjna iwhu bisr'; 
$smtpPort = getenv('SMTP_PORT') ?: 587;
$fromName = 'LifeLink Support';

$tokenLifetime = 15 * 60; // ecpyriy tym

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/forgot.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Please enter a valid email address.'); window.history.back();</script>";
    exit;
}

/* Find user by email */
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
if (!$stmt) {
    error_log('send_reset_link: DB prepare failed: ' . $conn->error);
    echo "<script>alert('Server error. Please try again later.'); window.history.back();</script>";
    exit;
}
$stmt->bind_param('s', $email);
$stmt->execute();
$res = $stmt->get_result();

if (!$res) {
    $stmt->close();
    error_log('send_reset_link: DB get_result failed');
    echo "<script>alert('Server error. Please try again later.'); window.history.back();</script>";
    exit;
}

/* If email not found, it provide specific all or genralized emaik*/
if ($res->num_rows === 0) {
    $stmt->close();
    echo "<script>alert('If that email is registered, a reset link has been sent.'); window.location.href='../html/Log In.php';</script>";
    exit;
}

$user = $res->fetch_assoc();
$stmt->close();

/* Create secure token & expiry and store it */
$token = bin2hex(random_bytes(32));
$expiry = date('Y-m-d H:i:s', time() + $tokenLifetime);

$upd = $conn->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE id = ?");
if (!$upd) {
    error_log('send_reset_link: DB prepare failed (update): ' . $conn->error);
    echo "<script>alert('Server error. Please try again later.'); window.history.back();</script>";
    exit;
}
$upd->bind_param('ssi', $token, $expiry, $user['id']);
$ok = $upd->execute();
$upd->close();

if (!$ok) {
    error_log('send_reset_link: failed to save token for user id ' . intval($user['id']));
    echo "<script>alert('Server error. Please try again later.'); window.history.back();</script>";
    exit;
}

/* Build a correct absolute URL for the reset link */
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Compute the application base path (e.g. "/Lifelink")
$scriptDir = dirname($_SERVER['PHP_SELF']); 
$appBase = dirname($scriptDir);          
$appBase = $appBase === DIRECTORY_SEPARATOR ? '' : rtrim($appBase, '/\\');

$baseUrl = $protocol . '://' . $host . $appBase; 

$resetLink = $baseUrl . '/php/verify_reset_token.php?token=' . urlencode($token);

/* Send the email via PHPMailer */
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = $smtpHost;
    $mail->SMTPAuth   = true;
    $mail->Username   = $smtpUser;
    $mail->Password   = $smtpPass;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = (int)$smtpPort;

    $mail->setFrom($smtpUser, $fromName);
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'LifeLink: Password reset link';
    $mail->Body    = "
      <p>You requested a password reset. Click the link below to reset your password. The link expires in 15 minutes.</p>
      <p><a href=\"" . htmlspecialchars($resetLink) . "\">Reset your LifeLink password</a></p>
      <p>If you did not request this, ignore this message.</p>
    ";

    $mail->send();

    $_SESSION['reset_email'] = $email;
    echo "<script>alert('If that email is registered, a reset link has been sent.'); window.location.href='../html/Log In.php';</script>";
    exit;
} catch (Exception $e) {
    error_log('send_reset_link: PHPMailer error: ' . $mail->ErrorInfo . ' Exception: ' . $e->getMessage());
    echo "<script>alert('Failed to send email. Please try again later.'); window.history.back();</script>";
    exit;
}
