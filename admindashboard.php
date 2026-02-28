<?php
session_start();
include("db.php"); // ✅ Include database connection

// Handle delete
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $conn->query("UPDATE sign_up SET is_deleted = 1 WHERE id = $id");
}

// Handle restore
if (isset($_GET['restore'])) {
  $id = intval($_GET['restore']);
  $conn->query("UPDATE sign_up SET is_deleted = 0 WHERE id = $id");
}

// Fetch all users
$sql = "SELECT * FROM sign_up WHERE Role = 'user'";
$result = $conn->query($sql);

// ✅ Fetch Login Count (total active users)
$loginCountQuery = $conn->query("SELECT COUNT(*) AS total_users FROM sign_up WHERE Role='user' AND is_deleted=0");
$loginCount = $loginCountQuery->fetch_assoc()['total_users'] ?? 0;

// Fetch all contact messages
$contactMessages = $conn->query("SELECT id, `Name`, `E-mail`, `Your message` FROM `contact us` ORDER BY id DESC");

// ✅ Total Donors (unique people who donated)
$totalDonorsResult = $conn->query("SELECT COUNT(DISTINCT `Full Name`) AS total_donors FROM make_a_donation");
$totalDonors = $totalDonorsResult->fetch_assoc()['total_donors'];

// ✅ Total Donations (sum of all donations)
$totalDonationsResult = $conn->query("SELECT SUM(`Donation Amount`) AS total_donations FROM make_a_donation");
$totalDonations = $totalDonationsResult->fetch_assoc()['total_donations'];

// ✅ Fetch Donor Details (all donors)
$donorDetails = $conn->query("SELECT name, amount FROM donations ORDER BY id DESC");

// ✅ Fetch Active Logins (users not deleted)
$activeUsers = $conn->query("SELECT id, Username, Email FROM sign_up WHERE Role='user' AND is_deleted=0");

