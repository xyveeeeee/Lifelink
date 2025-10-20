<?php 
require_once 'check_session.php';
require_once 'db_connect.php';

$user_id = $_SESSION['user_id'];

// Fetch thelatest notifications for this user
$sql = "
    SELECT 
        id, 
        message, 
        DATE_FORMAT(created_at, '%b %e, %Y %h:%i %p') AS date
    FROM notifications
    WHERE user_id = ?
    ORDER BY created_at DESC
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die(json_encode(['error' => 'SQL prepare failed: ' . $conn->error]));
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

header('Content-Type: application/json');
echo json_encode($notifications);
?>
