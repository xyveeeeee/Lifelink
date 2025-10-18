<?php
include "../doctors_db.php";

//AVAILABLE DONORS FUNCTION
function displayAvailableDonors($conn) {
    $donationTypeQuery = "SELECT donation_type, COUNT(*) AS total FROM donors WHERE status = 'Pending' GROUP BY donation_type";
    $organQuery = "SELECT organ, COUNT(*) AS total FROM donors WHERE status = 'Pending' GROUP BY organ";
    $bloodTypeQuery = "SELECT blood_type, COUNT(*) AS total FROM donors WHERE status = 'Pending' GROUP BY blood_type";

    $donationTypeResult = $conn->query($donationTypeQuery);
    $organResult = $conn->query($organQuery);
    $bloodTypeResult = $conn->query($bloodTypeQuery);

    $maxRows = max($donationTypeResult->num_rows, $organResult->num_rows, $bloodTypeResult->num_rows);

    for ($i = 0; $i < $maxRows; $i++) {
        echo "<tr>";

        // Donation Type column
        $donationRow = $donationTypeResult->fetch_assoc();
        if ($donationRow) {
            echo "<td>{$donationRow['donation_type']}</td><td>{$donationRow['total']}</td>";
        } else {
            echo "<td></td><td></td>";
        }

        // Organ column
        $organRow = $organResult->fetch_assoc();
        if ($organRow) {
            echo "<td>{$organRow['organ']}</td><td>{$organRow['total']}</td>";
        } else {
            echo "<td></td><td></td>";
        }

        // Blood Type column
        $bloodRow = $bloodTypeResult->fetch_assoc();
        if ($bloodRow) {
            echo "<td>{$bloodRow['blood_type']}</td><td>{$bloodRow['total']}</td>";
        } else {
            echo "<td></td><td></td>";
        }

        echo "</tr>";
    }
}

//PATIENT NEEDS FUNCTION
function displayPatientNeeds($conn) {
    $patientOrganQuery = "SELECT organ, COUNT(*) AS total FROM patients WHERE status = 'Pending' GROUP BY organ";
    $patientBloodTypeQuery = "SELECT blood_type, COUNT(*) AS total FROM patients WHERE status = 'Pending' GROUP BY blood_type";

    $patientOrganResult = $conn->query($patientOrganQuery);
    $patientBloodTypeResult = $conn->query($patientBloodTypeQuery);
    
    $maxPatientRows = max($patientOrganResult->num_rows, $patientBloodTypeResult->num_rows);

    for ($i = 0; $i < $maxPatientRows; $i++) {
        echo "<tr>";

        // Organ column
        $patientOrganRow = $patientOrganResult->fetch_assoc();
        if ($patientOrganRow) {
            echo "<td>{$patientOrganRow['organ']}</td><td>{$patientOrganRow['total']}</td>";
        } else {
            echo "<td></td><td></td>";
        }

        // Blood type column
        $patientBloodRow = $patientBloodTypeResult->fetch_assoc();
        if ($patientBloodRow) {
            echo "<td>{$patientBloodRow['blood_type']}</td><td>{$patientBloodRow['total']}</td>";
        } else {
            echo "<td></td><td></td>";
        }

        echo "</tr>";
    }
}

//NEW MATCHES FUNCTION
function displayNewMatches($conn) {
    $sql = "
    SELECT 
        d.id AS donor_id,
        d.name AS donor_name,
        p.id AS patient_id,
        p.name AS patient_name,
        CASE 
            WHEN d.organ = p.organ AND d.blood_type = p.blood_type THEN 'Both'
            WHEN d.organ = p.organ THEN 'Organ'
            WHEN d.blood_type = p.blood_type THEN 'Blood Type'
        END AS match_type,
        CASE 
            WHEN d.organ = p.organ AND d.blood_type = p.blood_type THEN CONCAT(d.organ, ' / ', d.blood_type)
            WHEN d.organ = p.organ THEN d.organ
            WHEN d.blood_type = p.blood_type THEN d.blood_type
        END AS matched_value,
        d.status AS donor_status,
        p.status AS patient_status
    FROM donors d
    INNER JOIN patients p
        ON (d.organ = p.organ OR d.blood_type = p.blood_type)
    WHERE d.status = 'Pending' AND p.status = 'Pending'
    ORDER BY match_type
    ";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "
                <div class='report-box'>
                    <p class='report-text'><strong>Donor:</strong> {$row['donor_id']} - {$row['donor_name']}</p>
                    <p class='report-text'><strong>Patient:</strong> {$row['patient_id']} - {$row['patient_name']}</p>
                    <p class='report-text'><strong>{$row['match_type']}</strong></p>
                    <p class='report-text'><strong>{$row['matched_value']}</strong></p>
                </div>
                ";
        }
    } else {
        echo "<p>No matches found.</p>";
    }

    $conn->close();
    }

/*
function displayRequests($conn) {
    
}
*/
?>