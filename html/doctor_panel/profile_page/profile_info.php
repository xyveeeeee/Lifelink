<?php
session_start();
include '../doctors_db.php';

$id = 2;

$check_user = mysqli_query($conn, "SELECT id FROM doctors WHERE id = 2");
if (!$check_user || mysqli_num_rows($check_user) == 0) {
    // Create default test user
    $create_user = "INSERT INTO doctors (id, username, email, phone_num, name, age, gender, license, workplace, description, created_at) 
                    VALUES (2, 'hippocrates', 'hippocrates@gmail.com', '2147483647', 'Hippocrates', 30, 'Male', '000-000-000-000', 'Hospital', 'Father of Medicine', NOW())";
    mysqli_query($conn, $create_user);
}

// user info
$user_sql = "SELECT * FROM doctors WHERE id = $id";
$user_result = mysqli_query($conn, $user_sql);

if ($user_result && mysqli_num_rows($user_result) > 0) {
    $user = mysqli_fetch_assoc($user_result);
} else {
    $user = [
        'id' => 2,
        'username' => 'hippocrates',
        'email' => 'hippocrates@gmail.com',
        'phone_num' => '2147483647',
        'name' => 'Hippocrates',
        'age' => 30,
        'gender' => 'Male',
        'license' => '000-000-000-000',
        'workplace' => 'Hospital',
        'description' => 'Father of Medicine',
        'created_at' => date('Y-m-d H:i:s')
    ];
}

// forms
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $section = $_POST['section'];

    if ($section === "account") {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone_num = mysqli_real_escape_string($conn, $_POST['phone_num']);
        $name = mysqli_real_escape_string($conn, $_POST['name']);

        if (!empty($username) && !empty($name) && !empty($email)) {
            $sql = "UPDATE doctors SET username='$username', email='$email', phone_num='$phone_num', name='$name' WHERE id=$id";
            
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Account information updated successfully!');</script>";
            } else {
                echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
            }
        } else {
            echo "<script>alert('Please fill in all required fields!');</script>";
        }
    }

    if ($section === "personal_info") {
        $age = intval($_POST['age']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $license = mysqli_real_escape_string($conn, $_POST['license']);
        $workplace = mysqli_real_escape_string($conn, $_POST['workplace']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);

        $check = mysqli_query($conn, "SELECT * FROM doctors WHERE id=$id");
        if (mysqli_num_rows($check) > 0) {
            $sql = "UPDATE doctors SET age=$age, gender='$gender', license='$license', workplace='$workplace', description='$description' WHERE id=$id";
        } else {
            $sql = "INSERT INTO doctors (id, age, gender, license, workplace, description)
                    VALUES ($id, $age, '$gender', '$license', '$workplace', 'description')";
        }
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Personal information saved successfully!');</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    }

    
    echo "<meta http-equiv='refresh' content='0'>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Profile</title>
    <link rel="stylesheet" href="profile_style.css">
</head>
<body>

<div id="profile" class="page active">
    <div class="profile-card">
        <div class="profile-header">
            <div class="avatar">👤</div>
            <div class="profile-info">
                <h2><?= htmlspecialchars($user['name'] ?? '') ?></h2>
                <p><?= htmlspecialchars($user['email'] ?? 'email@example.com') ?></p>
                <span class="status-badge"><?= htmlspecialchars($user['status'] ?? 'Active') ?></span>
            </div>
        </div>

        <div class="tabs">
            <button class="tab active" onclick="switchTab(0)">Account Info</button>
            <button class="tab" onclick="switchTab(1)">Personal Info</button>
        </div>

        <!-- ACCOUNT INFO TAB -->
        <div class="tab-content active" id="tab0">
            <h3 class="section-title">View Account Information</h3>
            
            <!-- View Account Info -->
            <div class="info-display">
                <div class="info-row">
                    <span class="info-label-display">User ID:</span>
                    <span class="info-value-display"><?= htmlspecialchars($user['id'] ?? 'N/A') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label-display">Username:</span>
                    <span class="info-value-display"><?= htmlspecialchars($user['username'] ?? 'N/A') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label-display">Name:</span>
                    <span class="info-value-display"><?= htmlspecialchars($user['name'] ?? 'N/A') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label-display">Email:</span>
                    <span class="info-value-display"><?= htmlspecialchars($user['email'] ?? 'N/A') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label-display">Phone Number:</span>
                    <span class="info-value-display"><?= htmlspecialchars($user['phone_num'] ?? 'N/A') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label-display">Account Created:</span>
                    <span class="info-value-display"><?= htmlspecialchars($user['created_at'] ?? 'N/A') ?></span>
                </div>
            </div>

            <button class="toggle-edit-btn" onclick="toggleEdit()">✏️ Edit Account Information</button>

            <!-- Edit Account Info (Hidden by default) -->
            <div class="edit-section" id="editAccountSection" style="display: none;">
                <h4 class="section-title">Edit Account Information</h4>
                <form method="POST">
                    <input type="hidden" name="section" value="account">

                    <label>
                        Username: <span class="required">*</span>
                        <input type="text" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" placeholder="Enter username" required>
                    </label>

                    <label>
                        Name: <span class="required">*</span>
                        <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" placeholder="Enter your Name" required>
                    </label>

                    <label>
                        Email: <span class="required">*</span>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" placeholder="Enter your email" required>
                    </label>

                    <label>
                        Phone Number:
                        <input type="text" name="phone_num" value="<?= htmlspecialchars($user['phone_num'] ?? '') ?>" placeholder="e.g., 09123456789">
                    </label>

                    <button class="btn btn-primary" type="submit">Save Changes</button>
                </form>
            </div>
        </div>

        <!-- Personal INFO TAB -->
        <div class="tab-content" id="tab1">
            <h3 class="section-title">Personal Information</h3>

            <form method="POST">
                <input type="hidden" name="section" value="personal_info">

                <div class="form-row">
                    <label>
                        Age:
                        <input type="number" name="age" value="<?= htmlspecialchars($user['age'] ?? '') ?>" placeholder="e.g., 25" min="18" max="100">
                    </label>

                    <label>
                        Gender:
                        <select name="gender">
                            <option value="">Select Gender</option>
                            <option value="Male" <?= ($user['gender'] ?? '') == 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= ($user['gender'] ?? '') == 'Female' ? 'selected' : '' ?>>Female</option>
                            <option value="Other" <?= ($user['gender'] ?? '') == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </label>
                </div>

                <div class="form-row">
                    <label>
                        License:
                        <input type="text" name="license" value="<?= htmlspecialchars($user['license'] ?? '') ?>" placeholder="Enter your License" required>
                    </label>

                    <label>
                        Workplace:
                        <input type="text" name="workplace" value="<?= htmlspecialchars($user['workplace'] ?? '') ?>" placeholder="Enter your Workplace" required>
                    </label>

                    <label>
                        Description:
                        <input type="text" name="description" value="<?= htmlspecialchars($user['description'] ?? '') ?>" placeholder="Enter your Description" required>
                    </label>
                </div>

                <button class="btn btn-primary" type="submit">Save Peronal Information</button>
            </form>
        </div>
    </div>
</div>

<script src="profile_config.js"></script>

</body>
</html>

<?php
mysqli_close($conn);
?>