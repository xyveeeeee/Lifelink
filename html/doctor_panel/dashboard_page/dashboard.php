<?php
include "../doctors_db.php";

$sql = "SELECT * FROM patients ORDER BY id DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="dashboard_style.css">

        <title>LifeLink</title>
        <body>
            <div class="content">
                <div class="content-container-1">
                    <div class="interactive-content">
                        <h2>Currently On-Going</h2>
                        <br>
                        <hr class="interactive-underline">
                        <br>
                        <table>
                            <tr>
                                <th><p>Donors</p></th><th><p>Patients</p></th>
                            </tr>
                            <tr>
                                <td>
                                    <?php
                                        include_once "status_checking.php";
                                        displayCurrentlyOnGoing1($conn);
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        include_once "status_checking.php";
                                        displayCurrentlyOnGoing2($conn);
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <br>
                    <div class="interactive-content">
                        <h2>Found Matches</h2>
                        <br>
                        <hr class="interactive-underline">
                        <br>
                        <?php
                            include_once "status_checking.php";
                            displayFoundMatches($conn);
                        ?>


                    </div>
                    <br>
                    <div class="interactive-content">
                        <h2>Completed Tasks</h2>
                        <br>
                        <hr class="interactive-underline">
                        <br>
                        <table>
                            <tr>
                                <th><p>Donors</p></th><th><p>Patients</p></th>
                            </tr>
                            <tr>
                                <td>
                                    <?php
                                    include_once "status_checking.php";
                                    displayCompletedTasks($conn);
                                    ?>
                                </td>
                            </tr>
                        </table>
                        <br>
                    </div>
                </div>

                <div class="content-container-2">
                    <div class="interactive-content">
                        <h2 class="match-text">Matching Dashboard</h2>
                        <br>
                        <hr class="interactive-underline">
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
                                    <table>
                                        <tr>
                                            <th>Donation Type</th>
                                            <th>Total</th>
                                            <th>Organ</th>
                                            <th>Total</th>
                                            <th>Blood Type</th>
                                            <th>Total</th>
                                        </tr>
                                        <?php
                                            include_once "matching_dash.php";
                                            displayAvailableDonors($conn);
                                        ?>
                                    </table>
                                </div>
                            </div>
                            <div class="results">
                                <div class="texts">
                                    <h2 class="black-text">Patient</h2>
                                    <p></p>
                                    <h2>Needs</h2>
                                </div>
                                <br>
                                <div class="boxes">
                                    <table>
                                        <tr>
                                            <th>Organ</th>
                                            <th>Total</th>
                                            <th>Blood Type</th>
                                            <th>Total</th>
                                        </tr>
                                        <?php
                                            include_once "matching_dash.php";
                                            displayPatientNeeds($conn);
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="interactive-content">
                        <h2 class="match-text">Reports</h2>
                        <br>
                        <hr class="interactive-underline">
                        <br>
                        <h2>New Matches:</h2>
                        <div class="reports-content">
                            
                            <?php
                                include_once "matching_dash.php";
                                displayNewMatches($conn);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </body>
    </head>
</html>