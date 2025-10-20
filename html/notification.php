<?php 
require_once '../php/check_session.php';
require_once '../php/db_connect.php'; 

$user_id = $_SESSION['user_id']; // por logged-in specific user
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>LifeLink - Notifications</title>
  <link rel="stylesheet" href="../css/donor_style.css">
</head>
<body>
  <!-- NAVIGATION BAR -->
  <nav class="nav-bar">
    <div class="nav-container">
      <div class="logo">
        <img src="../image/logo.png" alt="LifeLink Logo" class="logo-icon">
        <h2 class="web-title">LifeLink</h2>
      </div>
      <ul class="nav-menu">
        <li class="nav-link"><a href="donation.php">Donation</a><hr class="nav-underline"></li>
        <li class="nav-link"><a href="notification.php">Notification</a><hr class="nav-underline"></li>
        <li class="nav-link"><a href="history.php">Donation History</a><hr class="nav-underline"></li>
        <li class="nav-link"><a href="profile.php">Profile</a><hr class="nav-underline"></li>
      </ul>
    </div>
  </nav>

  <div class="main-content">
    <button class="back-btn" onclick="location.href='donation.php'">← Back</button>

    <div class="page-header">
      <h1 class="page-title">Notifications</h1>
      <p class="page-subtitle">Stay updated with match requests, status changes, and system alerts.</p>
    </div>

    <div id="notification-container">
      <p>Loading notifications...</p>
    </div>

    <form method="POST" action="../php/notification_action.php" style="margin-top:20px;">
      <input type="hidden" name="action" value="clear_all">
      <button class="btn btn-secondary" style="width:100%;">Clear All Notifications</button>
    </form>
  </div>

  <script>
    async function loadNotifications() {
      try {
        const res = await fetch("../php/fetch_notifications.php");
        const data = await res.json();

        const container = document.getElementById("notification-container");
        container.innerHTML = "";

        if (data.length === 0) {
          container.innerHTML = "<p style='text-align:center;margin-top:30px;'>No notifications yet.</p>";
          return;
        }

        data.forEach(notif => {
          const item = document.createElement("div");
          item.className = `notification-item ${notif.is_read == 1 ? 'read' : ''}`;
          item.innerHTML = `
            <span class="notification-icon">🔔</span>
            <div class="notification-content">
              <div class="notification-message">${notif.message}</div>
              <div class="notification-date">${notif.date}</div>
            </div>
            <div class="notification-actions">
              ${notif.is_read == 0 ? `
                <form method="POST" action="../php/notification_action.php" style="display:inline;">
                  <input type="hidden" name="id" value="${notif.id}">
                  <input type="hidden" name="action" value="mark_read">
                  <button class="btn btn-secondary btn-small">Mark as Read</button>
                </form>` : ''}
              <form method="POST" action="../php/notification_action.php" style="display:inline;">
                <input type="hidden" name="id" value="${notif.id}">
                <input type="hidden" name="action" value="delete">
                <button class="btn btn-danger btn-small">Delete</button>
              </form>
            </div>`;
          container.appendChild(item);
        });
      } catch (err) {
        console.error("Error loading notifications:", err);
      }
    }

    // Load immediately and every 5 seconds
    loadNotifications();
    setInterval(loadNotifications, 5000);
  </script>
</body>
</html>
