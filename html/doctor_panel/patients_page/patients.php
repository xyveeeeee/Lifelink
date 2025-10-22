<?php
include "../doctors_db.php"; // include instead of require_once

// READ
$sql = "SELECT * FROM patients ORDER BY id DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="patients_style.css">

        <title>LifeLink</title>
        <body>


            <div class="content">
                <div class="content-container-1">
                    <div class="interactive-content">
                        <h2>Add Patients</h2>
                        <br>
                        <hr class="interactive-underline">
                        <br>
                        <?php

                        // CREATE
                        if (isset($_POST['create'])) {
                            $status = 'Pending';
                            $name = $_POST['name'];
                            $age = $_POST['age'];
                            $gender = $_POST['gender'];
                            $organ_type = $_POST['organ_type'] ?? null;
                            $blood_type = $_POST['blood_type'] ?? null;
                            $location = $_POST['location'];

                            $sql = "INSERT INTO patients (status, name, age, gender, organ_type, blood_type, location) VALUES (?, ?, ?, ?, ?, ?, ?)";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("ssissss", $status, $name, $age, $gender, $organ_type, $blood_type, $location);

                            if ($stmt->execute()) {
                                echo "<script>alert('Patient has been inserted!');</script>";
                                // Refresh page to show new record
                            } else {
                                echo "<script>alert('Error: ');</script>" . $stmt->error;
                            }

                            echo "<meta http-equiv='refresh' content='0'>";

                            $stmt->close();
                        }
                        ?>
                        </p>
                        <form method="POST">
                            <input type="text" name="name" placeholder="Name" class="add-patient" required>
                            <input type="number" name="age" placeholder="Age" min="1" max="120" class="add-patient" required>
                            <select id="gender" name="gender" class="add-patient">
                                <option value="" disabled selected>Gender</option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Other</option>
                            </select>
                            <select id="organ_type" name="organ_type" class="add-patient">
                                <option value="" disabled selected>Needed Organ</option>
                                <option>Kidney</option>
                                <option>Liver</option>
                                <option>Heart</option>
                                <option>Lungs</option>
                                <option>Pancreas</option>
                                <option>Intestine</option>
                                <option>Cornea</option>
                            </select>
                            <select id="blood_type" name="blood_type" class="add-patient">
                                <option value="" disabled selected>Blood Type</option>
                                <option>A+</option>
                                <option>A-</option>
                                <option>B+</option>
                                <option>B-</option>
                                <option>AB+</option>
                                <option>AB-</option>
                                <option>O+</option>
                                <option>O-</option>
                            </select>
                            <input type="text" name="location" placeholder="Location" class="add-patient" required>
                            <button type="submit" name="create">Add</button>
                        </form>
                    </div>
                </div>

                <div class="content-container-2">
                    <div class="interactive-content">
                        <h2>Patients List</h2>
                        <br>
                        <hr class="interactive-underline">
                        <br>
                        <table>
                            <?php
                            if ($result && $result->num_rows > 0) {
                                echo "
                                    <tr>
                                        <th><p>ID</p></th>
                                        <th><p>Status</p></th>
                                        <th><p>Name</p></th>
                                        <th><p>Age</p></th>
                                        <th><p>Gender</p></th>
                                        <th><p>Needed Organ</p></th>
                                        <th><p>Blood Type</p></th>
                                        <th><p>Location</p></th>
                                        <th><p>Created At</p></th>
                                    </tr>";
                            ?>
                            <tbody>
                                <?php
                                    while ($row = $result->fetch_assoc()) {
                                        echo "
                                        <tr>
                                            <td><p>" . htmlspecialchars($row['id']) . "</p></td>
                                            <td><p>" . htmlspecialchars($row['status']) . "</p></td>
                                            <td><p>" . htmlspecialchars($row['name']) . "</p></td>
                                            <td><p>" . htmlspecialchars($row['age']) . "</p></td>
                                            <td><p>" . htmlspecialchars($row['gender']) . "</p></td>
                                            <td><p>" . htmlspecialchars($row['organ_type']) . "</p></td>
                                            <td><p>" . htmlspecialchars($row['blood_type']) . "</p></td>
                                            <td><p>" . htmlspecialchars($row['location']) . "</p></td>
                                            <td><p>" . htmlspecialchars($row['created_at']) . "</p></td>
                                        </tr>";
                                    }
                                    } else {
                                        // 6. If no records found
                                        echo "<div class='message'><p>No records found.</p></div>";
                                    }
                                    
                                    // 6. Close connection
                                    $conn->close();
                                ?>
                            </tbody>
                        </table>
                        <br>
                        <form method="POST" class="view">
                            <a href="patients_table.php" class="link-edit">Edit</a>
                        </form>
                    </div>
                    <br>
                </div>
            </div>
        </body>
    </head>
</html>