<?php
require_once 'db_connect.php';
session_start(); // ensure session is started

$action = $_POST['action'] ?? '';
$user_id = $_SESSION['user_id'] ?? null;

switch ($action) {
    // ADMIN: Send a notification to a specific user
    case 'add_notification':
        if (empty($_POST['user_id']) || empty($_POST['message'])) {
            die("Error: User ID and message are required.");
        }

        try {
            $stmt = $conn->prepare("
                INSERT INTO notifications (user_id, message, created_at)
                VALUES (?, ?, NOW())
            ");
            $stmt->bind_param("is", $_POST['user_id'], $_POST['message']);
            $stmt->execute();

            header("Location: ../html/admin.php?success=Notification+sent");
            exit;
        } catch (Exception $e) {
            die("Error adding notification: " . $e->getMessage());
        }

    //  USER: Mark notification as read
    case 'mark_read':
        if (!$user_id) exit("Unauthorized access.");
        $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $_POST['id'], $user_id);
        $stmt->execute();
        break;

    //  USER: Delete a specific notification
    case 'delete':
        if (!$user_id) exit("Unauthorized access.");
        $stmt = $conn->prepare("DELETE FROM notifications WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $_POST['id'], $user_id);
        $stmt->execute();
        break;

    //  USER: Clear all notifications
    case 'clear_all':
        if (!$user_id) exit("Unauthorized access.");
        $stmt = $conn->prepare("DELETE FROM notifications WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        break;
}

// Default redirect for user actions if not admin
if (!headers_sent() && $action !== 'add_notification') {
    header("Location: ../html/notification.php");
}
exit;
?>
