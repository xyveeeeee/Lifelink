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
                            $status = $_POST['status'];
                            $name = $_POST['name'];
                            $age = $_POST['age'];
                            $gender = $_POST['gender'];
                            $organ = $_POST['organ'];
                            $blood_type = $_POST['blood_type'];
                            $location = $_POST['location'];

                            $sql = "INSERT INTO patients (status, name, age, gender, organ, blood_type, location) VALUES (?, ?, ?, ?, ?, ?, ?)";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("ssissss", $status, $name, $age, $gender, $organ, $blood_type, $location);

                            if ($stmt->execute()) {
                                echo "<div class='message'><p>New patient has been added!</div></p>";
                                // Refresh page to show new record
                                echo "<meta http-equiv='refresh' content='2.5'>";
                            } else {
                                echo "Error: " . $stmt->error;
                            }
                            $stmt->close();
                        }
                        ?>
                        </p>
                        <form method="POST">
                            <input type="text" name="status" placeholder="Status" class="add-patient" required>
                            <input type="text" name="name" placeholder="Name" class="add-patient" required>
                            <input type="number" name="age" placeholder="Age" min="1" max="120" class="add-patient" required>
                            <input type="text" name="gender" placeholder="Gender" class="add-patient" required>
                            <input type="text" name="organ" placeholder="Needed Organ" class="add-patient" required>
                            <input type="text" name="blood_type" placeholder="Blood Type" class="add-patient" required>
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
                                            <td><p>" . htmlspecialchars($row['organ']) . "</p></td>
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