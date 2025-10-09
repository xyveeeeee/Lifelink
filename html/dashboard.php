<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/dashboard_style.css">

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
                            <hr class="default-underline">
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
                            <hr class="nav-underline">
                        </li>
                    </ul>
                </div>
            </div>

            <div class="frame">
                <ul class="progress-container">
                    <li class="progress-link">
                        <a href="#"><h2>Find Match</h2></a>
                        <br>
                        <hr class="progress-underline">
                    </li>
                    <br>
                    <p>(No results...)</p>
                    <br>
                    <li class="progress-link">
                        <a href="#"><h2>Patient Status</h2></a>
                        <br>
                        <hr class="progress-underline">
                    </li>
                    <br>
                    <p>(No results...)</p>
                    <br>
                    <li class="progress-link">
                        <a href="#"><h2>Pending Machines</h2></a>
                        <br>
                        <hr class="progress-underline">
                    </li>
                    <br>
                    <p>(No results...)</p>
                    <br>
                </ul>
                <div class="matching-table">
                    <h2 class="match-text">Matching Dashboard</h2>
                    <br>
                    <hr class="match-underline">
                    <br>
                    <div class="match-container">
                        <div class="results">
                            <div class="texts">
                                <h2 class="black-text">Available</h2>
                                <p></p>
                                <h2>Donors</h2>
                            </div>
                            <br>
                            <div class="boxes">
                                <p class="box">(No results...)</p>
                            </div>
                        </div>
                        <div class="results">
                            <div class="texts">
                                <h2 class="black-text">Patient </h2>
                                <p></p>
                                <h2>Needs</h2>
                            </div>
                            <br>
                            <div class="boxes">
                                <p class="box">(No results...)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
    </head>
</html>