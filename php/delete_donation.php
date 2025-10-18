<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../html/Log In.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['donation_id'])) {
    $donation_id = intval($_POST['donation_id']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM donations WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $donation_id, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Donation deleted successfully.'); window.location.href='../html/history.php';</script>";
    } else {
        echo "<script>alert('Error deleting donation.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
