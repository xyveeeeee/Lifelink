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

        <title>Patients Table</title>

    </head>
    <body>
        <div class="content-2">
            <table>
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
                    <th><p>Action</p></th>
                </tr>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php
                        if (isset($_POST['update'])) {
                            $id = intval($_POST['id']);
                            $status = $_POST['status'];
                            $name = $_POST['name'];
                            $age = $_POST['age'];
                            $gender = $_POST['gender'];
                            $organ = $_POST['organ'];
                            $blood_type = $_POST['blood_type'];
                            $location = $_POST['location'];

                            $sql = "UPDATE patients SET status=?, name=?, age=?, gender=?, organ=?, blood_type=?, location=? WHERE id=?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("ssissssi", $status, $name, $age, $gender, $organ, $blood_type, $location, $id);

                            if ($stmt->execute()) {
                                echo "<script>alert('Patient has been updated!');</script>";
                            } else {
                                echo "<script>alert('Error updating patient: ');</script>" . $stmt->error;
                            }

                            echo "<meta http-equiv='refresh' content='0'>";

                            $stmt->close();
                        }

                        if (isset($_GET['delete'])) {
                            $id = intval($_GET['delete']);
                            $sql = "DELETE FROM patients WHERE id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $id);

                            if ($stmt->execute()) {
                                echo "<script>alert('Patient has been successfully deleted!');</script>";
                            } else {
                                echo "<script>alert('Error deleting patient: ');</script>" . $stmt->error;
                            }

                            $stmt->close();
                        }
                        ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <form method="POST">
                                <td><p><?php echo htmlspecialchars($row['id']) ?></p></td>
                                <td><input type="text" name="status" class="status" value="<?= $row['status'] ?>"></td>
                                <td><input type="text" name="name" class="name" value="<?= $row['name'] ?>"></td>
                                <td><input type="number" name="age" class="age" value="<?= $row['age'] ?>"></td>
                                <td><input type="text" name="gender" class="gender" value="<?= $row['gender'] ?>"></td>
                                <td><input type="text" name="organ" class="organ" value="<?= $row['organ'] ?>"></td>
                                <td><input type="text" name="blood_type" class="blood-type" value="<?= $row['blood_type'] ?>"></td>
                                <td><input type="text" name="location" class="location" value="<?= $row['location'] ?>"></td>
                                <td><p><?php echo htmlspecialchars($row['created_at']) ?></p></td>
                                <td>
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn btn-update" name="update">Update</button>
                                    <button type="button" class="btn-delete"><a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Warning! This record will be lost once removed.')">Delete</a></button>
                                </td>
                            </form>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9"><?php echo "<div class='message'><p>No records found.</p></div>"; ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </body>
</html>