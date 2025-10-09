<?php
$required_role = 'doctor';
require_once '../php/check_session.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/profile_style.css">

        <title>LifeLink</title>
        <body>
            <div class="nav-bar">
                <div class="nav-container">
                    <div class="logo">
                        <img src="../image/logo.png" alt="logo">
                        <h2 class="web-title">LifeLink</h2>
                    </div>
                    <!--
                    <button class="nav-toggle">
                        <span class="bar"></span>
                        <span class="bar"></span>
                        <span class="bar"></span>
                    </button>
                    -->
                    <ul class="nav-menu">
                        <li class="nav-link">
                            <a href="dashboard.php">Dashboard</a>
                            <hr class="nav-underline">
                        </li>
                        <li class="nav-link">
                            <a href="doctor.php">Donor</a>
                            <hr class="nav-underline">
                        </li>
                        <li class="nav-link">
                            <a href="patients.php">Patients</a>
                            <hr class="nav-underline">
                        </li>
                        <li class="nav-link">
                            <a href="profile.php">Profile</a>
                            <hr class="default-underline">
                        </li>
                    </ul>
                </div>
            </div>

            <div class="frame">
                        <p>Dr. <?php echo htmlspecialchars($_SESSION['fullname']); ?></p>
                        <p>Gmail: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
                        <a href="Log In.php">Log out</a>
            </div>
        </body>
    </head>
</html>