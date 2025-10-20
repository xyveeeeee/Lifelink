<?php 
require_once 'db_connect.php'; 
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to continue.'); window.location.href='../html/Log In.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
    $donation_type = strtolower(trim($_POST['donation_type']));
    $hospital = trim($_POST['hospital']);
    $availability_date = trim($_POST['availability_date']);
    $status = 'Pending';

    // specific for blood
    $blood_type = isset($_POST['blood_type']) ? trim($_POST['blood_type']) : null;
    $volume = isset($_POST['volume']) ? trim($_POST['volume']) : null;

    // specific for organ
    $organ_type = isset($_POST['organ_type']) ? trim($_POST['organ_type']) : null;

    // required fields
    if (empty($donation_type) || empty($hospital) || empty($availability_date)) {
        echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
        exit();
    }

    // PREVENT'S USER DUPLICATIONS OF ORGAN DONATION
    if ($donation_type === 'organ' && !empty($organ_type)) {
        $check_sql = "SELECT COUNT(*) FROM donations WHERE user_id = ? AND LOWER(donation_type) = 'organ' AND LOWER(organ_type) = LOWER(?) AND status IN ('Pending', 'Approved', 'Completed')";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("is", $user_id, $organ_type);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();

        if ($count > 0) {
            echo "<script>
                alert('You have already donated or pledged to donate your $organ_type. Duplicate organ donations are not allowed.');
                window.history.back();
            </script>";
            exit();
        }
    }

    // BLOOD DONATION COOLDOWN CHECK
    if ($donation_type === 'blood' && !empty($blood_type)) {
        // cooldown days
        $cooldown_days = match(strtolower($blood_type)) {
            'whole' => 90, // 3 months cd
            'platelet' => 14, // 2 weeks cd
            'plasma' => 28, // 4 weeks cd
            'red' => 112, // 16 weeks cd
            default => 90
        };

        // get user's most recent donation of the SAME blood type
        $check_blood_sql = "SELECT MAX(availability_date) FROM donations WHERE user_id = ? AND LOWER(donation_type) = 'blood' AND LOWER(blood_type) = LOWER(?) AND status IN ('Pending', 'Approved', 'Completed')";
        $check_blood_stmt = $conn->prepare($check_blood_sql);
        $check_blood_stmt->bind_param("is", $user_id, $blood_type);
        $check_blood_stmt->execute();
        $check_blood_stmt->bind_result($last_donation_date);
        $check_blood_stmt->fetch();
        $check_blood_stmt->close();

        if ($last_donation_date) {
            $next_allowed_date = date('Y-m-d', strtotime($last_donation_date . " +$cooldown_days days"));
            $today = date('Y-m-d');
            if ($today < $next_allowed_date) {
                echo "<script>
                    alert('You must wait until $next_allowed_date before donating $blood_type blood again. You can still donate other types of blood');
                    window.history.back();
                </script>";
                exit();
            }
        }
    }
    // for new donation
    $sql = "INSERT INTO donations (user_id, donation_type, organ_type, blood_type, volume, hospital, availability_date, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $user_id, $donation_type, $organ_type, $blood_type, $volume, $hospital, $availability_date, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Donation submitted successfully! Thank you for your generosity.'); window.location.href='../html/donation.php';</script>";
    } else {
        echo "<script>alert('Error saving donation: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
}
?>
