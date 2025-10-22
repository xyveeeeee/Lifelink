<?php 
$required_role = 'donor';
require_once '../php/check_session.php';
require_once '../php/fetch_donations.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>LifeLink - Donation History</title>

  <link rel="stylesheet" href="../css/donor_style.css">
  <link rel="stylesheet" href="../css/history.css">
  <link rel="stylesheet" href="../css/edit_history.css"> 
</head>
<body>

  <!-- NAVIGATION BAR -->
  <nav class="nav-bar">
    <div class="nav-container">
      <div class="logo">
        <img src="../image/logo.png" alt="LifeLink Logo" class="logo-icon">
        <h2 class="web-title">LifeLink</h2>
      </div>

      <ul class="nav-menu">
        <li class="nav-link"><a href="donation.php">Donation</a><hr class="nav-underline"></li>
        <li class="nav-link"><a href="notification.php">Notification</a><hr class="nav-underline"></li>
        <li class="nav-link active"><a href="history.php">Donation History</a><hr class="nav-underline"></li>
        <li class="nav-link"><a href="profile.php">Profile</a><hr class="nav-underline"></li>
      </ul>
    </div>
  </nav>

  <div class="main-content">
    <div class="profile-card">
      <button class="back-btn" onclick="location.href='donation.php'">← Back</button>
      
      <div class="page-header">
        <h1 class="page-title">Donation History</h1>
        <p class="page-subtitle">Track your past and ongoing donations.</p>
      </div>

      <div class="page-switch">
        <button id="organBtn" class="switch active">Organ</button>
        <button id="bloodBtn" class="switch">Blood</button>
      </div>

      <!-- ORGAN TABLE -->
      <table id="organTable">
        <thead>
          <tr>
            <th>Donation ID</th>
            <th>Organ</th>
            <th>Status</th>
            <th>Hospital</th>
            <th>Date Requested</th>
            <th>Availability Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($organDonations)): ?>
            <?php foreach ($organDonations as $row): ?>
              <tr>
                <td><?= 'D-' . str_pad($row['id'], 4, '0', STR_PAD_LEFT) ?></td>
                <td><?= htmlspecialchars($row['organ_type']) ?></td>
                <td class="<?= strtolower($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['hospital']) ?></td>
                <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                <td><?= date('M d, Y', strtotime($row['availability_date'])) ?></td>
                <td>
                  <button class="edit-btn" 
                    data-id="<?= $row['id'] ?>" 
                    data-type="organ"
                    data-subtype="<?= htmlspecialchars($row['organ_type']) ?>" 
                    data-hospital="<?= htmlspecialchars($row['hospital']) ?>" 
                    data-date="<?= htmlspecialchars($row['availability_date']) ?>">
                    Edit
                  </button>
                  <form action="../php/delete_donation.php" method="POST" onsubmit="return confirm('Delete this donation?');" style="display:inline;">
                    <input type="hidden" name="donation_id" value="<?= $row['id'] ?>">
                    <button type="submit" class="delete-btn">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="7" class="no-data">No organ donations found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>

      <!-- BLOOD TABLE -->
      <table id="bloodTable" class="hidden">
        <thead>
          <tr>
            <th>Donation ID</th>
            <th>Type of Blood</th>
            <th>Volume</th>
            <th>Status</th>
            <th>Hospital</th>
            <th>Date Requested</th>
            <th>Availability Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($bloodDonations)): ?>
            <?php foreach ($bloodDonations as $row): ?>
              <tr>
                <td><?= 'B-' . str_pad($row['id'], 4, '0', STR_PAD_LEFT) ?></td>
                <td><?= htmlspecialchars($row['blood_cell']) ?></td>
                <td><?= htmlspecialchars($row['volume']) ?></td>
                <td class="<?= strtolower($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['hospital']) ?></td>
                <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                <td><?= date('M d, Y', strtotime($row['availability_date'])) ?></td>
                <td>
                  <button class="edit-btn" 
                    data-id="<?= $row['id'] ?>" 
                    data-type="blood"
                    data-subtype="<?= htmlspecialchars($row['blood_type']) ?>" 
                    data-volume="<?= htmlspecialchars($row['volume']) ?>"
                    data-hospital="<?= htmlspecialchars($row['hospital']) ?>" 
                    data-date="<?= htmlspecialchars($row['availability_date']) ?>">
                    Edit
                  </button>
                  <form action="../php/delete_donation.php" method="POST" onsubmit="return confirm('Delete this donation?');" style="display:inline;">
                    <input type="hidden" name="donation_id" value="<?= $row['id'] ?>">
                    <button type="submit" class="delete-btn">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="8" class="no-data">No blood donations found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- EDIT MODAL -->
  <div id="editModal" class="modal hidden">
    <div class="modal-content">
      <h2>Edit Donation</h2>
      <form id="editForm" method="POST" action="../php/update_donation.php">
        <input type="hidden" name="donation_id" id="editDonationId">

        <label for="hospitals">Hospital:</label>
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

        <label for="donationType">Donation Type:</label>
        <select id="donationType" name="donation_type" required>
          <option value="" disabled selected>Choose type</option>
          <option value="organ">Organ Donation</option>
          <option value="blood">Blood Donation</option>
        </select>

        <!-- Organ List -->
        <select id="organ" name="organ_type" class="hidden">
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

        <!-- Blood Type -->
        <select id="bloodType" name="blood_type" class="hidden">
          <option value="" disabled selected>Select blood donation type</option>
            <option >Whole Blood</option>
            <option >Platelet</option>
            <option >Plasma</option>
            <option >Double Red Cells</option>
        </select>

        <!-- blodvolume  -->
        <select id="wholeVolume" name="blood_volume" class="hidden">
          <option value="" disabled selected>Select amount</option>
          <option>350 mL</option>
          <option>450 mL</option>
          <option>500 mL</option>
        </select>

        <select id="plateletVolume" name="blood_volume" class="hidden">
          <option value="" disabled selected>Select amount</option>
          <option>200 mL</option>
          <option>300 mL</option>
          <option>400 mL</option>
        </select>

        <select id="plasmaVolume" name="blood_volume" class="hidden">
          <option value="" disabled selected>Select amount</option>
          <option>500 mL</option>
          <option>625 mL</option>
          <option>800 mL</option>
        </select>

        <select id="redVolume" name="blood_volume" class="hidden">
          <option value="" disabled selected>Select amount</option>
          <option>350 mL</option>
          <option>400 mL</option>
        </select>

        <label for="editDate">Availability Date:</label>
        <input type="date" name="availability_date" id="editDate" required>

        <div class="modal-buttons">
          <button type="submit" class="btn-primary">Save</button>
          <button type="button" id="closeModal" class="btn-secondary">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    const organBtn = document.getElementById('organBtn');
    const bloodBtn = document.getElementById('bloodBtn');
    const organTable = document.getElementById('organTable');
    const bloodTable = document.getElementById('bloodTable');
    const modal = document.getElementById('editModal');
    const closeModal = document.getElementById('closeModal');
    const editButtons = document.querySelectorAll('.edit-btn');

    const donationTypeSelect = document.getElementById('donationType');
    const organSelect = document.getElementById('organ');
    const bloodSelect = document.getElementById('bloodType');
    const wholeVolume = document.getElementById('wholeVolume');
    const plateletVolume = document.getElementById('plateletVolume');
    const plasmaVolume = document.getElementById('plasmaVolume');
    const redVolume = document.getElementById('redVolume');

    // Table toggle
    organBtn.addEventListener('click', () => {
      organBtn.classList.add('active');
      bloodBtn.classList.remove('active');
      organTable.classList.remove('hidden');
      bloodTable.classList.add('hidden');
    });

    bloodBtn.addEventListener('click', () => {
      bloodBtn.classList.add('active');
      organBtn.classList.remove('active');
      bloodTable.classList.remove('hidden');
      organTable.classList.add('hidden');
    });

    // Donation type toggle (inside modal)
    donationTypeSelect.addEventListener('change', function () {
      const val = this.value;
      organSelect.classList.toggle('hidden', val !== 'organ');
      bloodSelect.classList.toggle('hidden', val !== 'blood');
      [wholeVolume, plateletVolume, plasmaVolume, redVolume].forEach(v => v.classList.add('hidden'));
    });

    // Show correct volume dropdown depending on blood type
    bloodSelect.addEventListener('change', function () {
      [wholeVolume, plateletVolume, plasmaVolume, redVolume].forEach(v => v.classList.add('hidden'));
      if (this.value === 'Whole Blood') wholeVolume.classList.remove('hidden');
      else if (this.value.includes('Platelet')) plateletVolume.classList.remove('hidden');
      else if (this.value.includes('Plasma')) plasmaVolume.classList.remove('hidden');
      else if (this.value.includes('Red')) redVolume.classList.remove('hidden');
    });

    // Edit modal logic
    editButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        const hospital = btn.dataset.hospital;
        const type = btn.dataset.type;
        const subtype = btn.dataset.subtype; 
        const volume = btn.dataset.volume;
        const date = btn.dataset.date;

        document.getElementById('editDonationId').value = id;
        document.getElementById('hospitals').value = hospital;
        document.getElementById('editDate').value = date;

        donationTypeSelect.value = type;
        donationTypeSelect.dispatchEvent(new Event('change'));

        if (type === 'organ') {
          organSelect.value = subtype;
        } else if (type === 'blood') {
          bloodSelect.value = subtype;
          bloodSelect.dispatchEvent(new Event('change'));
          // Set volume
          const map = {
            'Whole Blood': wholeVolume,
            'Platelet (Apheresis)': plateletVolume,
            'Plasma (Plasmapheresis)': plasmaVolume,
            'Double Red Cell': redVolume
          };
          if (map[subtype]) map[subtype].value = volume;
        }

        modal.classList.remove('hidden');
      });
    });

    closeModal.addEventListener('click', () => modal.classList.add('hidden'));
  </script>
</body>
</html>
