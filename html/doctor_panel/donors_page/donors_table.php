<?php
include "../doctors_db.php";

// Function to display all donors
function displayAllDonors($conn) {
    $sql = "SELECT * FROM donations d INNER JOIN users u ON d.user_id = u.id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Organ</th>
                <th>Blood Cell</th>
                <th>Blood Type</th>
                <th>Location</th>
                <th>Created At</th>
              </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['username']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['phone']}</td>
                    <td>{$row['status']}</td>
                    <td>{$row['fullname']}</td>
                    <td>{$row['age']}</td>
                    <td>{$row['gender']}</td>
                    <td>{$row['organ_type']}</td>
                    <td>{$row['blood_cell']}</td>
                    <td>{$row['blood_type']}</td>
                    <td>{$row['location']}</td>
                    <td>{$row['created_at']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No donors found.</p>";
    }
}

// Function to display donors by status
function displayDonorsByPending($conn) {
    $sql = "
        SELECT 
            d.user_id AS user_id,
            u.username AS username,
            u.fullname AS fullname,
            d.donation_type,
            d.organ_type AS organ_type,
            d.blood_cell AS blood_cell,
            d.blood_type AS blood_type,
        CASE
            WHEN d.donation_type = 'organ' THEN 'Organ'
            WHEN d.donation_type = 'blood' THEN d.blood_cell
        END AS match_type
        FROM donations d INNER JOIN users u ON d.user_id = u.id
        WHERE status = 'Pending'
    ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "
                <div class='content'>
                    <p><strong>{$row['user_id']}</strong> - {$row['fullname']}</p>
                    <p>{$row['username']}</p>
                    <p><strong>{$row['match_type']}</strong></p>
                    <p><strong>{$row['organ_type']}</strong></p>
                    <p><strong>{$row['blood_type']}</strong></p>
                </div>
            ";
        }
    } else {
        echo "<p>No Pending donors found.</p>";
    }
}

function displayDonorsByConfirmed($conn) {
    $sql = "
        SELECT 
            d.user_id AS user_id,
            u.username AS username,
            u.fullname AS fullname,
            d.donation_type,
            d.organ_type AS organ_type,
            d.blood_cell AS blood_cell,
            d.blood_type AS blood_type,
        CASE
            WHEN d.donation_type = 'organ' THEN 'Organ'
            WHEN d.donation_type = 'blood' THEN d.blood_cell
        END AS match_type
        FROM donations d INNER JOIN users u ON d.user_id = u.id
        WHERE status = 'Confirmed'
    ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "
                <div class='content'>
                    <p><strong>{$row['user_id']}</strong> - {$row['fullname']}</p>
                    <p>{$row['username']}</p>
                    <p><strong>{$row['match_type']}</strong></p>
                    <p><strong>{$row['organ_type']}</strong></p>
                    <p><strong>{$row['blood_type']}</strong></p>
                </div>
            ";
        }
    } else {
        echo "<p class='empty-box'>No Confirmed donors found.</p>";
    }
}

function displayDonorsByDeclined($conn) {
    $sql = "
        SELECT 
            d.user_id AS user_id,
            u.username AS username,
            u.fullname AS fullname,
            d.donation_type,
            d.organ_type AS organ_type,
            d.blood_cell AS blood_cell,
            d.blood_type AS blood_type,
        CASE
            WHEN d.donation_type = 'organ' THEN 'Organ'
            WHEN d.donation_type = 'blood' THEN d.blood_cell
        END AS match_type
        FROM donations d INNER JOIN users u ON d.user_id = u.id
        WHERE status = 'Declined'
    ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "
                <div class='content'>
                    <p><strong>{$row['user_id']}</strong> - {$row['fullname']}</p>
                    <p>{$row['username']}</p>
                    <p><strong>{$row['match_type']}</strong></p>
                    <p><strong>{$row['organ_type']}</strong></p>
                    <p><strong>{$row['blood_type']}</strong></p>
                </div>
            ";
        }
    } else {
        echo "<p class='empty-box'>No Declined donors found.</p>";
    }
}
?>
