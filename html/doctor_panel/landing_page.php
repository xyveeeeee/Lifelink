<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="landing_style.css">

        <title>LifeLink</title>
    </head>
    <body>
        <div class="nav-bar">
            <div class="nav-container">
                <div class="logo">
                    <img src="../../image/logo.png" alt="logo">
                    <h2 class="web-title">LifeLink</h2>
                </div>
                <nav>
                    <ul class="nav-menu">
                        <li class="nav-link">
                            <a href="dashboard_page/dashboard.php" class="active" target="top-page">Dashboard</a>
                            <hr class="nav-underline">
                        </li>
                        <li class="nav-link">
                            <a href="donor_page/donor.php" target="top-page">Donor</a>
                            <hr class="nav-underline">
                        </li>
                        <li class="nav-link">
                            <a href="patients_page/patients.php" target="top-page">Patients</a>
                            <hr class="nav-underline">
                        </li>
                        <li class="nav-link">
                            <a href="profile_page/profile_info.php" target="top-page">Profile</a>
                            <hr class="nav-underline">
                        </li>
                            <li class="nav-link">
                                <a href="../../php/logout.php">Log Out</a>
                                <hr class="nav-underline">
                            </li>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <iframe name="top-page" id="top-page" src="dashboard_page/dashboard.php"></iframe>
    </body>
</html>