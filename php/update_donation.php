<?php
require_once 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first.'); window.location.href='../html/Log In.php';</script>";
    exit;
}

// required fielD
if (!isset($_POST['donation_id'], $_POST['donation_type'], $_POST['hospital'], $_POST['availability_date'])) {
    echo "<script>alert('Missing required fields.'); window.location.href='../html/history.php';</script>";
    exit;
}

$donation_id = intval($_POST['donation_id']);
$donation_type = strtolower(trim($_POST['donation_type']));
$hospital = trim($_POST['hospital']);
$availability_date = $_POST['availability_date'];
$user_id = $_SESSION['user_id'];

// BLOOD DONATION UPDATE
if ($donation_type === 'blood') {
    if (empty($_POST['blood_type']) || empty($_POST['blood_volume'])) {
        echo "<script>alert('Blood type and volume are required.'); window.location.href='../html/history.php';</script>";
        exit;
    }

    $blood_type = trim($_POST['blood_type']); // trim for blood_type
    $blood_volume = trim($_POST['blood_volume']);

    // cooldown days
    $cooldown_days_map = [
        'whole blood'       => 90, 
        'platelet'          => 14,
        'plasma'            => 28,
        'double red cells'  => 112
    ];

    // case-insensitive match
    $type_key = strtolower($blood_type);

    if (!array_key_exists($type_key, $cooldown_days_map)) {
        echo "<script>alert('Invalid or unknown blood type for cooldown.'); window.location.href='../html/history.php';</script>";
        exit;
    }

    $cooldown_days = $cooldown_days_map[$type_key];

    // Check last donation of the same blood_type (excluding current record)
    $check_stmt = $conn->prepare("
        SELECT availability_date 
        FROM donations 
        WHERE user_id = ? 
        AND donation_type = 'blood' 
        AND LOWER(blood_type) = ? 
        AND id != ?
        ORDER BY availability_date DESC 
        LIMIT 1
    ");
    $check_stmt->bind_param("isi", $user_id, $type_key, $donation_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $last_date = new DateTime($row['availability_date']);
        $new_date  = new DateTime($availability_date);

        //Prevent updates that would duplicate or back-date the record,
        //reject if new date is the same as or earlier than the most recent same-type donation.
        if ($new_date <= $last_date) {
            echo "<script>
                alert('Update rejected: you already have a {$blood_type} donation on " . $last_date->format('F j, Y') . ". Please choose a later availability date to avoid duplication.');
                window.location.href='../html/history.php';
            </script>";
            exit;
        }

        // Then enforce the cooldown only if new date is after last date)
        $interval = $last_date->diff($new_date);
        // %r%a gives signed days difference, cast to int
        $interval_days = (int)$interval->format('%r%a');

        if ($interval_days > 0 && $interval_days < $cooldown_days) {
            $next_allowed = clone $last_date;
            $next_allowed->modify("+{$cooldown_days} days");
            echo "<script>
                alert('You can only donate {$blood_type} again after " . $next_allowed->format('F j, Y') . " (cooldown: {$cooldown_days} days).');
                window.location.href='../html/history.php';
            </script>";
            exit;
        }
    }
    $check_stmt->close();

    // Proceed with update
    $stmt = $conn->prepare("
        UPDATE donations 
        SET hospital = ?, availability_date = ?, blood_type = ?, volume = ?
        WHERE id = ? AND user_id = ? AND donation_type = 'blood'
    ");
    $stmt->bind_param("ssssii", $hospital, $availability_date, $blood_type, $blood_volume, $donation_id, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Blood donation updated successfully.'); window.location.href='../html/history.php';</script>";
    } else {
        echo "<script>alert('Error updating blood donation: {$stmt->error}'); window.location.href='../html/history.php';</script>";
    }

    $stmt->close();
    $conn->close();
    exit;
}


// ORGAN DONATION UPDATE
elseif ($donation_type === 'organ') { 
    if (empty($_POST['organ_type'])) {
        echo "<script>alert('Organ type is required.'); window.location.href='../html/history.php';</script>";
        exit;
    }

    $organ_type = trim($_POST['organ_type']);

    // It Check for duplicate organ_type for this user excluding the current donation 
    $check_stmt = $conn->prepare("
        SELECT id FROM donations
        WHERE user_id = ? AND organ_type = ? AND donation_type = 'organ' AND id != ?
    ");
    $check_stmt->bind_param("isi", $user_id, $organ_type, $donation_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Duplicate found msg
        echo "<script>alert('This organ type is already registered in another donation record.'); window.location.href='../html/history.php';</script>";
        $check_stmt->close();
        $conn->close();
        exit;
    }
    $check_stmt->close();

    // Proceed with update if no duplicationss
    $stmt = $conn->prepare("
        UPDATE donations 
        SET hospital = ?, availability_date = ?, organ_type = ?
        WHERE id = ? AND user_id = ? AND donation_type = 'organ'
    ");
    $stmt->bind_param("sssii", $hospital, $availability_date, $organ_type, $donation_id, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Organ donation updated successfully.'); window.location.href='../html/history.php';</script>";
    } else {
        echo "<script>alert('Error updating organ donation: {$stmt->error}'); window.location.href='../html/history.php';</script>";
    }

    $stmt->close();
    $conn->close();
    exit;
}

// INVALID TYPE
else {
    echo "<script>alert('Invalid donation type.'); window.location.href='../html/history.php';</script>";
    exit;
}
?>
