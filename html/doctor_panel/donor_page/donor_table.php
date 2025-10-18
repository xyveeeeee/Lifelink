<?php
include "../doctors_db.php";

// Function to display all donors
function displayAllDonors($conn) {
    $sql = "SELECT * FROM donors ORDER BY id DESC";
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
                <th>Blood Type</th>
                <th>Location</th>
                <th>Created At</th>
              </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['username']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['phone_num']}</td>
                    <td>{$row['status']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['age']}</td>
                    <td>{$row['gender']}</td>
                    <td>{$row['organ']}</td>
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
function displayDonorsByStatus($status) {
    global $conn;
    $sql = "SELECT * FROM donors WHERE status = '$status' ORDER BY id DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr>
                <th>ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Organ</th>
                <th>Blood Type</th>
              </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['username']}</td>
                    <td>{$row['organ']}</td>
                    <td>{$row['blood_type']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No $status donors found.</p>";
    }
}
?>
