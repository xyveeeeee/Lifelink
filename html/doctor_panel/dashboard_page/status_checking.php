<?php 
include "../doctors_db.php";

function displayCurrentlyOnGoing1($conn) {
    $sql = "SELECT COUNT(*) AS total_pending FROM donors WHERE status = 'Pending'";
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        echo "<div class='pending-box'>";
        echo "<p>Total Pending: <strong>{$row['total_pending']}</strong></p>";
        echo "</div>";
    } else {
        echo "<p>No pending donors found.</p>";
    }
}

function displayCurrentlyOnGoing2($conn) {
    $sql = "SELECT COUNT(*) AS total_pending FROM patients WHERE status = 'Pending'";
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        echo "<div class='pending-box'>";
        echo "<p>Total Pending: <strong>{$row['total_pending']}</strong></p>";
        echo "</div>";
    } else {
        echo "<p>No pending donors found.</p>";
    }
}

function displayFoundMatches($conn) {
    echo "<h3 class='other-text'>Organ Match</h3>";

    $sql_organ = "
        SELECT 
            d.organ, 
            COUNT(*) AS total_matches
        FROM donors d
        INNER JOIN patients p
            ON d.organ = p.organ
        GROUP BY d.organ
        ORDER BY total_matches DESC
    ";

    $result_organ = $conn->query($sql_organ);

    if ($result_organ->num_rows > 0) {
        echo "<table border='1px'>
                <tr>
                    <th>Organ</th>
                    <th>Total</th>
                </tr>";
        while ($row = $result_organ->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['organ']}</td>
                    <td>{$row['total_matches']}</td>
                </tr>";
        }
        echo "</table><br>";
    } else {
        echo "<p>No organ matches found.</p>";
    }

    echo "<h3 class='other-text'>Blood Type Match</h3>";

    $sql_blood = "
        SELECT 
            d.blood_type, 
            COUNT(*) AS total_matches
        FROM donors d
        INNER JOIN patients p
            ON d.blood_type = p.blood_type
        GROUP BY d.blood_type
        ORDER BY total_matches DESC
    ";

    $result_blood = $conn->query($sql_blood);

    if ($result_blood->num_rows > 0) {
        echo "<table border='1px'>
                <tr>
                    <th>Blood Type</th>
                    <th>Total</th>
                </tr>";
        while ($row = $result_blood->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['blood_type']}</td>
                    <td>{$row['total_matches']}</td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No blood type matches found.</p>";
    }
}

function displayCompletedTasks($conn) {
    $sql = "SELECT COUNT(*) AS total_completion FROM donors WHERE status = 'Completed'";
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        echo "<div class='completed-box'>";
        echo "<p>Total Completion: <strong>{$row['total_completion']}</strong></p>";
        echo "</div>";
    } else {
        echo "<p>No pending donors found.</p>";
    }

    echo "</td>";
    echo "<td>";

    $sql = "SELECT COUNT(*) AS total_completion FROM patients WHERE status = 'Completed'";
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        echo "<div class='completed-box'>";
        echo "<p>Total Completion: <strong>{$row['total_completion']}</strong></p>";
        echo "</div>";
    } else {
        echo "<p>No pending donors found.</p>";
    }
}
?>