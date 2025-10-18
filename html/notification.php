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
        <li class="nav-link"><a href="notification.html">Notification</a><hr class="nav-underline"></li>
        <li class="nav-link"><a href="history.html">Donation History</a><hr class="nav-underline"></li>
        <li class="nav-link"><a href="profile.php">Profile</a><hr class="nav-underline"></li>
      </ul>
    </div>
  </nav>

  <div class="main-content">
    <button class="back-btn" onclick="location.href='donation.html'">← Back</button>

    <div class="page-header">
      <h1 class="page-title">Notifications</h1>
      <p class="page-subtitle">Stay updated with match requests, status changes, and system alerts.</p>
    </div>

    <div class="notification-item">
      <span class="notification-icon">🔔</span>
      <div class="notification-content">
        <div class="notification-message">A doctor has requested to view your profile.</div>
        <div class="notification-date">Oct 2, 2025</div>
      </div>
      <div class="notification-actions">
        <button class="btn btn-secondary btn-small">Mark as Read</button>
        <button class="btn btn-danger btn-small">Delete</button>
      </div>
    </div>

    <div class="notification-item">
      <span class="notification-icon">🔔</span>
      <div class="notification-content">
        <div class="notification-message">You have been matched with a patient.</div>
        <div class="notification-date">Oct 1, 2025</div>
      </div>
      <div class="notification-actions">
        <button class="btn btn-danger btn-small">Delete</button>
      </div>
    </div>

    <button class="btn btn-secondary" style="width:100%; margin-top: 20px;">Clear All Notifications</button>
  </div>

  <script src="donor_script.js"></script>
  <script>
    // nav highlight
    (function () {
      const path = location.pathname.split("/").pop() || 'notification.html';
      document.querySelectorAll('.nav-link').forEach(li=>{
        const a = li.querySelector('a');
        if(a && a.getAttribute('href') === path) {
          const hr = li.querySelector('.nav-underline');
          if (hr) hr.classList.add('default-underline');
        }
      });
    })();
  </script>
</body>
</html>
