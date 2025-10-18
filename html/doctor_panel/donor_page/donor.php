<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Records</title>
    <link rel="stylesheet" href="donor_style.css">
</head>
<body>
    <h2>Donors List</h2>
    <div class="table-container">
        <?php 
            include_once 'donor_table.php'; 
            displayAllDonors($conn);
        ?>
    </div>

    <hr>

    <h2>Donor Status Overview</h2>
    <div class="status-section">
        <?php
            $statuses = ['Pending', 'Approved', 'Rejected', 'Completed', 'Cancelled'];
            foreach ($statuses as $status) {
                echo "<div class='status-table'>";
                echo "<h2>$status</h2>";
                displayDonorsByStatus($status);
                echo "</div>";
            }
        ?>
    </div>
</body>
</html>

