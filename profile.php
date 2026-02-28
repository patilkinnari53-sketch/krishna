<?php
session_start();

// Redirect to login if user not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Set default profile info if not set
if (!isset($_SESSION['profile_image'])) {
    $_SESSION['profile_image'] = "https://cdn-icons-png.flaticon.com/512/149/149071.png";
}
if (!isset($_SESSION['credits'])) {
    $_SESSION['credits'] = 100;
}
if (!isset($_SESSION['storage_used'])) {
    $_SESSION['storage_used'] = "5 MB";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Account | Govardhan Eco Village</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f4f4;
      margin: 0;
      padding: 0;
    }
    header {
      background: #2c2c2c;
      color: #fff;
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    header h1 {
      margin: 0;
      font-family: 'Cinzel', serif;
    }
    header a {
      color: #fff;
      text-decoration: none;
      margin-left: 20px;
    }
    .container {
      max-width: 600px;
      margin: 40px auto;
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      text-align: center;
    }
    .profile-img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      margin-bottom: 15px;
    }
    h2 {
      margin-top: 10px;
    }
    table {
      width: 100%;
      margin-top: 20px;
      text-align: left;
    }
    table th, table td {
      padding: 8px;
    }
    .info-row {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
      margin-top: 12px;
      justify-content: center;
    }
    .actions {
      margin-top: 30px;
    }
    .actions a {
      background: gold;
      color: #000;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 30px;
      margin: 0 10px;
    }
    .actions a:hover {
      background: #ffd700;
    }

    /* Mobile Responsive Styles */
    @media screen and (max-width: 768px) {
      header {
        padding: 15px 20px;
        flex-direction: column;
        text-align: center;
        gap: 15px;
      }
      
      header h1 {
        font-size: 1.4rem;
      }
      
      header nav {
        display: flex;
        gap: 20px;
      }
      
      header a {
        margin-left: 0;
        font-size: 0.9rem;
      }
      
      .container {
        margin: 20px 15px;
        padding: 30px 20px;
      }
      
      .profile-img {
        width: 80px;
        height: 80px;
      }
      
      h2 {
        font-size: 1.3rem;
      }
      
      table {
        font-size: 0.9rem;
        display: block;
      }
      
      table tr {
        display: block;
        margin-bottom: 15px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
      }
      
      table th, table td {
        display: block;
        text-align: center;
        padding: 5px;
      }
      
      table th {
        font-weight: bold;
        background: #f9f9f9;
        margin-bottom: 5px;
      }
      
      .actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
      }
      
      .actions a {
        margin: 0;
        padding: 12px;
      }
    }

    @media screen and (max-width: 480px) {
      header h1 {
        font-size: 1.2rem;
      }
      
      .container {
        padding: 20px 15px;
      }
      
      .profile-img {
        width: 70px;
        height: 70px;
      }
      
      h2 {
        font-size: 1.1rem;
      }
      
      table {
        font-size: 0.85rem;
      }
    }
  </style>
</head>
<body>

<header>
  <h1>Govardhan Eco Village</h1>
  <nav>
    <a href="index.php">Home</a>
    <a href="logout.php">Logout</a>
  </nav>
</header>

<div class="container">
  <img src="<?= $_SESSION['profile_image'] ?>" class="profile-img" alt="User Avatar">
  <h2><?= htmlspecialchars($_SESSION['username']) ?></h2>
  <p>Welcome to your personal dashboard.</p>

  <table>
    <tr>
      <th>Email:</th>
      <td><?= htmlspecialchars($_SESSION['email']) ?></td>
    </tr>
      <tr>
    <tr>
  <th>Donation Amount:</th>
  <td><?= isset($_SESSION['donation_amount']) && $_SESSION['donation_amount'] ? $_SESSION['donation_amount'] . " ₹" : "No donations yet" ?></td>
</tr>
    <th>ID:</th>
    <td><?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "" ?></td>
  </tr>
</table>

  <div class="actions">
    <a href="forgot_password.php">Forgot Password</a>
    <a href="logout.php">Logout</a>
  </div>
</div>

</body>
</html>