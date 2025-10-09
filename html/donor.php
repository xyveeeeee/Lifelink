<?php
$required_role = 'donor';
require_once '../php/check_session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeLink - Organ Donation Platform</title>
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
            <li class="nav-link" onclick="showPage('donation')">
                <a>Donation</a>
                <hr class="nav-underline default-underline">
            </li>
            <li class="nav-link" onclick="showPage('notification')">
                <a>Notification</a>
                <hr class="nav-underline">
            </li>
            <li class="nav-link" onclick="showPage('history')">
                <a>Donation History</a>
                <hr class="nav-underline">
            </li>
            <li class="nav-link" onclick="showPage('profile')">
                <a>Profile</a>
                <hr class="nav-underline">
            </li>
        </ul>
    </div>
</nav>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- PROFILE PAGE -->
        <div id="profile" class="page">
            <button class="back-btn" onclick="showPage('donation')">← Back</button>
            
            <div class="profile-card">
                <div class="profile-header">
                    <div class="avatar">👤</div>
                    <div class="profile-info">
                        <p><?php echo htmlspecialchars($_SESSION['fullname']); ?></p>
                        <p><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                        <span class="status-badge">Active</span>
                    </div>
                </div>

                <div class="tabs">
                    <button class="tab" onclick="switchTab(0)">Active Settings</button>
                    <button class="tab active" onclick="switchTab(1)">Medical Info</button>
                    <button class="tab" onclick="switchTab(2)">History</button>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Blood Type</div>
                        <div class="info-value">O+</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Organ</div>
                        <div class="info-value">Liver</div>
                    </div>
                </div>

                <h3 class="section-title">Donation History</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Organ</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Liver</td>
                            <td style="color: #F39C12; font-weight: 500;">Pending</td>
                            <td>Sept 20, 2025</td>
                        </tr>
                    </tbody>
                </table>

                <div class="action-buttons">
                    <button class="btn btn-primary">Edit Profile</button>
                    <button class="btn btn-danger">Deactivate Account</button>
                    <button type="submit" class="btn btn-secondary" style="margin-left: auto;"><a href="Log In.php">Log out</a></button>
                </div>
            </div>
        </div>

        <!-- DONATION PAGE -->
        <div id="donation" class="page active">
            <div class="page-header">
                <h1 class="page-title">Available Donors</h1>
            </div>

            <div class="donor-card">
                <div class="donor-id">Donor ID: 0231</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Organ</div>
                        <div class="info-value">Kidney</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Blood type</div>
                        <div class="info-value">A+</div>
                    </div>
                </div>
                <button class="btn btn-view">View Details</button>
            </div>

            <div class="donor-card">
                <div class="donor-id">Donor ID: 0232</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Organ</div>
                        <div class="info-value">Heart</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Blood type</div>
                        <div class="info-value">O+</div>
                    </div>
                </div>
                <button class="btn btn-view">View Details</button>
            </div>

            <div class="donor-card">
                <div class="donor-id">Donor ID: 0233</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Organ</div>
                        <div class="info-value">Lung</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Blood type</div>
                        <div class="info-value">B+</div>
                    </div>
                </div>
                <button class="btn btn-view">View Details</button>
            </div>
        </div>

        <!-- NOTIFICATION PAGE -->
        <div id="notification" class="page">
            <button class="back-btn" onclick="showPage('donation')">← Back</button>
            
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

            <div class="notification-item">
                <span class="notification-icon">🔔</span>
                <div class="notification-content">
                    <div class="notification-message">Your donation status has been updated to Active.</div>
                    <div class="notification-date">Sept 30, 2025</div>
                </div>
                <div class="notification-actions">
                    <button class="btn btn-danger btn-small">Delete</button>
                </div>
            </div>

            <button class="btn btn-secondary" style="width: 100%; margin-top: 20px;">Clear All Notifications</button>
        </div>

        <!-- HISTORY PAGE -->
        <div id="history" class="page">
            <button class="back-btn" onclick="showPage('donation')">← Back</button>
            
            <div class="page-header">
                <h1 class="page-title">Donation History</h1>
                <p class="page-subtitle">Track your past and ongoing donations.</p>
            </div>

            <div class="profile-card">
                <table>
                    <thead>
                        <tr>
                            <th>Donation ID</th>
                            <th>Organ</th>
                            <th>Status</th>
                            <th>Date Requested</th>
                            <th>Date Updated</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>D-1001</td>
                            <td>Kidney</td>
                            <td style="color: #27AE60; font-weight: 500;">Completed</td>
                            <td>Sept 20, 2025</td>
                            <td>Sept 25, 2025</td>
                            <td><button class="btn btn-secondary btn-small">View</button></td>
                        </tr>
                        <tr>
                            <td>D-1002</td>
                            <td>Liver</td>
                            <td style="color: #F39C12; font-weight: 500;">Pending</td>
                            <td>Sept 26, 2025</td>
                            <td>-</td>
                            <td><button class="btn btn-secondary btn-small">View</button></td>
                        </tr>
                        <tr>
                            <td>D-1003</td>
                            <td>Kidney</td>
                            <td style="color: #E74C3C; font-weight: 500;">Cancelled</td>
                            <td>Sept 15, 2025</td>
                            <td>Sept 18, 2025</td>
                            <td><button class="btn btn-secondary btn-small">View</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function showPage(pageId) {
            const pages = document.querySelectorAll('.page');
            pages.forEach(page => page.classList.remove('active'));
            document.getElementById(pageId).classList.add('active');

            // Update nav underlines
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                const underline = link.querySelector('.nav-underline');
                if (link.getAttribute('onclick').includes(pageId)) {
                    underline.classList.add('default-underline');
                } else {
                    underline.classList.remove('default-underline');
                }
            });
        }

        function switchTab(index) {
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach((tab, i) => {
                if (i === index) {
                    tab.classList.add('active');
                } else {
                    tab.classList.remove('active');
                }
            });
        }

        
        document.addEventListener('click', (e) => {
            if (e.target.textContent === 'View Details') {
                alert('Viewing donor details...');
            }
            if (e.target.textContent === 'Mark as Read') {
                e.target.closest('.notification-item').style.opacity = '0.6';
                e.target.textContent = 'Read';
                e.target.disabled = true;
                e.target.style.cursor = 'not-allowed';
            }
            if (e.target.textContent === 'Delete') {
                const item = e.target.closest('.notification-item');
                item.style.transition = 'all 0.3s';
                item.style.opacity = '0';
                item.style.transform = 'translateX(100px)';
                setTimeout(() => item.remove(), 300);
            }
            if (e.target.textContent === 'Clear All Notifications') {
                const items = document.querySelectorAll('.notification-item');
                items.forEach((item, index) => {
                    setTimeout(() => {
                        item.style.transition = 'all 0.3s';
                        item.style.opacity = '0';
                        item.style.transform = 'translateX(100px)';
                        setTimeout(() => item.remove(), 300);
                    }, index * 100);
                });
            }
        });
    </script>
</body>
</html>