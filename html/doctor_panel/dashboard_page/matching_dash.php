<?php
include "../doctors_db.php";

//AVAILABLE DONORS FUNCTION
function displayAvailableDonors($conn) {
    $organ_type = "
        SELECT organ_type, COUNT(*) AS total 
        FROM donations 
        WHERE status = 'Pending' 
            AND organ_type IS NOT NULL 
        GROUP BY organ_type
    ";
    $blood_cell = "
        SELECT blood_cell, COUNT(*) AS total
        FROM donations WHERE status = 'Pending'
            AND blood_cell IS NOT NULL
        GROUP BY blood_cell
        ";
    $blood_type = "
        SELECT blood_type, COUNT(*) AS total 
        FROM donations 
        WHERE status = 'Pending' 
            AND blood_type IS NOT NULL 
        GROUP BY blood_type
    ";
    
    $organ_typeResult = $conn->query($organ_type);
    $blood_cellResult = $conn->query($blood_cell);
    $blood_typeResult = $conn->query($blood_type);

    $maxDonorRows = max($organ_typeResult->num_rows, $blood_cellResult->num_rows, $blood_typeResult->num_rows);

    for ($i = 0; $i < $maxDonorRows; $i++) {
        echo "<tr>";

        // ORGAN COLUMN
        $organ_typeRow = $organ_typeResult->fetch_assoc();
        if ($organ_typeRow) {
            echo "<td>{$organ_typeRow['organ_type']}</td><td>{$organ_typeRow['total']}</td>";
        } else {
            echo "<td></td><td></td>";
        }

        // BLOOD CELL COLUMN
        $blood_cellRow = $blood_cellResult->fetch_assoc();
        if ($blood_cellRow) {
            echo "<td>{$blood_cellRow['blood_cell']}</td><td>{$blood_cellRow['total']}</td>";
        } else {
            echo "<td></td><td></td>";
        }

        // BLOOD TYPE
        $blood_typeRow = $blood_typeResult->fetch_assoc();
        if ($blood_typeRow) {
            echo "<td>{$blood_typeRow['blood_type']}</td><td>{$blood_typeRow['total']}</td>";
        } else {
            echo "<td></td><td></td>";
        }

        echo "</tr>";
    }
}

//PATIENT NEEDS FUNCTION
function displayPatientNeeds($conn) {
    $organ_type = "
        SELECT organ_type, COUNT(*) AS total 
        FROM patients
        WHERE status = 'Pending' 
            AND organ_type IS NOT NULL 
        GROUP BY organ_type
    ";
    $blood_type = "
        SELECT blood_type, COUNT(*) AS total 
        FROM patients
        WHERE status = 'Pending' 
            AND blood_type IS NOT NULL 
        GROUP BY blood_type
    ";

    $organ_typeResult = $conn->query($organ_type);
    $blood_typeResult = $conn->query($blood_type);
    
    $maxPatientRows = max($organ_typeResult->num_rows, $blood_typeResult->num_rows);

    for ($i = 0; $i < $maxPatientRows; $i++) {
        echo "<tr>";

        // ORGAN COLUMN
        $organ_typeRow = $organ_typeResult->fetch_assoc();
        if ($organ_typeRow) {
            echo "<td>{$organ_typeRow['organ_type']}</td><td>{$organ_typeRow['total']}</td>";
        } else {
            echo "<td></td><td></td>";
        }

        // BLOOD TYPE COLUMN
        $blood_typeRow = $blood_typeResult->fetch_assoc();
        if ($blood_typeRow) {
            echo "<td>{$blood_typeRow['blood_type']}</td><td>{$blood_typeRow['total']}</td>";
        } else {
            echo "<td></td><td></td>";
        }

        echo "</tr>";
    }
}

//NEW MATCHES FUNCTION
function displayNewMatches($conn) {
    //MATCHING FUNCTION
    $sql = "
    SELECT 
        d.id AS donation_id,
        d.blood_cell AS donation_cell,
        d.user_id,
        u.id AS donor_id,
        u.fullname AS donor_name,
        p.id AS patient_id,
        p.name AS patient_name,
        CASE
            WHEN d.organ_type = p.organ_type AND d.organ_type IS NOT NULL THEN 'Organ'
            WHEN d.blood_type = p.blood_type AND d.blood_type IS NOT NULL THEN d.blood_cell
        END AS match_type,
        COALESCE(d.organ_type, d.blood_type) AS matched_value
    FROM donations d
    INNER JOIN users u ON d.user_id = u.id
    INNER JOIN patients p
        ON (
            (d.organ_type = p.organ_type AND d.organ_type IS NOT NULL)
            OR
            (d.blood_type = p.blood_type AND d.blood_type IS NOT NULL)
        )
    WHERE LOWER(d.status) = 'pending'
    AND LOWER(p.status) = 'pending'
    ORDER BY match_type
    ";

    //CONFIRM FUNCTION
    if (isset($_GET['confirm'])) {
        $donation_id = intval($_GET['donation_id']);
        $patient_id = intval($_GET['patient_id']);

        $update_donor = "UPDATE donations SET status='Confirmed' WHERE id=$donation_id";
        $update_patient = "UPDATE patients SET status='Confirmed' WHERE id=$patient_id";

        if (mysqli_query($conn, $update_donor) && mysqli_query($conn, $update_patient)) {
            echo "<script>alert('Request has been confirmed!');</script>";
        } else {
            echo "<script>alert('Error confirming request: " . mysqli_error($conn) . "');</script>";
        }
    }

    //DECLINE FUNCTION
    if (isset($_GET['decline'])) {
        $donation_id = intval($_GET['donation_id']);

        $update_donor = "UPDATE donations SET status='Declined' WHERE id=$donation_id";

        if (mysqli_query($conn, $update_donor)) {
            echo "<script>alert('Request has been declined!');</script>";
        } else {
            echo "<script>alert('Error declining request: " . mysqli_error($conn) . "');</script>";
        }
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "
                <div class='report-box'>
                    <p class='report-text'><strong>Donor:</strong> {$row['donor_id']} - {$row['donor_name']}</p>
                    <p class='report-text'><strong>Patient:</strong> {$row['patient_id']} - {$row['patient_name']}</p>
                    <p class='report-text'><strong>{$row['match_type']}</strong></p>
                    <p class='report-text'><strong>{$row['matched_value']}</strong></p>
                    <div class'buttons'>
                        <a href='?confirm=1&donation_id={$row['donation_id']}&patient_id={$row['patient_id']}' class='confirm'>
                            Confirm
                        </a>
                        <a href='?decline=1&donation_id={$row['donation_id']}' class='decline'>
                            Decline
                        </a>
                    </div>
                </div>
                ";
        }
        
    } else {
        echo "<p>No matches found.</p>";
    }
}
?>