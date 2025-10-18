<?php
require_once 'db_connect.php';

if (!isset($_SESSION)) session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../html/Log In.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// ORGAN donations
$stmt1 = $conn->prepare("SELECT * FROM donations WHERE user_id = ? AND donation_type = 'Organ' ORDER BY created_at DESC");
$stmt1->bind_param("i", $user_id);
$stmt1->execute();
$organDonations = $stmt1->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt1->close();

// BLOOD donations
$stmt2 = $conn->prepare("SELECT * FROM donations WHERE user_id = ? AND donation_type = 'Blood' ORDER BY created_at DESC");
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$bloodDonations = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt2->close();

$conn->close();
?>
