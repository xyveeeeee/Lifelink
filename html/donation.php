    <?php
    $required_role = 'donor';
    require_once '../php/check_session.php';
    ?>
    <!doctype html>
    <html lang="en">
    <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>LifeLink — Create Donation</title>

    <link rel="stylesheet" href="../css/donor_style.css">
    <link rel="stylesheet" href="../css/donation.css">
    </head>
    <body>

    <!-- NAVIGATION BAR -->
    <nav class="nav-bar" role="navigation" aria-label="Main navigation">
        <div class="nav-container">
        <div class="logo" aria-hidden="true">
            <img src="../image/logo.png" alt="LifeLink Logo" class="logo-icon">
            <h2 class="web-title">LifeLink</h2>
        </div>

        <ul class="nav-menu" role="menubar" aria-label="Primary">
            <li class="nav-link"><a href="donation.php" aria-current="page">Donation</a><hr class="nav-underline default-underline"></li>
            <li class="nav-link"><a href="notification.html">Notification</a><hr class="nav-underline"></li>
            <li class="nav-link"><a href="history.php">Donation History</a><hr class="nav-underline"></li>
            <li class="nav-link"><a href="profile.php">Profile</a><hr class="nav-underline"></li>
        </ul>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="page-wrapper">

        <section class="panel left-panel">
        <div class="left-inner">
            <h1 class="signin-title">Donation Form</h1>

            <form class="signin-form" id="donationForm" action="../php/donate_process.php" method="post" autocomplete="on">

            <!-- Donation Type -->
            <label for="donationType" class="field-label">Donation Type</label>
            <div class="input-box">
                <select id="donationType" name="donation_type" required>
                <option value="" disabled selected>Choose type</option>
                <option value="organ">Organ Donation</option>
                <option value="blood">Blood Donation</option>
                </select>
            </div>

            <!-- Organ Type -->
            <div id="organType" style="display: none;">
                <label for="organ" class="field-label">Organ Type</label>
                <div class="input-box">
                <select id="organ" name="organ_type">
                    <option value="" disabled selected>Choose an organ</option>
                    <option>Kidney</option>
                    <option>Liver</option>
                    <option>Heart</option>
                    <option>Lung</option>
                    <option>Pancreas</option>
                    <option>Intestine</option>
                    <option>Cornea</option>
                    <option>Other</option>
                </select>
                </div>
            </div>

            <!-- Blood Type -->
            <div id="bloodType" style="display: none;">
                <label for="blood" class="field-label">Type of Blood Donation</label>
                <div class="input-box">
                <select id="blood" name="blood_type">
                    <option value="" disabled selected>Select blood donation type</option>
                    <option value="Whole Blood">Whole Blood</option>
                    <option value="Platelet">Platelet (Apheresis)</option>
                    <option value="Plasma">Plasma (Plasmapheresis)</option>
                    <option value="Double Red Cells">Double Red Cell</option>
                </select>
                </div>
            </div>

            <!-- Volume Options (each hidden by default) -->
            <div id="wholeType" class="dose-option" style="display: none;">
                <label for="wholetype" class="field-label">Whole Blood Amount</label>
                <div class="input-box">
                <select id="wholetype" name="volume">
                    <option value="" disabled selected>Select amount</option>
                    <option>350 mL</option>
                    <option>450 mL</option>
                    <option>500 mL</option>
                </select>
                </div>
            </div>

            <div id="plateType" class="dose-option" style="display: none;">
                <label for="platetype" class="field-label">Platelet Volume</label>
                <div class="input-box">
                <select id="platetype" name="volume">
                    <option value="" disabled selected>Select amount</option>
                    <option>200 mL</option>
                    <option>300 mL</option>
                    <option>400 mL</option>
                </select>
                </div>
            </div>

            <div id="plasmaType" class="dose-option" style="display: none;">
                <label for="plasmatype" class="field-label">Plasma Volume</label>
                <div class="input-box">
                <select id="plasmatype" name="volume">
                    <option value="" disabled selected>Select amount</option>
                    <option>500 mL</option>
                    <option>625 mL</option>
                    <option>800 mL</option>
                </select>
                </div>
            </div>

            <div id="redType" class="dose-option" style="display: none;">
                <label for="redtype" class="field-label">Double Red Cell Volume</label>
                <div class="input-box">
                <select id="redtype" name="volume">
                    <option value="" disabled selected>Select amount</option>
                    <option>350 mL</option>
                    <option>400 mL</option>
                </select>
                </div>
            </div>

            <!-- Availability Date -->
            <label for="availability" class="field-label">Availability Date</label>
            <div class="input-box">
                <input id="availability" name="availability_date" type="date" required />
            </div>

            <!-- Hospital -->
            <label for="hospitals" class="field-label">Hospital</label>
            <div class="input-box">
                <select id="hospitals" name="hospital" required>
                <option value="" disabled selected>Choose a hospital</option>
                <option>Region 1 Medical Center</option>
                <option>National Kidney and Transplant Institute (NKTI)</option>
                <option>Lung Center of the Philippines (LCP)</option>
                <option>St. Luke’s Medical Center</option>
                <option>The Medical City</option>
                <option>Philippine General Hospital</option>
                <option>Philippine Heart Center</option>
                <option>Baguio General Hospital & Medical Center</option>
                <option>Rizal Medical Center</option>
                </select>
            </div>

            <!-- Checkbox Confirmation -->
            <div class="checkbox-wrap">
                <label>
                <input type="checkbox" id="confirmCheckbox" required>
                I confirm that the information I provided is true and I voluntarily wish to donate.
                </label>
            </div>

            <div class="cta-wrap">
                <button type="submit" class="btn-primary">Submit</button>
            </div>
            </form>
        </div>
        </section>

        <aside class="panel right-panel">
        <div class="right-inner">
            <h2 class="welcome-heading">CREATE DONATION TODAY!</h2>
            <p class="welcome-copy">
            Every moment counts when a life hangs in the balance. By offering your organ for donation, you’re giving someone the precious gift of hope and a second chance at life. Simply share your donation details to help connect with patients waiting for transplants. Your compassion today can become someone’s tomorrow — start your life-changing journey now.
            </p>
        </div>
        </aside>

    </main>

    <!-- SCRIPT -->
    <script>
        const donationType = document.getElementById('donationType');
        const organSection = document.getElementById('organType');
        const bloodSection = document.getElementById('bloodType');
        const doseSections = document.querySelectorAll('.dose-option');
        const bloodSelect = document.getElementById('blood');

        // Show/Hide Organ or Blood donation sections
        donationType.addEventListener('change', () => {
        if (donationType.value === 'organ') {
            organSection.style.display = 'block';
            bloodSection.style.display = 'none';
            doseSections.forEach(s => s.style.display = 'none');
        } else if (donationType.value === 'blood') {
            organSection.style.display = 'none';
            bloodSection.style.display = 'block';
        } else {
            organSection.style.display = 'none';
            bloodSection.style.display = 'none';
            doseSections.forEach(s => s.style.display = 'none');
        }
        });

        // Show correct blood volume based on type
        bloodSelect.addEventListener('change', () => {
        doseSections.forEach(s => s.style.display = 'none'); // hide all
        if (bloodSelect.value === 'Whole Blood') document.getElementById('wholeType').style.display = 'block';
        if (bloodSelect.value === 'Platelet') document.getElementById('plateType').style.display = 'block';
        if (bloodSelect.value === 'Plasma') document.getElementById('plasmaType').style.display = 'block';
        if (bloodSelect.value === 'Double Red Cells') document.getElementById('redType').style.display = 'block';
        });

        // Form confirmation
        const form = document.getElementById('donationForm');
        const checkbox = document.getElementById('confirmCheckbox');

        form.addEventListener('submit', (e) => {
        if (!checkbox.checked) {
            e.preventDefault();
            alert('Please confirm that your information is correct before continuing.');
        }
        });
    </script>
    </body>
    </html>
