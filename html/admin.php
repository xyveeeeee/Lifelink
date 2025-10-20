<?php
// Database configuration
$host = 'localhost';
$dbname = 'db_lifelink';
$username = 'root';
$password = '';

$required_role = 'admin';
require_once '../php/check_session.php';
// Create connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        switch($action) {
            //add admin
        case 'add_donor': 
            // Validate passwords first
            if ($_POST['password'] !== $_POST['cpassword']) {
                $message = "Passwords do not match!";
                break;
            }

            $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (fullname, username, email, role, password, created_at) 
                                    VALUES (?, ?, ?, ?, ?, NOW())");

            // Execute with the correct field order
            $stmt->execute([
                $_POST['name'],       // fullname
                $_POST['donor_id'],   // username
                $_POST['email'],      // email
                $_POST['role'],       // role (e.g., 'admin')
                $hashedPassword       // password (hashed)
            ]);

            $message = "Admin added successfully!";
            break;
            case 'update_donor':
                $stmt = $conn->prepare("UPDATE users SET fullname=?, username=?, email=?, phone=?, location=? WHERE id=?");
                $stmt->execute([
                    $_POST['fullname'],
                    $_POST['username'],
                    $_POST['email'],
                    $_POST['phone'],
                    $_POST['location'],
                    $_POST['id']
                ]);
                $message = "Updated successfully!";
                break;
                
            case 'delete_donor':
                $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
                $stmt->execute([$_POST['id']]);
                $message = "Donor deleted successfully!";
                break;

            //add doctor
           case 'add_doctor':
            if ($_POST['password'] !== $_POST['cpassword']) {
                $message = "Passwords do not match!";
                break;
            }

            $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Insert into users table
            $stmt = $conn->prepare("
                INSERT INTO users (fullname, username, email, phone, location, role, password, created_at)
                VALUES (?, ?, ?, ?, ?, 'doctor', ?, NOW())
            ");
            $stmt->execute([
                $_POST['fullname'],
                $_POST['username'],
                $_POST['email'],
                $_POST['phone'],
                $_POST['location'],
                $hashedPassword
            ]);

            // Get new user ID
            $user_id = $conn->lastInsertId();

            // Insert into doctor table
            $stmt2 = $conn->prepare("
                INSERT INTO doctor (username, email, phone_num, name, age, gender, license, location, description, created_at)
                VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt2->execute([
                $_POST['username'],   // username
                $_POST['email'],      // email
                $_POST['phone'],      // phone_num
                $_POST['fullname'],   // name (full name)
                $_POST['age'] ?? null,      // optional
                $_POST['gender'] ?? null,   // optional
                $_POST['license'] ?? null,  // optional
                $_POST['location'],         // workplace
                $_POST['description'] ?? null // optional
            ]);

            $message = "Doctor added successfully!";
            break;

                            
            case 'update_doctor': 
                // ✅ Update users table
                $stmt = $conn->prepare("
                    UPDATE users 
                    SET fullname = ?, username = ?, email = ?, phone = ?, location = ? 
                    WHERE id = ?
                ");
                $stmt->execute([
                    $_POST['fullname'],
                    $_POST['username'],
                    $_POST['email'],
                    $_POST['phone'],
                    $_POST['location'],
                    $_POST['id']
                ]);

                // ✅ Also update doctor table
                $stmt2 = $conn->prepare("
                    UPDATE doctor 
                    SET name = ?, username = ?, email = ?, phone_num = ?, location = ? 
                    WHERE id = ?
                ");
                $stmt2->execute([
                    $_POST['fullname'],   // doctor.name = users.fullname
                    $_POST['username'],
                    $_POST['email'],
                    $_POST['phone'],
                    $_POST['location'],
                    $_POST['id']
                ]);

                $message = "Doctor updated successfully!";
                break;

            case 'delete_donation': 
            // ✅ Delete from doctor table first (if exists)
            $stmt1 = $conn->prepare("DELETE FROM doctor WHERE id = ?");
            $stmt1->execute([$_POST['id']]);

            // ✅ Then delete from users table
            $stmt2 = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt2->execute([$_POST['id']]);

            $message = "Doctor deleted successfully!";
            break;
                
            case 'add_notification':
                // Validate input first
                if (empty($_POST['user_id']) || empty($_POST['message'])) {
                    $error = "User ID and message are required.";
                    break;
                }

                try {
                    // Insert new notification
                    $stmt = $conn->prepare("
                        INSERT INTO notifications (user_id, message, created_at)
                        VALUES (:user_id, :message, NOW())
                    ");
                    $stmt->execute([
                        ':user_id' => $_POST['user_id'],
                        ':message' => $_POST['message']
                    ]);

                    $message = "Notification added successfully!";
                } catch (PDOException $e) {
                    $error = "Error adding notification: " . $e->getMessage();
                }
                break;

        }
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch data
$donors = $conn->query("SELECT * FROM users WHERE role = 'donor' ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$donations = $conn->query("SELECT * FROM donations ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);


$users = $conn->query("SELECT * FROM users WHERE role = 'admin' ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$users1 = $conn->query("SELECT * FROM users WHERE role = 'doctor' ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

$notifications = $conn->query("SELECT * FROM notifications ORDER BY id DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeLink - Admin Panel</title>
    <style>
        /* ---------- Reset + base ---------- */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #e9e9ea;
            min-height: 100vh;
            color: #2d2d2d;
        }

        /* ---------- Layout ---------- */
        .app {
            display: flex;
            gap: 24px;
            max-width: 1400px;
            margin: 22px auto;
            padding: 0 16px;
        }

        /* ---------- Sidebar ---------- */
        .sidebar {
            width: 260px;
            min-height: calc(100vh - 44px);
            background: linear-gradient(180deg, #2b3438 0%, #1f2628 100%);
            border-radius: 12px;
            padding: 22px 18px;
            color: #e9f0ef;
            box-shadow: 0 10px 30px rgba(16,22,23,0.25);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .sidebar .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }

        /*.sidebar .brand .logo {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background: #28c07f;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: white;
            box-shadow: 0 6px 18px rgba(40,192,127,0.16);
            font-size: 20px;
        }*/

        .sidebar .brand h3 {
            font-size: 16px;
            color: #f1f6f5;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .nav-list {
            margin-top: 12px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            overflow-y: auto;
            padding-right: 8px; /* space for scrollbar */
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 10px;
            border-radius: 8px;
            color: rgba(255,255,255,0.92);
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s, transform 0.12s;
        }

        #nav-items {
            background: rgba(14, 100, 106, 0.37);
        }

        .nav-item .icon {
            min-width: 28px;
            min-height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.04);
            border-radius: 6px;
            font-size: 14px;
        }
        #nav-items.active {
            background: rgba(255, 255, 255, 0.03);
            transform: translateX(4px);
        }
        .nav-item.active {
            background: rgba(42, 246, 31, 0.06);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.02);
            color: #ffffff;
        }

        /* Logout button anchored at bottom */
        .sidebar .logout-wrap {
            margin-top: 18px;
            display: flex;
            justify-content: center;
        }

        .logout-btn {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: none;
            background: linear-gradient(180deg,#ff4f7a 0%, #ff2f63 100%);
            color: white;
            font-weight: 800;
            letter-spacing: 0.6px;
            cursor: pointer;
            box-shadow: 0 10px 24px rgba(255,47,99,0.14);
        }

        /* ---------- Main content (header + card) ---------- */
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: #34444f;
            padding: 18px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
        }

        .header h1 {
            color: #28c07f;
            font-size: 20px;
            margin: 0;
            font-weight: 700;
        }

        .header p {
            color: rgba(255,255,255,0.85);
            margin-left: 12px;
            font-size: 13px;
            opacity: 0.95;
        }

        .content {
            background: #ffffff;
            padding: 28px;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(33,40,45,0.12);
        }

        /*.tab-btn { 
            padding: 10px 20px;
            background: transparent;
            border: 1px solid rgba(0,0,0,0.06);
            border-radius: 10px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.18s ease;
            color: #2d2d2d;
            background: #ffffff;
            box-shadow: 0 3px 8px rgba(16,24,32,0.04);
        }

        .tab-btn.active {
            background: #2f4652;
            color: white;
            box-shadow: 0 6px 18px rgba(47,70,82,0.18);
            border: none;
        }*/

        .tab-content { display: none; }
        .tab-content.active { display: block; }

        .message { padding: 15px; border-radius: 10px; margin-bottom: 20px; }
        .success { background: #eaf7ef; color: #1f7a3b; border: 1px solid #d3f0d8; }
        .error { background: #fdecea; color: #8b2a2a; border: 1px solid #f5c6cb; }

        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 18px; margin-bottom: 20px; }
        .form-group { display:flex; flex-direction:column; }
        .form-group label { margin-bottom:8px; font-weight:600; color:#333; }
        .form-group input, .form-group select { padding:12px; border:1px solid #e6e6e6; border-radius:8px; font-size:14px; background:#fbfbfb; }
        .form-group input:focus, .form-group select:focus { outline:none; border-color:#2f8f63; box-shadow:0 4px 18px rgba(47,143,99,0.08); }

        .btn { padding:12px 30px; border:none; border-radius:10px; font-size:15px; font-weight:700; cursor:pointer; transition:all 0.2s; }
        .btn-primary { background: linear-gradient(180deg, #2fc07f 0%, #1f9b63 100%); color: white; box-shadow: 0 8px 18px rgba(47,192,127,0.16); }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(47,192,127,0.18); }
        .btn-danger { background: #e74c3c; color: white; border-radius:8px; font-weight:700; }
        .btn-warning { background: #f39c12; color:white; font-weight:700; border-radius:8px; }
        .btn-small { padding:8px 14px; font-size:13px; border-radius:8px; }

        table { width:100%; border-collapse:collapse; margin-top:20px; background:transparent; }
        table th { background: #2f4652; color:#ffffff; padding:16px 18px; text-align:left; font-weight:700; letter-spacing:0.2px; font-size:15px; }
        table td { padding:14px 18px; border-bottom:1px solid #efefef; background:#ffffff; }
        table tr:hover td { background:#fbfbfb; }
        .content table { border-radius:10px; overflow:hidden; }

        .status-badge { padding:6px 12px; border-radius:18px; font-size:13px; font-weight:700; display:inline-block; }
        .status-active { background:#dff7e8; color:#1d7a3f; }
        .status-pending { background:#fff3db; color:#b36b00; }
        .status-completed { background:#e6f7f7; color:#0b676b; }
        .status-cancelled { background:#fdecea; color:#a63434; }

        .action-buttons { display:flex; gap:10px; }
        .action-buttons .btn-warning { background:#f6a623; box-shadow:none; color:white; }
        .action-buttons .btn-danger { background:#a7a7a7; color:white; }

        .stats-grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:20px; margin-bottom:26px; }
        .stat-card { background: linear-gradient(180deg, #2f4652 0%, #27424a 100%); color:white; padding:22px; border-radius:12px; box-shadow:0 8px 20px rgba(33,40,45,0.08); }
        .stat-card h3 { font-size:28px; margin-bottom:6px; font-weight:800; }
        .stat-card p { opacity:0.92; }

        .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(18,22,24,0.45); z-index:1000; }
        .modal-content { background:white; max-width:680px; margin:50px auto; padding:30px; border-radius:14px; max-height:90vh; overflow-y:auto; box-shadow:0 18px 40px rgba(15,18,20,0.16); }
        .modal-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; }
        .close-btn { font-size:28px; cursor:pointer; color:#999; user-select:none; }
        .close-btn:hover { color:#333; }

        /* responsive */
        @media (max-width: 980px) {
            .app { padding: 0 12px; gap: 12px; }
            .sidebar { display: none; } /* hide sidebar on narrow screens */
            .main { width: 100%; }
        }

        @media (max-width: 700px) {
            .header { flex-direction: column; align-items: flex-start; gap: 8px; }
            .tab-btn { margin-bottom: 8px; }
            .stat-card h3 { font-size: 24px; }
        }
    </style>
</head>
<body>
    <div class="app">
        <!-- SIDEBAR -->
        <aside class="sidebar" aria-label="Main navigation">
            <div>
                <div class="brand">
                <img src="../image/logo.png" alt="LifeLink Logo" style="width: 44px; height: 44px; border-radius: 8px;">
                <h3>LifeLink</h3>
                </div>

                <nav class="nav-list" role="navigation" aria-label="Sidebar">
                    <!-- Keep these items simple — you can change text or add links -->
                    <div class="nav-item" id="nav-items" #onclick="scrollToSection('dashboard')">
                        <div class="icon">🏠</div>
                        <div>Dashboard</div>
                    </div>

                    <div class="nav-item active"  onclick="showTab('donors')">
                        <div class="icon">👥</div>
                        <div>Add Admin</div>
                    </div>

                    <div class="nav-item"  onclick="showTab('donations')">
                        <div class="icon">🩺</div>
                        <div>Add Doctor</div>
                    </div>

                    <div class="nav-item"  onclick="showTab('notifications')">
                        <div class="icon">🔔</div>
                        <div>Notifications</div>
                    </div>

                    <div class="nav-item"  onclick="window.location='#'">
                        <div class="icon">📁</div>
                        <div>Records</div>
                    </div>

                    <div class="nav-item"  onclick="window.location='#'">
                        <div class="icon">⚙️</div>
                        <div>Settings</div>
                    </div>
                </nav>
            </div>

            <div class="logout-wrap">
                <!-- This is a form submit (keeps backend intact). Replace '#' with your logout endpoint if you have one. -->
                <form action="../php/logout.php" method="POST" style="width:100%;">
                    <button type="submit" class="logout-btn">LOGOUT</button>
                </form>
            </div>
        </aside>

        <!-- MAIN -->
        <div class="main">
            <div class="header">
                <h1>Hi, <?php echo htmlspecialchars($_SESSION['fullname']); ?></h1>
                <p>Manage accounts for admin, doctor, and notifications</p>
            </div>

            <div class="content">
                <?php if (isset($message)): ?>
                    <div id="successMessage" class="message success">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <?php if(isset($error)): ?>
                    <div id="successMessage"class="message error"><?= $error ?></div>
                <?php endif; ?>

                <!-- Statistics -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3><?= count($donors) ?></h3>
                        <p>Total Donors</p>
                    </div>
                    <div class="stat-card">
                        <h3><?= count($donations) ?></h3>
                        <p>Total Donations</p>
                    </div>
                    <div class="stat-card">
                        <h3><?= count(array_filter($donations, fn($d) => $d['status'] === 'Pending')) ?></h3>
                        <p>Pending Donations</p>
                    </div>
                    <div class="stat-card">
                        <h3><?= count(array_filter($donations, fn($d) => $d['status'] === 'Completed')) ?></h3>
                        <p>Completed Donations</p>
                    </div>
                </div>

                <!-- Tabs -->

                <div class="tab-content active" id="donors">
                    <h2>Admin Management</h2>
                    <button class="btn btn-primary" onclick="openModal('addDonorModal')" style="margin: 20px 0;">+ Add New Admin</button>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Location</th>
                                <th>Account Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['fullname']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['phone']) ?></td>
                                <td><?= htmlspecialchars($user['location']) ?></td>
                                <td><?= htmlspecialchars($user['created_at']) ?></td>
                                <td class="action-buttons">
                                    <button class="btn btn-warning btn-small" 
                                    onclick='editDonor(<?= htmlspecialchars(json_encode($user), ENT_QUOTES, "UTF-8") ?>)'> 
                                    Edit
                                    </button>

                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this donor?')">
                                        <input type="hidden" name="action" value="delete_donor">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-small">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="tab-content" id="donations">
                    <h2>Doctor Management</h2>
                    <button class="btn btn-primary" onclick="openModal('addDonationModal')" style="margin: 20px 0;">+ Add New Doctor</button>
                    <table>
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Location</th>
                                <th>Account Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($users1 as $user): ?>
                        <tr>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['fullname']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['phone']) ?></td>
                                <td><?= htmlspecialchars($user['location']) ?></td>
                                <td><?= htmlspecialchars($user['created_at'] ?? '-') ?></td>
                                <td class="action-buttons">
                                    <button class="btn btn-warning btn-small" onclick='editDoctor(<?= json_encode($user) ?>)'>Edit</button>

                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this doctor?')">
                                        <input type="hidden" name="action" value="delete_donor">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-small">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="tab-content" id="notifications">
                    <h2>Notification Management</h2>
                    <button class="btn btn-primary" onclick="openModal('addNotificationModal')" style="margin: 20px 0;">+ Send New Notification</button>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User ID</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                            <tbody>
                                <?php if (!empty($notifications)): ?>
                                    <?php foreach ($notifications as $notif): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($notif['id']) ?></td>
                                        <td><?= htmlspecialchars($notif['user_id']) ?></td>
                                        <td><?= htmlspecialchars($notif['message']) ?></td>
                                        <td><?= htmlspecialchars($notif['created_at']) ?></td>
                                        <td><span class="status-badge status-active">Sent</span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" style="text-align:center; color:gray;">No notifications yet.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Admin Modal -->
    <div id="addDonorModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Admin</h2>
                <span class="close-btn" onclick="closeModal('addDonorModal')">&times;</span>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="add_donor">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="donor_id" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select id="role" name="role" required>
                        <option>admin</option>
                </select>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input id="password" name="password" type="password" placeholder="Enter your password" 
                        required autocomplete="current-password" minlength="8" />
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input id="cpassword" name="cpassword" type="password" placeholder="Enter your password" 
                        required autocomplete="current-password" minlength="8"/>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Add Admin</button>
            </form>
        </div>
    </div>

    <!-- Edit Admin Modal -->
    <div id="editDonorModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Admin</h2>
                <span class="close-btn" onclick="closeModal('editDonorModal')">&times;</span>
            </div>
            <form method="POST" id="editDonorForm">
                <input type="hidden" name="action" value="update_donor">
                <input type="hidden" name="id" id="edit_donor_id">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" id="edit_donor_username" required>
                    </div>
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="fullname" id="edit_donor_name" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="edit_donor_email" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" id="edit_donor_phone" required>

                    </div>
                    <div class="form-group">
                        <label>Location</label>
                        <input type="text" name="location" id="edit_donor_location" required>
                    </div>

                </div>
                <button type="submit" class="btn btn-primary">Update Admin</button>
            </form>
        </div>
    </div>

    <!-- Add Doctor Modal -->
    <div id="addDonationModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Doctor</h2>
                <span class="close-btn" onclick="closeModal('addDonationModal')">&times;</span>
            </div>
           <form method="POST"> 
            <input type="hidden" name="action" value="add_doctor">

            <div class="form-grid">

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="fullname" required>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" required>
                </div>

                <div class="form-group">
                    <label>Location (Workplace)</label>
                    <input type="text" name="location" required>
                </div>

                <div class="form-group">
                    <label>Age</label>
                    <input type="number" name="age">
                </div>

                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender">
                        <option value="">Select</option>
                        <option>Male</option>
                        <option>Female</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>License</label>
                    <input type="text" name="license">
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <input type="text" name="description">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="cpassword" required>
                </div>

            </div>

            <button type="submit" class="btn btn-primary">Add Doctor</button>
        </form>


            </form>
        </div>
    </div>


    <!-- Edit Doctor Modal -->
    <div id="editDoctorModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Doctor</h2>
                <span class="close-btn" onclick="closeModal('editDoctorModal')">&times;</span>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="update_doctor">
                <input type="hidden" name="id" id="edit_doctor_id">

                <div class="form-grid">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" id="edit_donor_username" required>
                    </div>
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="fullname" id="edit_donor_name" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="edit_donor_email" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" id="edit_donor_phone" required>
                    </div>
                    <div class="form-group">
                        <label>Location</label>
                        <input type="text" name="location" id="edit_donor_location" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Update Doctor</button>
            </form>
        </div>
    </div>


    <!-- Add Notification Modal -->
    <div id="addNotificationModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
        <h2>Send Notification</h2>
        <span class="close-btn" onclick="closeModal('addNotificationModal')">&times;</span>
        </div>

        <form method="POST" action="../php/notification_action.php">
        <input type="hidden" name="action" value="add_notification">

        <div class="form-group">
            <label>User ID</label>
            <input type="number" name="user_id" placeholder="Enter User ID" required>
        </div>

        <div class="form-group">
            <label>Message</label>
            <textarea name="message" rows="3" placeholder="Enter notification message" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Send Notification</button>
        </form>
    </div>
    </div>

    <script>
        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            var el = document.getElementById(tabName);
            if(el) el.classList.add('active');

            // find tab button that matches and set active (simple behavior)
            var btns = document.querySelectorAll('.tab-btn');
            btns.forEach(b => { if(b.textContent.trim().toLowerCase().startsWith(tabName[0])) { }});
            // Note: We didn't change your existing JS logic — this keeps tab activation working.
        }

        function openModal(modalId) { document.getElementById(modalId).style.display = 'block'; }
        function closeModal(modalId) { document.getElementById(modalId).style.display = 'none'; }

        function editDonor(user) {
            document.getElementById('edit_donor_id').value = user.id;
            document.getElementById('edit_donor_username').value = user.username;
            document.getElementById('edit_donor_name').value = user.fullname;
            document.getElementById('edit_donor_email').value = user.email;
            document.getElementById('edit_donor_phone').value = user.phone;
            document.getElementById('edit_donor_location').value = user.location;

            openModal('editDonorModal');
        }
        document.addEventListener("DOMContentLoaded", () => {

        const msg = document.getElementById("successMessage");
            if (msg) {
                setTimeout(() => {
                    msg.style.transition = "opacity 0.5s";
                    msg.style.opacity = "0";
                    setTimeout(() => msg.remove(), 500);
                }, 3000);
            }

        const organBtn = document.getElementById("organBtn");
        const bloodBtn = document.getElementById("bloodBtn");
        const rows = document.querySelectorAll("#donations tbody tr");

        function filterDonations(type) {
            rows.forEach(row => {
                const donationType = row.getAttribute("data-type");
                row.style.display = (donationType === type) ? "" : "none";
            });
        }
       const navItems = document.querySelectorAll(".nav-item");
    const otherNavItems = Array.from(navItems).filter(item => 
        !item.textContent.includes("Dashboard")
    );

    otherNavItems.forEach(item => {
        item.addEventListener("click", () => {
            otherNavItems.forEach(i => i.classList.remove("active"));
            item.classList.add("active");
        });
    });

        organBtn.addEventListener("click", () => {
            organBtn.classList.add("active");
            bloodBtn.classList.remove("active");
            filterDonations("organ");
        });

        bloodBtn.addEventListener("click", () => {
            bloodBtn.classList.add("active");
            organBtn.classList.remove("active");
            filterDonations("blood");
        });

        // Default view: show organ donations
        filterDonations("organ");
        });

        function editDoctor(doctor) {
            document.getElementById('edit_doctor_id').value = doctor.id;
            document.getElementById('edit_donor_username').value = doctor.username;
            document.getElementById('edit_donor_name').value = doctor.fullname;
            document.getElementById('edit_donor_email').value = doctor.email;
            document.getElementById('edit_donor_phone').value = doctor.phone;
            document.getElementById('edit_donor_location').value = doctor.location;

            openModal('editDoctorModal');
        }


        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }

        // small helper (not altering backend) to scroll to a section
        function scrollToSection(id){
            // placeholder - can implement in future
            console.log('scrollToSection', id);
        }
    </script>
</body>
</html>