// ✅ Fetch Active Donors (group by Full Name and SUM their donations)
$activeDonors = $conn->query("
    SELECT `Full Name` AS name, SUM(`Donation Amount`) AS total_amount
    FROM make_a_donation
    GROUP BY `Full Name`
    ORDER BY total_amount DESC
");
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Govardhan Eco Village</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* Mobile First Responsive Styles */
    @media (max-width: 768px) {
      .container {
        padding: 10px !important;
      }
      
      .stats-box {
        flex-direction: column;
        gap: 15px;
      }
      
      .card {
        width: 100% !important;
        margin-bottom: 15px;
      }
      
      .table-responsive {
        font-size: 0.85rem;
      }
      
      .table th,
      .table td {
        padding: 8px 4px;
      }
      
      h1 {
        font-size: 1.8rem !important;
      }
      
      h2 {
        font-size: 1.4rem !important;
      }
      
      .btn-sm {
        padding: 4px 8px;
        font-size: 0.8rem;
      }
      
      .card h2 {
        font-size: 1.5rem !important;
      }
      
      .card p {
        font-size: 0.9rem;
      }
    }

    @media (max-width: 576px) {
      .container {
        padding: 5px !important;
      }
      
      .table-responsive {
        font-size: 0.75rem;
      }
      
      .table th,
      .table td {
        padding: 6px 2px;
      }
      
      h1 {
        font-size: 1.5rem !important;
      }
      
      h2 {
        font-size: 1.2rem !important;
      }
      
      .btn-sm {
        padding: 3px 6px;
        font-size: 0.7rem;
        margin: 2px;
      }
      
      .card {
        padding: 15px !important;
      }
      
      .card h2 {
        font-size: 1.3rem !important;
      }
    }

    /* Base Styles */
    body {
      font-family: 'Segoe UI', sans-serif;
      background: url('https://iskconnews.org/media/images/2019/11-Nov/gev7_Scl3dEp.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #fff;
      padding: 20px;
      min-height: 100vh;
    }

    /* Headings */
    h1, h2 {
      color: #f8c146;
      text-shadow: 1px 1px 5px rgba(0,0,0,0.7);
    }

    h1 {
      font-size: 2.2rem;
      margin-bottom: 1.5rem;
    }

    h2 {
      font-size: 1.7rem;
      margin: 2rem 0 1rem 0;
    }

    /* Cards */
    .card {
      background-color: rgba(44, 44, 76, 0.85);
      border: none;
      border-radius: 15px;
      color: #fff;
      padding: 20px;
      text-align: center;
      width: 30%;
      box-shadow: 0 8px 20px rgba(0,0,0,0.5);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 30px rgba(0,0,0,0.7);
    }

    .card h2 {
      font-size: 1.8rem;
      margin-bottom: 0.5rem;
    }

    /* Stats Box */
    .stats-box {
      display: flex;
      justify-content: space-around;
      flex-wrap: wrap;
      gap: 20px;
      margin-bottom: 30px;
    }

    /* Tables */
    .table {
      background-color: rgba(30, 30, 46, 0.85);
      color: #fff;
      border-radius: 10px;
      overflow: hidden;
      margin-bottom: 2rem;
    }
    
    .table thead {
      background-color: rgba(44, 44, 76, 0.85);
    }
    
    .table tbody tr:hover {
      background-color: rgba(58, 58, 110, 0.8);
      transition: 0.3s;
    }

    /* Buttons */
    .btn-action {
      transition: 0.3s;
      margin: 2px;
    }
    
    .btn-action:hover {
      transform: translateY(-2px);
    }

    /* Table responsive wrapper */
    .table-wrapper {
      overflow-x: auto;
      margin-bottom: 2rem;
    }

    /* Text center for mobile */
    .text-center-mobile {
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1 class="mb-4 text-center">Admin Dashboard</h1>

    <!-- ✅ Stats Section -->
    <div class="stats-box">
      <div class="card">
        <h2><?= $loginCount ?></h2>
        <p>Total Active Users</p>
      </div>
      <div class="card">
        <h2><?= $totalDonors ?></h2>
        <p>Total Donors</p>
      </div>
      <div class="card">
        <h2>₹<?= number_format($totalDonations, 2) ?></h2>
        <p>Total Donations</p>
      </div>
    </div>

    <!-- ✅ Users Table -->
    <h2 class="mt-5 mb-3 text-center">Users Management</h2>
    <div class="table-wrapper">
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Password</th>
              <th>Role</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
              <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= $row['id'] ?></td>
                  <td><?= $row['Username'] ?></td>
                  <td><?= $row['Email'] ?></td>
                  <td><?= substr($row['Password'], 0, 10) ?>...</td>
                  <td><?= $row['Role'] ?></td>
                  <td><?= isset($row['is_deleted']) && $row['is_deleted'] ? 'Deleted' : 'Active' ?></td>
                  <td>
                    <div class="btn-group-vertical btn-group-sm">
                      <?php if (!isset($row['is_deleted']) || !$row['is_deleted']): ?>
                        <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm btn-action">Delete</a>
                        <a href="sign.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm btn-action">Edit</a>
                      <?php else: ?>
                        <a href="?restore=<?= $row['id'] ?>" class="btn btn-success btn-sm btn-action">Restore</a>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="7" class="text-center">No user accounts found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ✅ Donor Details Table -->
    <h2 class="mt-5 mb-3 text-center">Donor Details</h2>
    <div class="table-wrapper">
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Full Name</th>
              <th>Email Address</th>
              <th>Amount (₹)</th>
              <th>Payment Method</th>
              <th>Message</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $donorDetails = $conn->query("SELECT `id`, `Full Name`, `Email address`, `Donation Amount`, `Payment Method`, `Message` 
                                          FROM make_a_donation 
                                          ORDER BY id DESC");
            if ($donorDetails && $donorDetails->num_rows > 0): ?>
              <?php while($donor = $donorDetails->fetch_assoc()): ?>
                <tr>
                  <td><?= $donor['id'] ?></td>
                  <td><?= htmlspecialchars($donor['Full Name']) ?></td>
                  <td><?= htmlspecialchars($donor['Email address']) ?></td>
                  <td>₹<?= htmlspecialchars($donor['Donation Amount']) ?></td>
                  <td><?= htmlspecialchars($donor['Payment Method']) ?></td>
                  <td><?= strlen($donor['Message']) > 50 ? substr(htmlspecialchars($donor['Message']), 0, 50) . '...' : htmlspecialchars($donor['Message']) ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center">No donations yet.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ✅ Active Users Table -->
    <h2 class="mt-5 mb-3 text-center">Active Users</h2>
    <div class="table-wrapper">
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>User ID</th>
              <th>Name</th>
              <th>Email</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($activeUsers && $activeUsers->num_rows > 0): ?>
              <?php while($user = $activeUsers->fetch_assoc()): ?>
                <tr>
                  <td><?= $user['id'] ?></td>
                  <td><?= $user['Username'] ?></td>
                  <td><?= $user['Email'] ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="3" class="text-center">No active users.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ✅ Contact Messages Table -->
    <h2 class="mt-5 mb-3 text-center">Contact Messages</h2>
    <div class="table-wrapper">
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Message</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($contactMessages && $contactMessages->num_rows > 0): ?>
              <?php while($msg = $contactMessages->fetch_assoc()): ?>
                <tr>
                  <td><?= $msg['id'] ?></td>
                  <td><?= htmlspecialchars($msg['Name']) ?></td>
                  <td><?= htmlspecialchars($msg['E-mail']) ?></td>
                  <td><?= strlen($msg['Your message']) > 50 ? substr(htmlspecialchars($msg['Your message']), 0, 50) . '...' : htmlspecialchars($msg['Your message']) ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" class="text-center">No messages found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ✅ Active Donors Table -->
    <h2 class="mt-5 mb-3 text-center">Active Donors</h2>
    <div class="table-wrapper">
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>Donor Name</th>
              <th>Total Donated (₹)</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($activeDonors && $activeDonors->num_rows > 0): ?>
              <?php while($donor = $activeDonors->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($donor['name']) ?></td>
                  <td>₹<?= number_format($donor['total_amount'], 2) ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="2" class="text-center">No active donors.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>