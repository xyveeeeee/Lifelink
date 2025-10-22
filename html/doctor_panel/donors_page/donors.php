<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Records</title>
    <link rel="stylesheet" href="donors_style.css">
</head>
<body>

    <div class="content-container">
        <h2>Donors List</h2>
        <div class="table-container">
            <?php 
                include_once 'donors_table.php'; 
                displayAllDonors($conn);
            ?>
        </div>

        <hr class="interactive-underline">
        <br>

        <h2>Donor Status</h2>
        <div class="status-section">
            <?php
                echo "<div class='status-table'>";
                echo "<h2 class='other-text'>Pending</h2>";
                displayDonorsByPending($conn);
                echo "</div>";
            ?>

            <?php
                echo "<div class='status-table'>";
                echo "<h2 class='other-text'>Confirmed</h2>";
                displayDonorsByConfirmed($conn);
                echo "</div>";
            ?>

            <?php
                echo "<div class='status-table'>";
                echo "<h2 class='other-text'>Declined</h2>";
                displayDonorsByDeclined($conn);
                echo "</div>";
            ?>
        </div>
    </div>
</body>
</html>

