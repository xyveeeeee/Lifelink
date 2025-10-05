<?php
$required_role = 'doctor';
require_once '../php/check_session.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h1>Welcome Dr. <?php echo htmlspecialchars($_SESSION['fullname']); ?></h1>
    <p>Username: <?php echo htmlspecialchars($_SESSION['username']); ?></p>
    <p>Role: <?php echo htmlspecialchars($_SESSION['role']); ?></p>
    <p><a href="../php/logout.php">Logout</a></p>
</body>
</html>
